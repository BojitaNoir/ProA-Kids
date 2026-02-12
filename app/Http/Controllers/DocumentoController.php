<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class DocumentoController extends Controller
{
    /**
     * Display a listing of the documents.
     */
    public function index()
    {
        $documentos = Documento::with(['uploader', 'categoria'])->latest()->get();
        $categorias = \App\Models\Categoria::all();

        return view('documentos.index', compact('documentos', 'categorias'));
    }

    /**
     * Show the form for creating a new document.
     */
    public function create()
    {
        // Admins and Doctors can access the upload form
        $userRole = Auth::user()->role;
        if ($userRole !== 'admin' && $userRole !== 'doctor') {
            abort(403, 'No tienes permiso para subir documentos.');
        }

        $categorias = \App\Models\Categoria::all();
        return view('documentos.create', compact('categorias'));
    }

    /**
     * Store a newly created document in storage.
     */
    public function store(Request $request)
    {
        $userRole = Auth::user()->role;
        if ($userRole !== 'admin' && $userRole !== 'doctor') {
            abort(403);
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
            'categoria_id' => 'required|exists:categorias,id',
            'archivo' => 'required|file|mimes:pdf|mimetypes:application/pdf|max:15360',
        ]);

        if ($request->hasFile('archivo')) {
            $path = $request->file('archivo')->store('documentos', 'public');

            // 1. Create Document Header
            $documento = Documento::create([
                'nombre' => $request->nombre,
                'categoria_id' => $request->categoria_id,
                'visibilidad' => 'public',
                'uploaded_by' => Auth::id(),
            ]);

            // 2. Create Initial Version
            $version = \App\Models\DocumentoVersion::create([
                'documento_id' => $documento->id,
                'version_number' => 1,
                'archivo_path' => $path,
                'cambios_realizados' => 'Versión inicial.',
                'created_by' => Auth::id(),
            ]);

            // 3. Link current version
            $documento->update(['current_version_id' => $version->id]);

            \App\Models\Bitacora::registrar('Subió archivo', "Se creó el documento '{$documento->nombre}' con la Versión 1.");

            return redirect()->route('documentos.index')
                ->with('success', 'Documento y versión inicial creados correctamente.');
        }

        return back()->with('error', 'Error al subir el archivo.');
    }

    /**
     * Download the specified document.
     */
    public function download($id)
    {
        $documento = Documento::findOrFail($id);
        $path = storage_path('app/public/' . $documento->archivo_path);

        if (!file_exists($path)) {
            abort(404, 'El archivo no existe en el servidor.');
        }

        return response()->download($path, $documento->nombre . '.pdf');
    }

    /**
     * View the specified document inline (Preview).
     */
    public function view($id)
    {
        $documento = Documento::findOrFail($id);
        $path = storage_path('app/public/' . $documento->archivo_path);

        if (!file_exists($path)) {
            abort(404, 'El archivo no existe en el servidor.');
        }

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $documento->nombre . '.pdf"',
            'Accept-Ranges' => 'bytes',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }


    /**
     * Store a newly created document via API (Mobile).
     */
    public function apiStore(Request $request)
    {
        // Forzar que la validación devuelva JSON incluso si no viene el header
        $request->headers->set('Accept', 'application/json');

        $request->validate([
            'nombre' => 'required|string|max:255',
            'categoria_id' => 'required', // Puede ser ID numérico o Nombre (ej: "Guía Clínica")
            'visibilidad' => 'required|in:public,private',
            'archivo' => 'required|file|mimes:pdf|max:10240', // 10MB limit
        ]);

        // 1. Resolver Categoría Dinámica
        $categoriaSearch = $request->categoria_id;
        $categoria = \App\Models\Categoria::where('id', $categoriaSearch)
            ->orWhere('nombre', $categoriaSearch)
            ->first();

        if (!$categoria) {
            return response()->json([
                'message' => 'La categoría proporcionada no existe en el sistema.'
            ], 422);
        }

        if ($request->hasFile('archivo')) {
            $path = $request->file('archivo')->store('documentos', 'public');

            // 1. Create Document Header
            $documento = Documento::create([
                'nombre' => $request->nombre,
                'categoria_id' => $categoria->id,
                'visibilidad' => $request->visibilidad,
                'uploaded_by' => auth()->id() ?? 1,
            ]);

            // 2. Create Initial Version
            $version = \App\Models\DocumentoVersion::create([
                'documento_id' => $documento->id,
                'version_number' => 1,
                'archivo_path' => $path,
                'cambios_realizados' => 'Subida inicial desde API móvil.',
                'created_by' => auth()->id() ?? 1,
            ]);

            // 3. Link current version
            $documento->update(['current_version_id' => $version->id]);

            // Registrar en Bitácora
            $userName = auth()->user()->name ?? 'Usuario Móvil';
            \App\Models\Bitacora::registrar(
                'Subida Móvil',
                "El usuario '{$userName}' subió el documento '{$documento->nombre}' v1 desde la App."
            );

            return response()->json([
                'message' => 'Documento subido con éxito.',
                'documento' => [
                    'id' => $documento->id,
                    'nombre' => $documento->nombre,
                    'version' => 1,
                    'categoria' => $categoria->nombre,
                    'visibilidad' => $documento->visibilidad,
                    'preview_url' => route('documentos.view', $documento->id),
                    'download_url' => route('documentos.download', $documento->id),
                ]
            ], 201);
        }

        return response()->json(['message' => 'Error al subir el archivo.'], 400);
    }

    /**
     * Show the form for editing the specified document.
     */
    public function edit($id)
    {
        $documento = Documento::findOrFail($id);

        if (Auth::user()->role !== 'admin' && Auth::id() !== $documento->uploaded_by) {
            abort(403);
        }

        $categorias = \App\Models\Categoria::all();
        return view('documentos.edit', compact('documento', 'categorias'));
    }

    /**
     * Update the specified document in storage.
     */
    public function update(Request $request, $id)
    {
        $documento = Documento::findOrFail($id);

        if (Auth::user()->role !== 'admin' && Auth::id() !== $documento->uploaded_by) {
            abort(403);
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
            'categoria_id' => 'required|exists:categorias,id',
            'archivo' => 'nullable|file|mimes:pdf|max:15360',
            'cambios' => 'nullable|string',
        ]);

        // 1. Update Metadata
        $documento->update($request->only('nombre', 'categoria_id'));

        // 2. Handle New Version if file provided
        if ($request->hasFile('archivo')) {
            $path = $request->file('archivo')->store('documentos', 'public');
            $newVersionNumber = $documento->versiones()->max('version_number') + 1;

            $version = \App\Models\DocumentoVersion::create([
                'documento_id' => $documento->id,
                'version_number' => $newVersionNumber,
                'archivo_path' => $path,
                'cambios_realizados' => $request->cambios ?? "Actualización a versión {$newVersionNumber}.",
                'created_by' => Auth::id(),
            ]);

            $documento->update(['current_version_id' => $version->id]);

            \App\Models\Bitacora::registrar('Subió nueva versión', "Se actualizó '{$documento->nombre}' a la Versión {$newVersionNumber}.");
        } else {
            \App\Models\Bitacora::registrar('Modificó archivo', "Se actualizó metadatos de '{$documento->nombre}'.");
        }

        return redirect()->route('documentos.index')
            ->with('success', 'Documento actualizado correctamente.');
    }

    /**
     * Remove the specified document from storage.
     */
    public function destroy($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $documento = Documento::findOrFail($id);

        // Borrado lógico (SoftDelete)
        \App\Models\Bitacora::registrar('Eliminó archivo (Soft)', "Se movió a papelera el documento '{$documento->nombre}'.");
        $documento->delete();

        return redirect()->route('documentos.index')
            ->with('success', 'Documento movido a la papelera (puedes restaurarlo si es necesario).');
    }
    /**
     * Show sections/apartados for a specific document.
     */
    public function apartados($id)
    {
        $documento = Documento::with('apartados')->findOrFail($id);
        return view('documentos.apartados', compact('documento'));
    }

    /**
     * Store a new section/apartado.
     */
    public function storeApartado(Request $request, $id)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'fragmento_texto' => 'nullable|string',
            'archivo' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $path = null;
        if ($request->hasFile('archivo')) {
            $path = $request->file('archivo')->store('apartados', 'public');
        }

        \App\Models\DocumentoApartado::create([
            'documento_id' => $id,
            'titulo' => $request->titulo,
            'fragmento_texto' => $request->fragmento_texto,
            'archivo_path' => $path,
            'orden' => \App\Models\DocumentoApartado::where('documento_id', $id)->count() + 1,
        ]);

        return back()->with('success', 'Apartado agregado correctamente.');
    }
}

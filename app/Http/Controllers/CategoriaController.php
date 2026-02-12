<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Storage;

class CategoriaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $categorias = Categoria::withCount('documentos')->get();
        return view('categorias.index', compact('categorias'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'nombre' => 'required|string|unique:categorias,nombre|max:255',
            'icono' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
        ]);

        $data = $request->only('nombre');

        if ($request->hasFile('icono')) {
            $path = $request->file('icono')->store('categorias', 'public');
            $data['icono'] = $path;
        }

        $categoria = Categoria::create($data);

        Bitacora::registrar('Creó categoría', "Se creó la categoría '{$categoria->nombre}' con imagen de icono.");

        return back()->with('success', 'Categoría creada correctamente.');
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $categoria = Categoria::findOrFail($id);

        $request->validate([
            'nombre' => "required|string|unique:categorias,nombre,{$id}|max:255",
            'icono' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
        ]);

        $data = $request->only('nombre');

        if ($request->hasFile('icono')) {
            // Eliminar icono anterior si existe
            if ($categoria->icono) {
                Storage::disk('public')->delete($categoria->icono);
            }
            $path = $request->file('icono')->store('categorias', 'public');
            $data['icono'] = $path;
        }

        $nombreAnterior = $categoria->nombre;
        $categoria->update($data);

        Bitacora::registrar('Editó categoría', "Se actualizó la categoría '{$nombreAnterior}' (ID: {$id}).");

        return back()->with('success', 'Categoría actualizada correctamente.');
    }

    public function destroy($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $categoria = Categoria::findOrFail($id);

        if ($categoria->documentos()->count() > 0) {
            return back()->with('error', 'No se puede eliminar una categoría que contiene documentos.');
        }

        Bitacora::registrar('Eliminó categoría', "Se eliminó la categoría '{$categoria->nombre}'.");
        $categoria->delete();

        return back()->with('success', 'Categoría eliminada correctamente.');
    }
}

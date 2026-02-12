<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ValidacionController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\BitacoraController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Public Document Access (Liberated for Mobile & Ease of use)
Route::get('/documentos/{id}/download', [App\Http\Controllers\DocumentoController::class, 'download'])->name('documentos.download');
Route::get('/documentos/{id}/view', [App\Http\Controllers\DocumentoController::class, 'view'])->name('documentos.view');

// Quick Access for Medical Staff
Route::post('/quick-login', [AuthController::class, 'quickLogin'])->name('quick-login');

// Protected Routes (require authentication)
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('dashboard');
        }
        return redirect()->route('doctor.dashboard');
    })->name('home');

    // Shared Document Routes (Admin & Doctors)
    Route::get('/documentos', [App\Http\Controllers\DocumentoController::class, 'index'])->name('documentos.index');
    Route::get('/documentos/subir', [App\Http\Controllers\DocumentoController::class, 'create'])->name('documentos.create');
    Route::post('/documentos', [App\Http\Controllers\DocumentoController::class, 'store'])->name('documentos.store');
    Route::get('/documentos/{id}/editar', [App\Http\Controllers\DocumentoController::class, 'edit'])->name('documentos.edit');
    Route::put('/documentos/{id}', [App\Http\Controllers\DocumentoController::class, 'update'])->name('documentos.update');

    // Admin only routes
    Route::middleware('role:admin')->group(function () {
        Route::get('/dashboard', function () {
            $docCount = \App\Models\Documento::count();
            $userCount = \App\Models\User::count();

            // Reemplazamos actividad analítica por una vista simple de recientes
            $recientes = \App\Models\Documento::with(['uploader', 'categoria'])->latest()->take(8)->get();
            $bitacoraReciente = \App\Models\Bitacora::with('user')->latest()->take(3)->get();

            return view('dashboard', compact('docCount', 'userCount', 'recientes', 'bitacoraReciente'));
        })->name('dashboard');

        // Administration (Users)
        Route::get('/usuarios', [App\Http\Controllers\UsuarioController::class, 'index'])->name('usuarios.index');
        Route::get('/usuarios/create', [App\Http\Controllers\UsuarioController::class, 'create'])->name('usuarios.create');
        Route::post('/usuarios', [App\Http\Controllers\UsuarioController::class, 'store'])->name('usuarios.store');
        Route::get('/usuarios/{id}/edit', [App\Http\Controllers\UsuarioController::class, 'edit'])->name('usuarios.edit');
        Route::put('/usuarios/{id}', [App\Http\Controllers\UsuarioController::class, 'update'])->name('usuarios.update');
        Route::delete('/usuarios/{id}', [App\Http\Controllers\UsuarioController::class, 'destroy'])->name('usuarios.destroy');

        // Bajas de documentos (Solo Admin)
        Route::delete('/documentos/{id}', [DocumentoController::class, 'destroy'])->name('documentos.destroy');

        // Gestión de Categorías (Admin)
        Route::get('/categorias', [CategoriaController::class, 'index'])->name('categorias.index');
        Route::post('/categorias', [CategoriaController::class, 'store'])->name('categorias.store');
        Route::put('/categorias/{id}', [CategoriaController::class, 'update'])->name('categorias.update');
        Route::delete('/categorias/{id}', [CategoriaController::class, 'destroy'])->name('categorias.destroy');

        // Bitácora del Sistema (Admin)
        Route::get('/bitacora', [BitacoraController::class, 'index'])->name('bitacora.index');

        // Perfil (Admin Only)
        Route::get('/mi-perfil', [App\Http\Controllers\PerfilController::class, 'edit'])->name('perfil.edit');
        Route::put('/mi-perfil', [App\Http\Controllers\PerfilController::class, 'update'])->name('perfil.update');
    });

    // Doctor only / Clinical routes
    Route::get('/portal-medico', function () {
        return view('doctor.dashboard');
    })->name('doctor.dashboard');



    // Dashboard Real-time API
    Route::get('/api/admin/stats', function () {
        return response()->json([
            'totalValidaciones' => \App\Models\RegistroValidacion::count(),
            'alertasCriticas' => \App\Models\RegistroValidacion::where('resultado', 'CRÍTICO')->count(),
            'validacionesExitosas' => \App\Models\RegistroValidacion::where('resultado', 'EXITOSO')->count(),
            'conformidad' => (\App\Models\RegistroValidacion::count() > 0 ? round((\App\Models\RegistroValidacion::where('resultado', 'EXITOSO')->count() / \App\Models\RegistroValidacion::count()) * 100, 1) : 100) . '%',
            'docCount' => \App\Models\Documento::count(),
            'actividad' => \App\Models\RegistroValidacion::with('user')->latest()->take(10)->get()->map(function ($log) {
                return [
                    'id' => str_pad($log->id, 4, '0', STR_PAD_LEFT),
                    'user' => $log->user->name,
                    'uploaded_by_id' => $log->user_id,
                    'uploaded_by_name' => $log->user->name,
                    'medicamento' => $log->medicamento,
                    'patologia' => $log->patologia,
                    'resultado' => $log->resultado,
                    'tiempo' => $log->created_at->diffForHumans()
                ];
            })
        ]);
    })->name('api.stats');
});

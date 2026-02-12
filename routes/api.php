<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ValidacionController;

/*
|--------------------------------------------------------------------------
| API Routes - PROA Medical Platform
|--------------------------------------------------------------------------
|
| Rutas protegidas con API Key para validaciones clínicas
|
*/

// Rutas de validación médica (requieren X-API-KEY header)
Route::post('/validar', [ValidacionController::class, 'validar']);

// Rutas auxiliares para obtener catálogos
Route::get('/patologias', [ValidacionController::class, 'obtenerPatologias']);
Route::get('/medicamentos', [ValidacionController::class, 'obtenerMedicamentos']);

// Nueva ruta para el repositorio documental (móvil) - Protegida
Route::get('/documentos', [ValidacionController::class, 'obtenerDocumentos'])->middleware('auth:sanctum');

// Subida de documentos desde móvil (Fase 1)
Route::post('/documentos', [App\Http\Controllers\DocumentoController::class, 'apiStore'])->middleware('auth:sanctum');

// Autenticación para App Móvil
Route::post('/login', [App\Http\Controllers\AuthController::class, 'apiLogin']);

// Perfil de usuario (Protegido)
Route::get('/profile', [App\Http\Controllers\AuthController::class, 'getProfile'])->middleware('auth:sanctum');

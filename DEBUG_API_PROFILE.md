# Diagnóstico de Endpoint: GET /api/profile

Este documento contiene la información técnica necesaria para diagnosticar por qué el endpoint de perfil devuelve un error 404.

# 1. Route List
Equivalente a `php artisan route:list`. Rutas filtradas relacionadas con el diagnóstico:

| Method | URI | Name | Action | Middleware |
| :--- | :--- | :--- | :--- | :--- |
| POST | `api/login` | | `App\Http\Controllers\AuthController@apiLogin` | `api` |
| GET | `api/documentos` | | `App\Http\Controllers\ValidacionController@obtenerDocumentos` | `api`, `auth:sanctum` |
| GET | `api/profile` | | `App\Http\Controllers\AuthController@getProfile` | `api`, `auth:sanctum` |

# 2. Ruta actual del perfil
Definición exacta en `routes/api.php`:

```php
// Perfil de usuario (Protegido)
Route::get('/profile', [App\Http\Controllers\AuthController::class, 'getProfile'])->middleware('auth:sanctum');
```

**Contexto**: Esta ruta se encuentra en el archivo `routes/api.php`. Por definición global en el `RouteServiceProvider`, Laravel le aplica automáticamente el prefijo `api/`.

# 3. Método en AuthController
Método encargado de procesar la petición en `app/Http/Controllers/AuthController.php`:

```php
/**
 * Get authenticated user profile data
 */
public function getProfile(Request $request)
{
    return response()->json([
        'user' => [
            'id' => $request->user()->id,
            'name' => $request->user()->name,
            'email' => $request->user()->email,
            'role' => $request->user()->role,
            'created_at' => $request->user()->created_at->format('Y-m-d H:i:s'),
        ]
    ]);
}
```
**Retorno**: Devuelve un objeto JSON con la llave `user` que contiene los detalles básicos del usuario autenticado mediante el token de Sanctum.

# 4. Middleware aplicado
La ruta utiliza los siguientes middlewares:
*   **api**: Grupo de middleware base para todas las rutas de la API (incluye `SubstituteBindings`, etc.).
*   **auth:sanctum**: Middleware de autenticación de Laravel Sanctum. Si el token no es enviado o es inválido, Laravel debería devolver un 401 (No autorizado), no un 404.

# 5. Base URL esperada
Basado en un entorno estándar de `php artisan serve`:

*   **Host**: `127.0.0.1` (o la IP asignada con `--host 0.0.0.0`)
*   **Puerto**: `8000`
*   **URL Completa del Endpoint**: `http://127.0.0.1:8000/api/profile`

# 6. Notas
*   **Prefijo Global**: En `app/Providers/RouteServiceProvider.php` (línea 29), se define el prefijo `api` para todas las rutas dentro de `routes/api.php`.
*   **Caché de Rutas**: Si las rutas han sido modificadas recientemente y no se reflejan, se recomienda ejecutar `php artisan route:clear`.
*   **Sanctum Config**: `config/auth.php` usa el guard `sanctum` que apunta al driver de tokens de API.

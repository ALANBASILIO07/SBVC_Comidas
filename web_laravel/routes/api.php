<?php
/*
* Nombre de la clase         : api.php
* Descripción de la clase    : Archivo de rutas de la API REST para la aplicación móvil SBVC_Comidas.
*                               Define todos los endpoints accesibles desde Flutter.
* Fecha de creación          : 16/12/2024
* Elaboró                    : Claude Code Assistant
* Fecha de liberación        : 16/12/2024
* Autorizó                   : Alan Basilio
* Versión                    : 1.0
* Fecha de mantenimiento     :
* Folio de mantenimiento     :
* Tipo de mantenimiento      :
* Descripción del mantenimiento :
* Responsable                :
* Revisor                    :
*/

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\EstablecimientoApiController;
use App\Http\Controllers\Api\CategoriaApiController;
use App\Http\Controllers\Api\ResenaApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Aquí es donde puedes registrar las rutas API para tu aplicación. Estas
| rutas son cargadas por el RouteServiceProvider y todas ellas serán
| asignadas al grupo de middleware "api". ¡Haz algo grandioso!
|
*/

// ====================================================================
// RUTAS DE AUTENTICACIÓN (Públicas)
// ====================================================================
Route::prefix('auth')->group(function () {
    // Registro de nuevo usuario
    Route::post('/register', [AuthApiController::class, 'register'])
        ->middleware('throttle:6,1');

    // Login de usuario
    Route::post('/login', [AuthApiController::class, 'login'])
        ->middleware('throttle:10,1');

    // Rutas protegidas por autenticación
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthApiController::class, 'me']);
        Route::post('/logout', [AuthApiController::class, 'logout']);
    });
});

// ====================================================================
// RUTAS DE CATEGORÍAS (Públicas)
// ====================================================================
Route::prefix('categorias')->group(function () {
    // Listar todas las categorías
    Route::get('/', [CategoriaApiController::class, 'index']);

    // Categorías agrupadas por tipo (formal/informal)
    Route::get('/agrupadas', [CategoriaApiController::class, 'agrupadasPorTipo']);

    // Detalles de una categoría
    Route::get('/{id}', [CategoriaApiController::class, 'show']);
});

// ====================================================================
// RUTAS DE ESTABLECIMIENTOS (Públicas)
// ====================================================================
Route::prefix('establecimientos')->group(function () {
    // Listar establecimientos con filtros
    Route::get('/', [EstablecimientoApiController::class, 'index']);

    // Detalles de un establecimiento
    Route::get('/{id}', [EstablecimientoApiController::class, 'show']);

    // Establecimientos por categoría
    Route::get('/categoria/{categoriaId}', [EstablecimientoApiController::class, 'porCategoria']);

    // Buscar establecimientos cercanos (geolocalización)
    Route::get('/cercanos/buscar', [EstablecimientoApiController::class, 'cercanos']);

    // Buscar establecimientos por término
    Route::get('/buscar/termino', [EstablecimientoApiController::class, 'buscar']);
});

// ====================================================================
// RUTAS DE RESEÑAS
// ====================================================================
Route::prefix('resenas')->group(function () {
    // Obtener reseñas de un establecimiento (público)
    Route::get('/establecimiento/{id}', [ResenaApiController::class, 'porEstablecimiento']);

    // Rutas protegidas (requieren autenticación)
    Route::middleware('auth:sanctum')->group(function () {
        // Crear reseña
        Route::post('/', [ResenaApiController::class, 'store']);

        // Actualizar reseña
        Route::put('/{id}', [ResenaApiController::class, 'update']);

        // Eliminar reseña
        Route::delete('/{id}', [ResenaApiController::class, 'destroy']);
    });
});

// ====================================================================
// RUTA DE HEALTH CHECK
// ====================================================================
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'service' => 'SBVC_Comidas API',
        'version' => '1.0',
        'timestamp' => now()->toISOString(),
    ]);
});

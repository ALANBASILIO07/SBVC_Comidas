<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Rutas públicas (sin autenticación)
Route::post('/register', [App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'login']);

// Rutas protegidas con autenticación Sanctum
Route::middleware('auth:sanctum')->group(function () {
    // Autenticación
    Route::post('/logout', [App\Http\Controllers\Api\AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // Establecimientos
    Route::apiResource('establecimientos', App\Http\Controllers\Api\EstablecimientoController::class);
    
    // Promociones
    Route::apiResource('promociones', App\Http\Controllers\Api\PromocionController::class);
    
    // Banners
    Route::apiResource('banners', App\Http\Controllers\Api\BannerController::class);
    
    // Categorías (solo lectura)
    Route::get('categorias', [App\Http\Controllers\Api\CategoriaController::class, 'index']);
    
    // Calificaciones
    Route::get('calificaciones', [App\Http\Controllers\Api\CalificacionController::class, 'index']);
    Route::post('calificaciones', [App\Http\Controllers\Api\CalificacionController::class, 'store']);
});

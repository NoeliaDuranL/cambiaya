<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\MensajeController;

// Rutas para los controladores usando apiResource
Route::apiResource('persona', PersonaController::class);
Route::apiResource('usuario', UsuarioController::class);
Route::apiResource('producto', ProductoController::class);
Route::apiResource('post', PostController::class);
Route::apiResource('notificacion', NotificacionController::class);
Route::apiResource('mensaje', MensajeController::class);

// Ruta de ejemplo proporcionada por Laravel
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


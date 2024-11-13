<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\MensajeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostProductoController;
use App\Http\Controllers\ImageController;

// Rutas para los controladores usando apiResource
Route::apiResource('persona', PersonaController::class);
Route::apiResource('usuario', UsuarioController::class);
Route::apiResource('producto', ProductoController::class);
Route::apiResource('post', PostController::class);
Route::apiResource('notificacion', NotificacionController::class);
Route::apiResource('mensaje', MensajeController::class);
Route::apiResource('post-producto', PostProductoController::class)->only(['store', 'destroy']);;
Route::post('/upload-image', [ImageController::class, 'store']);





// Rutas para login y registro
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

// Ruta de ejemplo proporcionada por Laravel
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


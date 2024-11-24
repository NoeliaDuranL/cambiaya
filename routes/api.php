<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\MensajeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductPostUpController;

use App\Http\Controllers\PostProductoController;
use App\Http\Controllers\ImageController;

// Rutas para los controladores usando apiResource
Route::apiResource('persona', PersonaController::class);

Route::apiResource('usuario', UsuarioController::class);
Route::apiResource('producto', ProductoController::class);

Route::apiResource('post', PostController::class);

Route::apiResource('mensaje', MensajeController::class);
// Ruta para obtener el ID del usuario por correo
Route::post('/id_user', [AuthController::class, 'getUserIdByEmail']);

// Route::apiResource('post-producto', PostProductoController::class)->only(['store', 'destroy']);
// Route::post('/upload-image', [ImageController::class, 'store']);

// routes/api.php
// Ruta para subir la imagen
// Route::post('/upload-image', [ProductoImageController::class, 'uploadImage']);


// Route::post('/notificacion', [FCMController::class, 'store']);
Route::apiResource('notificacion', NotificacionController::class);
Route::post('notificacion/meinteresa', [NotificacionController::class, 'meInteresa']);
Route::post('/send-to-device', [NotificacionController::class, 'sendToDevice']);

// Ruta para crear el producto y el post
Route::post('/post-producto', [ProductPostUpController::class, 'createProductAndPost']);








// Rutas para login y registro
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
// Route::post('products/uploadImage', [ProductoController::class, 'uploadImage']);
// Route::post('products', [ProductoController::class, 'createProduct']);
// Ruta de ejemplo proporcionada por Laravel



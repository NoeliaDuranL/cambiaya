<?php
// App\Http\Controllers\PostProductoController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PostProductoController extends Controller
{
    /**
     * Crear un producto y un post asociado.
     */
    public function store(Request $request)
    {
        \Log::info('Datos recibidos en la solicitud:', $request->all());
        // Decodificar el JSON enviado como 'producto'
        $productoData = json_decode($request->input('data'), true);
        \Log::info('Datos del producto:', $productoData);

        // Validación de los datos del producto
        $request->validate([
            'data.descripcion' => 'required|string|max:500',
            'data.id_usuario' => 'required|exists:usuario,id_usuario',
            'data.nombre' => 'required|string|max:255', // Verifica que el campo 'nombre' sea obligatorio
            'imagen_url' => 'required|url', // Validar la URL de la imagen
        ]);

        try {
            // Obtener la URL de la imagen
            $imageUrl = $request->input('imagen_url');

            // Crear el producto en la base de datos
            $producto = Producto::create([
                'nombre' => $productoData['nombre'],
                'descripcion' => $productoData['descripcion'],
                'imagen' => $imageUrl,  // Usar la URL de la imagen
                'estado' => 1,
            ]);

            // Crear el post
            $post = Post::create([
                'id_producto' => $producto->id_producto,
                'id_usuario' => $productoData['id_usuario'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Producto y post creados exitosamente.',
                'producto' => $producto,
                'post' => $post
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error al crear el producto o post', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error inesperado: ' . $e->getMessage()
            ], 500);
        }
    }
}

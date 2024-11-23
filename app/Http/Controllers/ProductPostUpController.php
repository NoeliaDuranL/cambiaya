<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// app/Http/Controllers/ProductPostUpController.php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Post;
use Illuminate\Http\Request;

class ProductPostUpController extends Controller
{


    public function createProductAndPost(Request $request)
    {
        // Log para ver los datos recibidos
        \Log::info('Datos recibidos:', $request->all());

        // Validar los datos recibidos
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image_url' => 'required|string',
            'user_id' => 'required|integer|exists:usuario,id_usuario', // Validar que el ID de usuario exista
        ]);

        \Log::info('Datos validados:', $validated);

        // Iniciar una transacción para asegurarse de que ambos registros se creen o ninguno
        \DB::beginTransaction();

        try {
            // Crear el producto
            $producto = Producto::create([
                'nombre' => $validated['name'],
                'descripcion' => $validated['description'],
                'imagen' => $validated['image_url'],
                'estado' => 1, // Asumiendo que el estado por defecto es activo
            ]);

            // Log para ver si el producto fue creado
            \Log::info('Producto creado:', $producto->toArray());

            // Crear el post asociado al producto
            $post = Post::create([
                'id_producto' => $producto->id_producto,
                'id_usuario' => $validated['user_id'],
                'estado' => 1, // El post también se marca como activo
            ]);

            // Log para ver si el post fue creado
            \Log::info('Post creado:', $post->toArray());

            // Si todo está bien, confirmar la transacción
            \DB::commit();

            // Retornar una respuesta exitosa
            return response()->json([
                'success' => true,
                'message' => 'Producto y Post creados con éxito',
                'producto' => $producto,
                'post' => $post
            ], 201);

        } catch (\Exception $e) {
            // Si algo falla, deshacer los cambios
            \DB::rollBack();

            // Log del error para depuración
            \Log::error('Error al crear producto y post: ' . $e->getMessage());

            // Retornar error
            return response()->json([
                'success' => false,
                'message' => 'Hubo un error al crear el producto y el post',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}


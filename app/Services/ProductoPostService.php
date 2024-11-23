<?php

namespace App\Services;

use App\Models\Producto;
use App\Models\Post;
use Illuminate\Support\Facades\DB;

class ProductoPostService
{
    /**
     * Crear un producto y un post asociado.
     */


    public static function createProductoAndPost($data)
    {
        return DB::transaction(function () use ($data) {
            $producto = Producto::create([
                'nombre' => $data['nombre_producto'],
                'descripcion' => $data['descripcion_producto'],
                'imagen' => $data['imagen'] ?? null,
                'estado' => 1,
            ]);

            $post = Post::create([
                'id_producto' => $producto->id_producto,
                'id_usuario' => $data['id_usuario'],
                'estado' => 1,
            ]);

            return compact('producto', 'post');
        });
    }


    /**
     * Eliminar un producto y su post asociado.
     */
    public static function deleteProductoAndPost($postId)
    {
        $post = Post::findOrFail($postId);
        $producto = Producto::findOrFail($post->id_producto);

        $post->delete();
        $producto->delete();
    }
}

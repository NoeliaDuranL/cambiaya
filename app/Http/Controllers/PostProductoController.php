<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Post;
use Illuminate\Http\Request;

class PostProductoController extends Controller
{
    /**
     * Crear un producto y un post asociado.
     */
    public function store(Request $request)
    {
        // Validar los datos
        $request->validate([
            'nombre_producto' => 'required|string|max:255',
            'descripcion_producto' => 'required|string',
            'imagen' => 'nullable|url',
            'descripcion_post' => 'required|string',
            'id_usuario' => 'required|exists:Usuario,id_usuario',
        ]);

        // Crear el producto
        $producto = Producto::create([
            'nombre' => $request->input('nombre_producto'),
            'descripcion' => $request->input('descripcion_producto'),
            'imagen' => $request->input('imagen'),
            'estado' => 1,
        ]);

        // Crear el post asociado al producto
        $post = Post::create([
            'id_producto' => $producto->id_producto,
            'id_usuario' => $request->input('id_usuario'),
            'descripcion' => $request->input('descripcion_post'),
            'estado' => 1,
        ]);

        return response()->json(['producto' => $producto, 'post' => $post], 201);
    }

    /**
     * Eliminar un producto y su post asociado.
     */
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $producto = Producto::findOrFail($post->id_producto);

        $post->delete();
        $producto->delete();

        return response()->json(['message' => 'Producto y post eliminados con éxito']);
    }
}

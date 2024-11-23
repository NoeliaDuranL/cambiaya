<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PostController extends Controller
{
    /**
     * Listar todos los posts.
     */
    public function index()
    {
        $posts = Post::with(['producto', 'usuario'])->get();
        return response()->json([
            'success' => true,
            'data' => $posts,
        ]);
    }

    /**
     * Mostrar un post por ID.
     */
    public function show($id)
    {
        try {
            $post = Post::with(['producto', 'usuario'])->findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $post,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Post no encontrado',
            ], 404);
        }
    }

    /**
     * Crear un nuevo post.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_producto' => 'required|exists:producto,id_producto',
            'id_usuario' => 'required|exists:usuario,id_usuario',
            'estado' => 'nullable|integer',
        ]);

        $post = Post::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $post,
        ], 201);
    }

    /**
     * Actualizar un post.
     */
    public function update(Request $request, $id)
    {
        try {
            $post = Post::findOrFail($id);

            $request->validate([
                'id_producto' => 'nullable|exists:producto,id_producto',
                'id_usuario' => 'nullable|exists:usuario,id_usuario',
                'estado' => 'nullable|integer',
            ]);

            $post->update($request->only(['id_producto', 'id_usuario', 'estado']));

            return response()->json([
                'success' => true,
                'data' => $post,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Post no encontrado',
            ], 404);
        }
    }

    /**
     * Eliminar un post.
     */
    public function destroy($id)
    {
        try {
            $post = Post::findOrFail($id);
            $post->delete();

            return response()->json([
                'success' => true,
                'message' => 'Post eliminado con éxito',
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Post no encontrado',
            ], 404);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Listar todos los productos.
     */
    public function index()
    {
        return response()->json(Producto::all());
    }

    /**
     * Mostrar un producto por ID.
     */
    public function show($id)
    {
        $producto = Producto::findOrFail($id);
        return response()->json($producto);
    }

    /**
     * Subir una imagen para un producto.
     */
    public function uploadImage(Request $request)
    {
        // Validar que el archivo sea una imagen
        $request->validate([
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Guardar la imagen en el directorio `public/images`
        $imageName = time() . '_' . $request->file('imagen')->getClientOriginalName();
        $imagePath = $request->file('imagen')->move(public_path('images'), $imageName);

        return response()->json([
            'success' => true,
            'message' => 'Imagen subida con éxito',
            'imagePath' => url('images/' . $imageName), // URL completa de la imagen
        ]);
    }

    /**
     * Crear un nuevo producto.
     */
    public function store(Request $request)
    {
        // Validar los datos del producto
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string|max:500',
            'imagen' => 'nullable|string', // Path opcional de la imagen
        ]);

        // Crear el producto
        $producto = Producto::create([
            'nombre' => $request->input('nombre'),
            'descripcion' => $request->input('descripcion'),
            'imagen' => $request->input('imagen'), // Path opcional de la imagen
        ]);

        return response()->json([
            'success' => true,
            'producto' => $producto,
        ], 201);
    }

    /**
     * Actualizar un producto existente.
     */
    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);

        // Validar los datos del producto
        $request->validate([
            'nombre' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string|max:500',
            'imagen' => 'nullable|string', // Puede incluir un path o ser nulo
        ]);

        // Actualizar los datos del producto
        $producto->update($request->only(['nombre', 'descripcion', 'imagen']));

        return response()->json([
            'success' => true,
            'producto' => $producto,
        ]);
    }

    /**
     * Eliminar un producto por ID.
     */
    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);

        // Verificar y eliminar la imagen asociada
        if ($producto->imagen) {
            $imagePath = public_path('images/' . basename($producto->imagen));
            if (file_exists($imagePath)) {
                unlink($imagePath); // Eliminar la imagen físicamente
            }
        }

        // Eliminar el producto
        $producto->delete();

        return response()->json([
            'success' => true,
            'message' => 'Producto eliminado con éxito',
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    public function index()
    {
        return response()->json(Producto::all());
    }

    public function show($id)
    {
        $producto = Producto::findOrFail($id);
        return response()->json($producto);
    }


    public function uploadImage(Request $request)
    {
        // Validar que la imagen se haya enviado
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Subir la imagen y obtener el nombre del archivo
        $imagePath = $request->file('image')->store('images'); // Guarda en la carpeta 'images'

        // Aquí puedes guardar el $imagePath en la base de datos si es necesario

        return response()->json([
            'success' => true,
            'message' => 'Image uploaded successfully',
            'imagePath' => $imagePath
        ]);
    }


    public function createProduct(Request $request)
    {
        // Validar los datos del producto
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'imagen' => 'required|string', // Suponemos que se pasa la ruta de la imagen como string
        ]);

        // Crear el producto en la base de datos
        $product = Producto::create([
            'nombre' => $request->input('nombre'),
            'descripcion' => $request->input('descripcion'),
            'imagen' => $request->input('imagen'), // Guardar la ruta de la imagen
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Producto creado con éxito',
            'producto' => $product
        ]);
    }
    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);
        $producto->update($request->all());
        return response()->json($producto);
    }

    public function destroy($id)
    {
        Producto::destroy($id);
        return response()->json(['message' => 'Producto eliminado con éxito']);
    }
}

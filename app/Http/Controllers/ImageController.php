<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Producto; // Asegúrate de importar el modelo Producto

class ImageController extends Controller
{
    public function store(Request $request)
    {
        // Valida que el archivo esté presente y sea una imagen
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'id_producto' => 'nullable|integer|exists:Producto,id_producto', // ID del producto opcional
        ]);

        // Guarda la imagen en el sistema de archivos configurado (ej., storage/app/public/images)
        $path = $request->file('image')->store('public/images');

        // Obtiene la URL de la imagen almacenada
        $url = Storage::url($path);

        // Si se proporciona un ID de producto, guarda la URL en la tabla Producto
        if ($request->filled('id_producto')) {
            $producto = Producto::find($request->id_producto);
            $producto->imagen = $url; // Asegúrate de que el campo "imagen" exista en la tabla Producto
            $producto->save();
        }

        // Retorna la URL en la respuesta para que puedas guardarla en tu base de datos
        return response()->json(['url' => $url], 201);
    }
}

<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProductoImageController extends Controller
{
    /**
     * Subir una imagen.
     */
    public function uploadImage(Request $request)
    {

        \Log::info('Subiendo imagen');
        \Log::info($request->all());
        // Validación del archivo
        $request->validate([
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif,webp,bmp|max:20480', // Validación de imagen
        ]);

        try {
            // Verificar si hay un archivo de imagen
            if ($request->hasFile('imagen')) {
                $archivo = $request->file('imagen');
                // Subir el archivo a la carpeta 'productos' en el disco 'public'
                $imagePath = $archivo->store('productos', 'public');
                return response()->json([
                    'success' => true,
                    'message' => 'Imagen subida con éxito.',
                    'image_url' => Storage::url($imagePath),
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'No se ha recibido ninguna imagen.',
            ], 400);
        } catch (\Exception $e) {
            Log::error('Error al subir la imagen', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error al subir la imagen: ' . $e->getMessage(),
            ], 500);
        }
    }
}

<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ImageService;  // Asegúrate de importar el servicio
use App\Models\Producto;        // Asegúrate de importar el modelo de Producto
use App\Models\Usuario;         // Asegúrate de importar el modelo de Usuario

class ImageController extends Controller
{
    protected $imageService;

    // Inyección de dependencia del servicio
    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function store(Request $request)
    {
        // Validación de la imagen
        $request->validate([
            // 'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validación de imagen
            'image' => 'required',
            'type' => 'required|in:producto,usuario', // Tipo de imagen: producto o usuario
            'id' => 'required|integer', // ID del producto o usuario
        ]);

        try {
            // Cargar la imagen utilizando el servicio
            $url = $this->imageService->uploadImage($request->file('image'), $request->type, $request->id);

            // Retornar la URL de la imagen subida
            return response()->json(['url' => $url], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al subir la imagen.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}


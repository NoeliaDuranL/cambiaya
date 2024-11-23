<?php
namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\Producto;
use App\Models\Usuario;

class ImageService
{
    /**
     * Maneja la carga de la imagen.
     *
     * @param  UploadedFile  $image
     * @param  string  $type
     * @param  int  $id
     * @return string  URL de la imagen subida
     */
    public function uploadImage(UploadedFile $image, string $type, int $id): string
    {
        // Validación del tipo de imagen
        if ($type === 'producto') {
            $model = Producto::findOrFail($id);  // Buscar producto por ID
            $folder = 'productos/';
        } elseif ($type === 'usuario') {
            $model = Usuario::findOrFail($id);  // Buscar usuario por ID
            $folder = 'usuarios/';
        } else {
            throw new \Exception("Tipo de imagen no válido.");
        }

        // Guardar la imagen en el almacenamiento público
        $path = $image->storeAs("public/{$folder}", uniqid() . '.' . $image->getClientOriginalExtension());

        // Si es un producto, asociamos la imagen al producto
        if ($type === 'producto') {
            $model->image_path = $path;  // Guarda la ruta de la imagen en el producto
            $model->save();
        }
        // Si es un usuario, asociamos la imagen al usuario
        elseif ($type === 'usuario') {
            $model->profile_image = $path;  // Guarda la ruta de la imagen en el usuario
            $model->save();
        }

        // Retornar la URL de la imagen
        return asset('storage/' . $path);
    }
}



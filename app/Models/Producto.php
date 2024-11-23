<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'producto'; // Cambiado a plural según convenciones de Laravel
    protected $primaryKey = 'id_producto';

    protected $fillable = [
        'nombre',      // Nombre del producto
        'descripcion', // Descripción del producto
        'imagen',      // URL o path de la imagen del producto
        'estado',      // Estado del producto (activo/inactivo)
    ];

    public $timestamps = true; // Incluye las columnas created_at y updated_at

    /**
     * Relación con Post: Un producto puede tener múltiples publicaciones (posts).
     */
    public function posts()
    {
        return $this->hasMany(Post::class, 'id_producto', 'id_producto');
    }
}

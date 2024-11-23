<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $table = 'post'; // Asegúrate de que el nombre sea en plural si sigues convenciones
    protected $primaryKey = 'id_post';

    protected $fillable = [
        'id_producto',  // Relación con el producto
        'id_usuario',   // Relación con el usuario
        'estado',       // Indica si el post está activo o inactivo
    ];

    public $timestamps = true; // Mantén esto si deseas usar las columnas created_at y updated_at

    /**
     * Relación con el modelo Producto.
     */
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }

    /**
     * Relación con el modelo Usuario.
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
}

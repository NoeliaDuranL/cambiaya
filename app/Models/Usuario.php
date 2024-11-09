<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;

    protected $table = 'Usuario';
    protected $primaryKey = 'id_usuario';

    protected $fillable = [
        'id_persona', // Relación con Persona
        'usuario',
        'correo',
        'contrasena', // Contraseña en formato hash
        'estado',
    ];

    public $timestamps = true;

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id_persona');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;

    protected $table = 'Persona'; // Nombre personalizado de la tabla
    protected $primaryKey = 'id_persona'; // Clave primaria personalizada

    protected $fillable = [
        'nombre',
        'apellido',
        'estado',
    ];

    public $timestamps = true;
}

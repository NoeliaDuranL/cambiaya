<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mensaje extends Model
{
    use HasFactory;

    protected $table = 'Mensaje';
    protected $primaryKey = 'id_mensaje';

    protected $fillable = [
        'remitente',
        'destinatario',
        'mensaje',
        'leido',
        'estado',
    ];

    public $timestamps = true;

    public function remitenteUsuario()
    {
        return $this->belongsTo(Usuario::class, 'remitente', 'id_usuario');
    }

    public function destinatarioUsuario()
    {
        return $this->belongsTo(Usuario::class, 'destinatario', 'id_usuario');
    }
}

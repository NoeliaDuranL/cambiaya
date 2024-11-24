<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    use HasFactory;

    protected $table = 'notificacion';
    protected $primaryKey = 'id_notificacion';

    protected $fillable = [
        'id_usuario',
        'id_remitente',
        'id_post',
        'token',
        'mensaje',
        'leido',
        'estado',
    ];

    public $timestamps = true;

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }
    public function post()
    {
        return $this->belongsTo(Post::class, 'id_post');
    }
}

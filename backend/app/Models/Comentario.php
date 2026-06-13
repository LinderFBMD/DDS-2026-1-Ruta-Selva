<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    protected $table = 'comentario';

    protected $fillable = [
        'usuario_id',
        'establecimiento_id',
        'texto',
        'estrellas'
    ];

    public $timestamps = false;

    public function establecimiento()
    {
        return $this->belongsTo(Establecimiento::class, 'establecimiento_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
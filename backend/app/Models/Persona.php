<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table      = 'persona';
    public    $timestamps = false;

    protected $fillable = [
        'usuario_id',
        'nombre',
        'apellido',
        'dni',
        'telefono',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
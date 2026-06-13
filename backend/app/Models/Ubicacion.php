<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ubicacion extends Model
{
    protected $table      = 'ubicacion';
    public    $timestamps = false;

    protected $fillable = [
        'departamento', 'provincia', 'distrito',
        'direccion', 'referencia', 'latitud', 'longitud',
    ];
}
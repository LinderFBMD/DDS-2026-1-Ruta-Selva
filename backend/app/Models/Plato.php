<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plato extends Model
{
    protected $table      = 'plato';
    public    $timestamps = false;

    protected $fillable = [
        'establecimiento_id', 'nombre',
        'foto_url', 'descripcion', 'precio', 'disponible',
    ];

    protected $casts = [
        'disponible' => 'boolean',
    ];

    public function establecimiento()
    {
        return $this->belongsTo(Establecimiento::class, 'establecimiento_id');
    }
}
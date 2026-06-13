<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Foto extends Model
{
    protected $table      = 'foto';
    public    $timestamps = false;

    protected $fillable = [
        'establecimiento_id', 'url', 'descripcion', 'es_portada',
    ];

    protected $casts = ['es_portada' => 'boolean'];

    public function establecimiento()
    {
        return $this->belongsTo(Establecimiento::class, 'establecimiento_id');
    }
}
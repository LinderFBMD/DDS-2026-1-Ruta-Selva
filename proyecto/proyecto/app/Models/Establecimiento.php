<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Establecimiento extends Model
{
    protected $table = 'establecimiento';
    public $timestamps = false;

    protected $fillable = [
        'nombre', 'descripcion', 'activo', 'empresa_id',
        'tipo_id', 'ubicacion_id', 'tiene_internet',
        'precio_entrada', 'horario_apertura', 'horario_cierre',
    ];

    protected $casts = [
        'activo'         => 'boolean',
        'tiene_internet' => 'boolean',
        'precio_entrada' => 'decimal:2',
    ];

    public function fotos(): HasMany
    {
        return $this->hasMany(Foto::class, 'establecimiento_id');
    }

    public function portada(): HasOne
    {
        return $this->hasOne(Foto::class, 'establecimiento_id')
                    ->where('es_portada', true);
    }
}
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table      = 'empresa';
    public    $timestamps = false;

    protected $fillable = [
        'usuario_id',
        'razon_social',
        'ruc',
        'telefono',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function suscripciones()
    {
        return $this->hasMany(Suscripcion::class, 'empresa_id');
    }

    public function suscripcionActiva()
    {
        return $this->hasOne(Suscripcion::class, 'empresa_id')
                    ->where('estado', 'activa')
                    ->where('fecha_fin', '>=', now()->toDateString())
                    ->latest('created_at');
    }

        
    public function establecimientos()
    {
        return $this->hasMany(Establecimiento::class, 'empresa_id');
    }
}
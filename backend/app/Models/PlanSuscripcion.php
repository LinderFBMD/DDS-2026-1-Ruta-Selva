<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanSuscripcion extends Model
{
    protected $table      = 'plan_suscripcion';
    public    $timestamps = false;

    protected $fillable = [
        'nombre', 'descripcion', 'precio',
        'max_establecimientos', 'duracion_dias',
    ];

    public function suscripciones()
    {
        return $this->hasMany(Suscripcion::class, 'plan_id');
    }
}
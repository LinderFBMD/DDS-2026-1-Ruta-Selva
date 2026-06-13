<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Suscripcion extends Model
{
    protected $table = 'suscripcion';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'empresa_id', 'plan_id',
        'fecha_inicio', 'fecha_fin', 'estado',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function plan()
    {
        return $this->belongsTo(PlanSuscripcion::class, 'plan_id');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'suscripcion_id');
    }

    public function estaActiva(): bool
    {
        return $this->estado === 'activa'
            && $this->fecha_fin >= now()->toDateString();
    }
}
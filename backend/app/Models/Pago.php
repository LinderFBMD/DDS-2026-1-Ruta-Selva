<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'pago';

    const CREATED_AT = 'fecha';
    const UPDATED_AT = null;

    protected $fillable = [
        'suscripcion_id', 'metodo_pago',
        'monto', 'referencia', 'estado',
    ];

    public function suscripcion()
    {
        return $this->belongsTo(Suscripcion::class, 'suscripcion_id');
    }
}
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visita extends Model
{
    protected $table      = 'visita';
    public    $timestamps = false;

    protected $fillable = ['establecimiento_id', 'usuario_id', 'ip'];

    public function establecimiento()
    {
        return $this->belongsTo(Establecimiento::class, 'establecimiento_id');
    }
}
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoEstablecimiento extends Model
{
    protected $table      = 'tipo_establecimiento';
    public    $timestamps = false;

    protected $fillable = ['nombre', 'descripcion'];

    public function establecimientos()
    {
        return $this->hasMany(Establecimiento::class, 'tipo_id');
    }
}
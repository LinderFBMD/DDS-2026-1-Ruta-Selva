<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table      = 'categoria';
    public    $timestamps = false;

    protected $fillable = ['nombre'];

    public function establecimientos()
    {
        return $this->belongsToMany(
            Establecimiento::class,
            'establecimiento_categoria',
            'categoria_id',
            'establecimiento_id'
        );
    }
}
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Comentario;

class Establecimiento extends Model
{
    protected $table      = 'establecimiento';
    public    $timestamps = false;

    protected $fillable = [
        'empresa_id', 'tipo_id', 'ubicacion_id', 'nombre',
        'descripcion', 'tiene_internet', 'precio_entrada',
        'horario_apertura', 'horario_cierre', 'estado',
    ];

    protected $casts = [
        'tiene_internet' => 'boolean',
        'estado'         => 'boolean',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function tipo()
    {
        return $this->belongsTo(TipoEstablecimiento::class, 'tipo_id');
    }

    public function ubicacion()
    {
        return $this->belongsTo(Ubicacion::class, 'ubicacion_id');
    }

    public function fotos()
    {
        return $this->hasMany(Foto::class, 'establecimiento_id');
    }

    public function portada()
    {
        return $this->hasOne(Foto::class, 'establecimiento_id')
                    ->where('es_portada', true);
    }

    public function categorias()
    {
        return $this->belongsToMany(
            Categoria::class,
            'establecimiento_categoria',
            'establecimiento_id',
            'categoria_id'
        );
    }

    public function visitas()
    {
        return $this->hasMany(Visita::class, 'establecimiento_id');
    }

    public function comentarios()
    {
        return $this->hasMany(Comentario::class, 'establecimiento_id');
    }

        // Agregar dentro de la clase, después de comentarios()
    public function platos()
    {
        return $this->hasMany(Plato::class, 'establecimiento_id');
    }
}
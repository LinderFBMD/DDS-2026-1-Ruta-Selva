<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Usuario extends Authenticatable implements JWTSubject
{
    protected $table      = 'usuario';
    public    $timestamps = false;

    protected $fillable = [
        'email',
        'password',
        'tipo',
        'activo',
    ];

    protected $hidden = ['password'];

    protected $casts = ['activo' => 'boolean'];

    // JWT obligatorio
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return ['tipo' => $this->tipo];
    }

    // Relaciones
    public function persona()
    {
        return $this->hasOne(Persona::class, 'usuario_id');
    }

    public function empresa()
    {
        return $this->hasOne(Empresa::class, 'usuario_id');
    }

    public function roles()
    {
        return $this->belongsToMany(
            Rol::class,
            'usuario_rol',
            'usuario_id',
            'rol_id'
        )->withPivot('asignado_at');
    }
}
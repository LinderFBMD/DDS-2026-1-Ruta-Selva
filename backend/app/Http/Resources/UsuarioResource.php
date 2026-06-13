<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UsuarioResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $perfil = null;

        if ($this->tipo === 'persona' && $this->persona) {
            $perfil = [
                'nombre'   => $this->persona->nombre,
                'apellido' => $this->persona->apellido,
                'dni'      => $this->persona->dni,
                'telefono' => $this->persona->telefono,
            ];
        }

        if ($this->tipo === 'empresa' && $this->empresa) {
            $perfil = [
                'razon_social' => $this->empresa->razon_social,
                'ruc'          => $this->empresa->ruc,
                'telefono'     => $this->empresa->telefono,
            ];
        }

        return [
            'id'             => $this->id,
            'email'          => $this->email,
            'tipo'           => $this->tipo,
            'activo'         => $this->activo,
            'fecha_registro' => $this->fecha_registro,
            'perfil'         => $perfil,
        ];
    }
}
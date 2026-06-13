<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EstablecimientoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'nombre'           => $this->nombre,
            'descripcion'      => $this->descripcion,
            'tiene_internet'   => $this->tiene_internet,
            'horario_apertura' => $this->horario_apertura,
            'horario_cierre'   => $this->horario_cierre,
            'tipo'             => $this->tipo?->nombre,
            'categorias'       => $this->categorias->pluck('nombre'),
            'portada'          => $this->portada?->url,
            'total_visitas'    => $this->visitas_count ?? 0,
            'promedio_estrellas' => round($this->comentarios->avg('estrellas'), 1),
            'total_comentarios'  => $this->comentarios->count(),
            'ubicacion'        => $this->ubicacion ? [
                'departamento' => $this->ubicacion->departamento,
                'distrito'     => $this->ubicacion->distrito,
                'direccion'    => $this->ubicacion->direccion,
            ] : null,
        ];
    }
}
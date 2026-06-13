<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PlanSuscripcionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                   => $this->id,
            'nombre'               => $this->nombre,
            'descripcion'          => $this->descripcion,
            'precio'               => $this->precio,
            'max_establecimientos' => $this->max_establecimientos,
            'duracion_dias'        => $this->duracion_dias,
        ];
    }
}
<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SuscripcionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'           => $this->id,
            'plan'         => new PlanSuscripcionResource($this->whenLoaded('plan')),
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin'    => $this->fecha_fin,
            'estado'       => $this->estado,
            'activa'       => $this->estaActiva(),
        ];
    }
}
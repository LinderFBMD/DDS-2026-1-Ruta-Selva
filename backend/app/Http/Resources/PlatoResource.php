<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PlatoResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'nombre'      => $this->nombre,
            'descripcion' => $this->descripcion,
            'precio'      => $this->precio,
            'foto_url'    => $this->foto_url,
            'disponible'  => $this->disponible,
        ];
    }
}
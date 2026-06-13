<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FotoResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'url'         => $this->url,
            'descripcion' => $this->descripcion,
            'es_portada'  => $this->es_portada,
        ];
    }
}
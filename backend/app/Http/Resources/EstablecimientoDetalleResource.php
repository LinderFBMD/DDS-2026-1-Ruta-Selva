<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EstablecimientoDetalleResource extends JsonResource
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
            'fotos'            => $this->fotos->map(fn($f) => [
                'id'          => $f->id,
                'url'         => $f->url,
                'descripcion' => $f->descripcion,
                'es_portada'  => (bool) $f->es_portada,
            ]),
            'platos'             => $this->platos->map(fn($p) => [
                'id'          => $p->id,
                'nombre'      => $p->nombre,
                'descripcion' => $p->descripcion,
                'precio'      => $p->precio,
                'foto_url'    => $p->foto_url,
                'disponible'  => (bool) $p->disponible,
            ]),
            'promedio_estrellas' => round($this->comentarios->avg('estrellas'), 1),
            'total_comentarios'  => $this->comentarios->count(),
            'total_visitas'      => $this->visitas_count ?? 0,
            'ubicacion'          => $this->ubicacion ? [
                'departamento' => $this->ubicacion->departamento,
                'provincia'    => $this->ubicacion->provincia,
                'distrito'     => $this->ubicacion->distrito,
                'direccion'    => $this->ubicacion->direccion,
                'referencia'   => $this->ubicacion->referencia,
                'latitud'      => $this->ubicacion->latitud,
                'longitud'     => $this->ubicacion->longitud,
            ] : null,
            
            // NUEVO: Lista de comentarios estructurada mapeando estrictamente a 'persona'
            'lista_comentarios' => $this->comentarios->map(fn($c) => [
                'id'         => $c->id,
                'texto'      => $c->texto,
                'estrellas'  => (int)$c->estrellas,
                // CORREGIDO: Extrae el nombre directamente de la relación persona
                'autor'      => $c->usuario?->persona?->nombre ?? 'Usuario Anónimo'
            ]),
        ];
    }
}
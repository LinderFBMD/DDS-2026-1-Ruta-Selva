<?php
namespace App\Http\Controllers;

use App\Http\Resources\EstablecimientoResource;
use App\Http\Resources\SuscripcionResource;
use Illuminate\Http\Request;

class PanelController extends Controller
{
    // GET /api/panel/mis-establecimientos
    public function misEstablecimientos(Request $request)
    {
        $empresa = $request->user()->empresa;

        if (!$empresa) {
            return response()->json(['message' => 'No tienes perfil de empresa.'], 403);
        }

        $establecimientos = $empresa->establecimientos()
            ->with(['tipo', 'categorias', 'portada', 'ubicacion'])
            ->withCount(['visitas', 'comentarios'])
            ->get();

        return EstablecimientoResource::collection($establecimientos);
    }

    // GET /api/panel/suscripcion
    public function suscripcion(Request $request)
    {
        $empresa = $request->user()->empresa;

        if (!$empresa) {
            return response()->json(['message' => 'No tienes perfil de empresa.'], 403);
        }

        $suscripcion = $empresa->suscripcionActiva()->with('plan')->first();

        if (!$suscripcion) {
            return response()->json(['suscripcion' => null]);
        }

        return response()->json([
            'suscripcion' => new SuscripcionResource($suscripcion),
        ]);
    }
}
<?php
namespace App\Http\Controllers;

use App\Models\PlanSuscripcion;
use App\Models\Suscripcion;
use App\Http\Requests\StoreSuscripcionRequest;
use App\Http\Resources\PlanSuscripcionResource;
use App\Http\Resources\SuscripcionResource;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SuscripcionController extends Controller
{
    // GET /api/suscripciones/planes
    public function planes()
    {
        $planes = PlanSuscripcion::all();
        return PlanSuscripcionResource::collection($planes);
    }

    // POST /api/suscripciones
    public function store(StoreSuscripcionRequest $request)
    {
        $empresa = $request->user()->empresa;

        if (!$empresa) {
            return response()->json(['message' => 'No tienes perfil de empresa.'], 403);
        }

        Suscripcion::where('empresa_id', $empresa->id)
            ->where('estado', 'activa')
            ->update(['estado' => 'cancelada']);

        $plan = PlanSuscripcion::findOrFail($request->plan_id);

        $suscripcion = Suscripcion::create([
            'empresa_id'   => $empresa->id,
            'plan_id'      => $plan->id,
            'fecha_inicio' => Carbon::now()->toDateString(),
            'fecha_fin'    => Carbon::now()->addDays($plan->duracion_dias)->toDateString(),
            'estado'       => 'activa',
        ]);

        return response()->json([
            'message'     => 'Suscripción creada correctamente.',
            'suscripcion' => new SuscripcionResource($suscripcion->load('plan')),
        ], 201);
    }
}
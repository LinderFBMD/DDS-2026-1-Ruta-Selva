<?php
namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Suscripcion;
use App\Http\Requests\StorePagoRequest;
use Illuminate\Http\Request;

class PagoController extends Controller
{
    // POST /api/pagos — pago simulado
    public function store(StorePagoRequest $request)
    {
        $empresa = $request->user()->empresa;

        $suscripcion = Suscripcion::where('id', $request->suscripcion_id)
            ->where('empresa_id', $empresa->id)
            ->with('plan')
            ->firstOrFail();

        $pago = Pago::create([
            'suscripcion_id' => $suscripcion->id,
            'metodo_pago'    => $request->metodo_pago,
            'monto'          => $suscripcion->plan->precio,
            'referencia'     => $request->referencia ?? 'SIM-' . strtoupper(uniqid()),
            'estado'         => 'completado',
        ]);

        return response()->json([
            'message'    => 'Pago registrado correctamente.',
            'referencia' => $pago->referencia,
            'estado'     => $pago->estado,
        ], 201);
    }
}
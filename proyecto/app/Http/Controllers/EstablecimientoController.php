<?php
namespace App\Http\Controllers;

use App\Models\Establecimiento;

class EstablecimientoController extends Controller
{
    public function index()
    {
        $establecimientos = Establecimiento::with('portada')
            ->where('activo', true)
            ->orderBy('nombre')
            ->paginate(10);

        return view('establecimientos.index', compact('establecimientos'));
    }

    public function show(Establecimiento $establecimiento)
    {
        $establecimiento->load([
            'fotos' => fn($q) => $q->orderByDesc('es_portada')
        ]);

        return view('establecimientos.show', compact('establecimiento'));
    }
}
<?php
namespace App\Http\Controllers;

use App\Models\Establecimiento;
use App\Models\Plato;
use App\Http\Resources\PlatoResource;
use Illuminate\Http\Request;

class PlatoController extends Controller
{
    private function verificarPropietario(Request $request, $establecimientoId): Establecimiento
    {
        $empresa = $request->user()->empresa;

        return Establecimiento::where('id', $establecimientoId)
            ->where('empresa_id', $empresa->id)
            ->firstOrFail();
    }

    // GET /api/establecimientos/{id}/platos
    public function index(Request $request, $id)
    {
        $establecimiento = $this->verificarPropietario($request, $id);
        return PlatoResource::collection($establecimiento->platos);
    }

    // POST /api/establecimientos/{id}/platos
    public function store(Request $request, $id)
    {
        $request->validate([
            'nombre'      => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'precio'      => 'nullable|numeric|min:0',
            'foto'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'disponible'  => 'nullable',
        ]);

        $establecimiento = $this->verificarPropietario($request, $id);

        $foto_url = null;
        if ($request->hasFile('foto')) {
            $path     = $request->file('foto')->store('platos', 'public');
            $foto_url = asset('storage/' . $path);
        }

        $plato = $establecimiento->platos()->create([
            'nombre'      => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio'      => $request->precio,
            'foto_url'    => $foto_url,
            'disponible'  => $request->disponible == '1',
        ]);

        return new PlatoResource($plato);
    }

    // POST /api/establecimientos/{id}/platos/{platoId} con _method=PUT
    public function update(Request $request, $id, $platoId)
    {
        $request->validate([
            'nombre'      => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'precio'      => 'nullable|numeric|min:0',
            'foto'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'disponible'  => 'nullable',
        ]);

        $establecimiento = $this->verificarPropietario($request, $id);
        $plato           = $establecimiento->platos()->findOrFail($platoId);

        $foto_url = $plato->foto_url;
        if ($request->hasFile('foto')) {
            $path     = $request->file('foto')->store('platos', 'public');
            $foto_url = asset('storage/' . $path);
        }

        $plato->update([
            'nombre'      => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio'      => $request->precio,
            'foto_url'    => $foto_url,
            'disponible'  => $request->disponible == '1',
        ]);

        return new PlatoResource($plato);
    }

    // DELETE /api/establecimientos/{id}/platos/{platoId}
    public function destroy(Request $request, $id, $platoId)
    {
        $establecimiento = $this->verificarPropietario($request, $id);
        $establecimiento->platos()->findOrFail($platoId)->delete();

        return response()->json(['message' => 'Plato eliminado.']);
    }
}

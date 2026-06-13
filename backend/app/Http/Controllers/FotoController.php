<?php
namespace App\Http\Controllers;

use App\Models\Establecimiento;
use App\Models\Foto;
use App\Http\Resources\FotoResource;
use Illuminate\Http\Request;

class FotoController extends Controller
{
    private function verificarPropietario(Request $request, $establecimientoId): Establecimiento
    {
        $empresa = $request->user()->empresa;

        return Establecimiento::where('id', $establecimientoId)
            ->where('empresa_id', $empresa->id)
            ->firstOrFail();
    }

    // POST /api/establecimientos/{id}/fotos
    public function store(Request $request, $id)
    {
        $request->validate([
            'foto'        => 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
            'descripcion' => 'nullable|string|max:100',
            'es_portada'  => 'boolean',
        ]);

        $establecimiento = $this->verificarPropietario($request, $id);

        // Guardar archivo en storage/app/public/fotos
        $path = $request->file('foto')->store('fotos', 'public');
        $url  = asset('storage/' . $path);

        if ($request->boolean('es_portada')) {
            $establecimiento->fotos()->update(['es_portada' => false]);
        }

        $foto = $establecimiento->fotos()->create([
            'url'         => $url,
            'descripcion' => $request->descripcion,
            'es_portada'  => $request->boolean('es_portada', false),
        ]);

        return new FotoResource($foto);
    }

    // DELETE /api/establecimientos/{id}/fotos/{fotoId}
    public function destroy(Request $request, $id, $fotoId)
    {
        $establecimiento = $this->verificarPropietario($request, $id);
        $establecimiento->fotos()->findOrFail($fotoId)->delete();

        return response()->json(['message' => 'Foto eliminada.']);
    }

    // PATCH /api/establecimientos/{id}/fotos/{fotoId}/portada
    public function setPortada(Request $request, $id, $fotoId)
    {
        $establecimiento = $this->verificarPropietario($request, $id);
        $establecimiento->fotos()->update(['es_portada' => false]);
        $establecimiento->fotos()->findOrFail($fotoId)->update(['es_portada' => true]);

        return response()->json(['message' => 'Portada actualizada.']);
    }
}
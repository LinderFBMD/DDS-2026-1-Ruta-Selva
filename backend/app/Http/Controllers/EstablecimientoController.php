<?php

namespace App\Http\Controllers;

use App\Models\Establecimiento;
use App\Models\Visita;
use App\Models\Comentario;
use App\Http\Resources\EstablecimientoResource;
use App\Http\Resources\EstablecimientoDetalleResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ubicacion;
use App\Models\Categoria;
use App\Http\Requests\StoreEstablecimientoRequest;
use Illuminate\Support\Facades\DB;

class EstablecimientoController extends Controller
{
    // GET /api/establecimientos
    public function index(Request $request)
    {
        $query = Establecimiento::with([
            'tipo',
            'categorias',
            'portada',
            'comentarios',
            'ubicacion'
        ])
        ->withCount('visitas')
        ->where('estado', true);

        // Búsqueda por query string
        if ($request->filled('q')) {
            $query->where('nombre', 'like', '%' . $request->q . '%');
        }

        // Filtro por Categoría
        if ($request->filled('categoria')) {
            $query->whereHas('categorias', function ($q) use ($request) {
                $q->where('nombre', $request->categoria);
            });
        }

        // NUEVO: Filtro por Departamento (Tabla Ubicación relacionada)
        if ($request->filled('departamento')) {
            $query->whereHas('ubicacion', function ($q) use ($request) {
                $q->where('departamento', $request->departamento);
            });
        }

        // NUEVO: Filtro por Provincia (Tabla Ubicación relacionada)
        if ($request->filled('provincia')) {
            $query->whereHas('ubicacion', function ($q) use ($request) {
                $q->where('provincia', $request->provincia);
            });
        }

        // Orden (Se mantiene "valorados" por defecto)
        $orden = $request->get('orden', 'valorados');
        
        $query->withCount('comentarios')
              ->orderByDesc('comentarios_count');

        $establecimientos = $query->paginate(12);

        return EstablecimientoResource::collection($establecimientos);
    }

    // GET /api/filtros
    // Extrae departamentos y provincias únicos que poseen establecimientos activos
    public function obtenerFiltrosUbicacion()
    {
        $ubicaciones = DB::table('ubicaciones')
            ->join('establecimientos', 'ubicaciones.id', '=', 'establecimientos.ubicacion_id')
            ->where('establecimientos.estado', true)
            ->select('ubicaciones.departamento', 'ubicaciones.provincia')
            ->distinct()
            ->get();

        // Agrupamos el set de datos para facilitar el renderizado jerárquico en React
        $regiones = [];
        foreach ($ubicaciones as $ub) {
            if (!empty($ub->departamento)) {
                if (!isset($regiones[$ub->departamento])) {
                    $regiones[$ub->departamento] = [];
                }
                if (!empty($ub->provincia) && !in_array($ub->provincia, $regiones[$ub->departamento])) {
                    $regiones[$ub->departamento][] = $ub->provincia;
                }
            }
        }

        return response()->json([
            'regiones' => $regiones
        ]);
    }

    // GET /api/establecimientos/buscar?q=xxx
    public function buscar(Request $request)
    {
        $q = $request->get('q', '');

        $resultados = Establecimiento::where('estado', true)
            ->where('nombre', 'like', '%' . $q . '%')
            ->select('id', 'nombre')
            ->limit(6)
            ->get();

        return response()->json($resultados);
    }

    // GET /api/establecimientos/{id}
    public function show(Request $request, $id)
    {
        // CORREGIDO: Cargamos la relación anidada para que al abrir la página ya traiga los nombres de persona
        $establecimiento = Establecimiento::with([
            'tipo',
            'categorias',
            'fotos',
            'comentarios.usuario.persona', 
            'ubicacion'
        ])
        ->where('estado', true)
        ->findOrFail($id);

        Visita::create([
            'establecimiento_id' => $establecimiento->id,
            'usuario_id'         => Auth::id(),
            'ip'                 => $request->ip(),
        ]);

        return new EstablecimientoDetalleResource($establecimiento);
    }

    private function verificarLimite($empresa): bool
    {
        $suscripcion = $empresa->suscripcionActiva()->with('plan')->first();
        if (!$suscripcion) return false;

        $publicados = Establecimiento::where('empresa_id', $empresa->id)
            ->where('estado', true)
            ->count();

        return $publicados < $suscripcion->plan->max_establecimientos;
    }

    // POST /api/panel/establecimientos
    public function store(StoreEstablecimientoRequest $request)
    {
        $empresa = $request->user()->empresa;

        if (!$empresa) {
            return response()->json(['message' => 'No tienes perfil de empresa.'], 403);
        }

        if (!$this->verificarLimite($empresa)) {
            return response()->json([
                'message' => 'Has alcanzado el límite de tu plan. Actualiza tu suscripción.'
            ], 403);
        }

        DB::beginTransaction();
        try {
            $ubicacion = Ubicacion::create([
                'departamento' => $request->departamento,
                'provincia'    => $request->provincia,
                'distrito'     => $request->distrito,
                'direccion'    => $request->direccion,
                'referencia'   => $request->referencia,
                'latitud'      => $request->latitud,
                'longitud'     => $request->longitud,
            ]);

            $establecimiento = Establecimiento::create([
                'empresa_id'       => $empresa->id,
                'tipo_id'          => $request->tipo_id,
                'ubicacion_id'     => $ubicacion->id,
                'nombre'           => $request->nombre,
                'descripcion'      => $request->descripcion,
                'tiene_internet'   => $request->boolean('tiene_internet', false),
                'precio_entrada'   => $request->precio_entrada,
                'horario_apertura' => $request->horario_apertura,
                'horario_cierre'   => $request->horario_cierre,
                'estado'           => true,
            ]);

            if ($request->filled('categorias')) {
                $establecimiento->categorias()->sync($request->categorias);
            }

            DB::commit();

            return new EstablecimientoDetalleResource(
                $establecimiento->load(['tipo', 'categorias', 'ubicacion'])
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error al crear el establecimiento.'], 500);
        }
    }

    // PUT /api/panel/establecimientos/{id}
    public function update(StoreEstablecimientoRequest $request, $id)
    {
        $empresa         = $request->user()->empresa;
        $establecimiento = Establecimiento::where('id', $id)
            ->where('empresa_id', $empresa->id)
            ->firstOrFail();

        DB::beginTransaction();
        try {
            if ($establecimiento->ubicacion_id) {
                $establecimiento->ubicacion->update([
                    'departamento' => $request->departamento,
                    'provincia'    => $request->provincia,
                    'distrito'     => $request->distrito,
                    'direccion'    => $request->direccion,
                    'referencia'   => $request->referencia,
                    'latitud'      => $request->latitud,
                    'longitud'     => $request->longitud,
                ]);
            }

            $establecimiento->update([
                'tipo_id'          => $request->tipo_id,
                'nombre'           => $request->nombre,
                'descripcion'      => $request->descripcion,
                'tiene_internet'   => $request->boolean('tiene_internet', false),
                'precio_entrada'   => $request->precio_entrada,
                'horario_apertura' => $request->horario_apertura,
                'horario_cierre'   => $request->horario_cierre,
            ]);

            if ($request->filled('categorias')) {
                $establecimiento->categorias()->sync($request->categorias);
            }

            DB::commit();

            return new EstablecimientoDetalleResource(
                $establecimiento->load(['tipo', 'categorias', 'ubicacion'])
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error al actualizar.'], 500);
        }
    }

    // DELETE /api/panel/establecimientos/{id}
    public function destroy(Request $request, $id)
    {
        $empresa         = $request->user()->empresa;
        $establecimiento = Establecimiento::where('id', $id)
            ->where('empresa_id', $empresa->id)
            ->firstOrFail();

        $establecimiento->update(['estado' => false]);

        return response()->json(['message' => 'Establecimiento desactivado.']);
    }

    // POST /api/establecimientos/comentarios
    public function storeComentario(Request $request)
    {
        // Validamos directamente aquí los datos enviados por la app en React
        $request->validate([
            'establecimiento_id' => 'required|exists:establecimiento,id',
            'texto'              => 'required|string|min:3',
            'estrellas'          => 'required|integer|min:1|max:5',
        ]);

        // Guardamos el comentario
        $comentario = Comentario::create([
            'usuario_id'         => $request->user()->id, 
            'establecimiento_id' => $request->establecimiento_id,
            'texto'              => $request->texto,
            'estrellas'          => $request->estrellas,
        ]);

        // ✅ CORREGIDO: Cambiado 'comentarios.persona' por la ruta anidada correcta 'comentarios.usuario.persona'
        $establecimiento = Establecimiento::with([
            'tipo', 
            'categorias', 
            'fotos', 
            'comentarios.usuario.persona', 
            'ubicacion',
            'platos'
        ])->findOrFail($request->establecimiento_id);

        return response()->json([
            'message' => 'Comentario publicado con éxito.',
            'data'    => new EstablecimientoDetalleResource($establecimiento)
        ], 201);
    }    
}
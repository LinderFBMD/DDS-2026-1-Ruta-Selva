<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EstablecimientoController;
use App\Http\Controllers\SuscripcionController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\PlatoController;
use App\Http\Controllers\FotoController;
use App\Http\Controllers\PanelController;

// ── 1. RUTAS PÚBLICAS (Sin Token / Sin Autenticación) ─────────────────

Route::prefix('auth')->group(function () {
    Route::post('register/persona', [AuthController::class, 'registerPersona']);
    Route::post('register/empresa', [AuthController::class, 'registerEmpresa']);
    Route::post('login',            [AuthController::class, 'login']);
});

// Establecimientos públicos
Route::prefix('establecimientos')->group(function () {
    Route::get('/',       [EstablecimientoController::class, 'index']);
    Route::get('/buscar', [EstablecimientoController::class, 'buscar']);
    Route::get('/{id}',   [EstablecimientoController::class, 'show']);
});

// Endpoints públicos para poblar los Filtros Laterales (Ubicación y Categorías)
Route::get('filtros', [EstablecimientoController::class, 'obtenerFiltrosUbicacion']);
Route::get('categorias', fn() => \App\Models\Categoria::all()); // MUDADA AQUÍ: Ahora es pública

// Planes públicos
Route::get('suscripciones/planes', [SuscripcionController::class, 'planes']);


// ── 2. RUTAS PROTEGIDAS GENERALES (Requieren token JWT) ────────────────

Route::prefix('auth')->middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me',      [AuthController::class, 'me']);
});


// ── 3. RUTAS PROTEGIDAS (Requieren token JWT) ──────────────────

Route::middleware('auth:api')->group(function () { // AGREGUE SANCTUM EN VEZ DE API EN CASO DE CUALQUIER ERROR

    // NUEVO: Ruta para guardar comentarios y puntuaciones (Solo tipo 'persona' gestionado en el Request)
    Route::post('establecimientos/comentarios', [EstablecimientoController::class, 'storeComentario']);

    // Panel de Control de la Empresa
    Route::prefix('panel')->group(function () {
        Route::get('mis-establecimientos',     [PanelController::class, 'misEstablecimientos']);
        Route::get('suscripcion',              [PanelController::class, 'suscripcion']);
        Route::post('establecimientos',        [EstablecimientoController::class, 'store']);
        Route::put('establecimientos/{id}',   [EstablecimientoController::class, 'update']);
        Route::delete('establecimientos/{id}',[EstablecimientoController::class, 'destroy']);
    });

    // Suscripciones y pagos
    Route::post('suscripciones', [SuscripcionController::class, 'store']);
    Route::post('pagos',         [PagoController::class, 'store']);

    // Platos (Anidado en establecimiento)
    Route::prefix('establecimientos/{id}/platos')->group(function () {
        Route::get('/',             [PlatoController::class, 'index']);
        Route::post('/',            [PlatoController::class, 'store']);
        Route::put('/{platoId}',    [PlatoController::class, 'update']);
        Route::delete('/{platoId}', [PlatoController::class, 'destroy']);
    });

    // Fotos (Anidado en establecimiento)
    Route::prefix('establecimientos/{id}/fotos')->group(function () {
        Route::post('/',                  [FotoController::class, 'store']);
        Route::delete('/{fotoId}',        [FotoController::class, 'destroy']);
        Route::patch('/{fotoId}/portada', [FotoController::class, 'setPortada']);
    });

   
    Route::get('/establecimientos/buscar', [EstablecimientoController::class, 'buscar']);

    // Tipos para el formulario de creación (Solo empresas autenticadas)
    Route::get('tipos-establecimiento', fn() => \App\Models\TipoEstablecimiento::all());
    
});

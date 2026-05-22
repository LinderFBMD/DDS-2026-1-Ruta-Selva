
<?php
use App\Http\Controllers\EstablecimientoController;
use Illuminate\Support\Facades\Route;

Route::get('/', [EstablecimientoController::class, 'index'])->name('establecimientos.index');
Route::get('/establecimiento/{establecimiento}', [EstablecimientoController::class, 'show'])->name('establecimientos.show');
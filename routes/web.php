<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CodigoController;
use App\Http\Controllers\CuponController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    // Agrega cualquier otro comando de limpieza que necesites

    return "La cach¨¦ ha sido limpiada";
});
Route::get('/', [ClienteController::class, 'participar']);

// Route::get('/', function () {
//     return view('welcome');
// });

/* Clientes */
Route::middleware('auth')->prefix('clientes')->group(function () {
    Route::get('/', [ClienteController::class, 'index'])->name('clientes');
    Route::get('/{cliente}', [ClienteController::class, 'show'])->name('cliente.mostrar');
});
Route::post('/crear', [ClienteController::class, 'create'])->name('cliente.nuevo');


/* AdministraciÃ³n */
Route::get('/dashboard', [AdminController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/estadisticas', [AdminController::class, 'graficas'])->middleware(['auth', 'verified'])->name('graficas');
Route::post('/estadisticas/actualizar/cupones', [AdminController::class, 'actualizarPieCupones'])->middleware(['auth', 'verified'])->name('graficas.actualizar.cupones');
Route::post('/estadisticas/actualizar/usuarios', [AdminController::class, 'actualizarBarUsuarios'])->middleware(['auth', 'verified'])->name('graficas.actualizar.usuarios');
Route::post('/estadisticas/actualizar/clientes', [AdminController::class, 'actualizarBarClientes'])->middleware(['auth', 'verified'])->name('graficas.actualizar.clientes');



Route::middleware('auth')->prefix('cupones')->group(function () {
    Route::get('/', [CuponController::class, 'index'])->name('cupones.show');
    Route::post('/{id}/actualizar', [CuponController::class, 'update'])->name('cupones.actualizar');
    Route::get('/buscar', [CuponController::class, 'buscarQR'])->name('cupones.buscar');
    Route::post('/validar', [CuponController::class, 'validar'])->name('cupones.validar');
});
// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

/* Usuarios */ 
Route::middleware('auth')->prefix('usuarios')->group(function () {
    Route::get('/', [ProfileController::class, 'index'])->name('usuarios');
    Route::get('/perfil/{user?}', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/perfil', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/perfil', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

/** Codigos QR */
Route::middleware('auth')->prefix('codigos')->group(function () {
    Route::get('/', [CodigoController::class, 'index'])->name('codigosqr');
});
Route::middleware('auth')->prefix('codigosqr')->group(function () {
    
    Route::get('/crear', function () { 
        return view('codigos_qr.crear', ['generar' => false]); 
    })->name('codigosqr.nuevo');
    Route::post('/crear', [CodigoController::class, 'create'])->name('codigosqr.generar');
    Route::post('/actualizar', [CodigoController::class, 'update'])->name('codigosqr.actualizar');
    Route::get('/buscar', function () { 
        return view('codigos_qr.buscar'); 
    })->name('codigosqr.buscar');
    Route::post('/validar', [CodigoController::class, 'validar'])->name('codigosqr.validar');
    Route::delete('/eliminar', [CodigoController::class, 'delete'])->name('codigosqr.eliminar');
});

Route::get('/politica-de-privacidad', function () {
    return view('rgpd.privacidad');
})->name('rgpd.privacidad');

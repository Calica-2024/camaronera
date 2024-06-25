<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CamaroneraController;
use App\Http\Controllers\UsuarioCamaroneraController;
use App\Http\Controllers\PiscinaController;
use App\Http\Controllers\BalanceadoController;
use App\Http\Controllers\ProduccionesController;
use App\Http\Controllers\CultivoDiasController;
use App\Http\Controllers\TablaAlimentacionController;
use App\Http\Controllers\ProyectoRealController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/*
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
*/

Route::middleware('auth')->group(function () {
    //Route::get('/', function () {
    //    return view('welcome');
    //});
    Route::get('/', [DashboardController::class, 'index']);
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');    
    Route::resource('/camaroneras', CamaroneraController::class);
    Route::get('/asignarUser/{camaronera}/{user}', [UsuarioCamaroneraController::class, 'asignarCamaronera']);
    Route::get('/deleteUserCam/{user}', [UsuarioCamaroneraController::class, 'destroy']);
    Route::resource('/piscinas', PiscinaController::class);
    Route::resource('/balanceados', BalanceadoController::class);
    Route::resource('/producciones', ProduccionesController::class);
    Route::get('/producciones/camaronera/{camaronera}', [ProduccionesController::class, 'camaronera']);
    Route::get('/producciones/piscina/{piscina}', [ProduccionesController::class, 'piscina']);
    Route::get('/producciones/create/{piscina}', [ProduccionesController::class, 'create']);
    Route::post('/producciones/store/{piscina}', [ProduccionesController::class, 'store']);
    Route::resource('/cultivo', CultivoDiasController::class);
    Route::get('/cultivo/create/{produccion}', [CultivoDiasController::class, 'create']);
    Route::resource('/tabla_alimentacion', TablaAlimentacionController::class);
    Route::put('/updProyItem/{item}', [ProduccionesController::class, 'updProyItem']);
    
    Route::post('/registro/{produccion}', [ProduccionesController::class, 'crearItemReal']);
    
    Route::post('/proyectoReal/{produccion}', [ProyectoRealController::class, 'store']);
    Route::put('/proyectoReal/upd/{id}', [ProyectoRealController::class, 'update']);
    Route::get('/proyectoReal/destroy/{id}', [ProyectoRealController::class, 'destroy']);
});

require __DIR__.'/auth.php';

<?php

use App\Http\Controllers\Api\cuentaController;
use App\Http\Controllers\Api\multasController;
use App\Http\Controllers\Api\propiedadController;
use App\Http\Controllers\Api\reciboController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\socioController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Ruta para las funciones del usuario

// Devuelve a los usuarios
Route::get('/socios',[socioController::class, 'index']);

// Devuelve un solo usuario con su id
Route::get('/socios/{id}',[socioController::class,'show']);

// Modifican un recurso, el impacto es sobre la totalidad
// de los atributos de recurso
Route::put('/actualizar/socio/{id}',[socioController::class, 'update']);

// Modifican sobre uno o varios de los atributos
Route::patch('/actualizar/socio/{id}',[socioController::class, 'update_parcial']);

// Eliminar al usuario
Route::delete('/socios/{id}', [socioController::class, 'destroy']);
// Ruta para visualizar las propiedades de un socio
Route::get('/propiedades/socios', [socioController::class, 'propiedades']);


/*
  * Rutas para registrar nuevos socios y logueo de cuentas
*/

// Ingreso a login
Route:: post('/login',[cuentaController::class, 'login']);
// Registro usuarios
Route::post('/registrar-socios',[socioController::class, 'store']);


/**
 * Rutas recibos
 */

//Ruta para la visualizacion de recibos de una persona, modificar luego
Route::get('/recibos', [reciboController::class , 'index']);

//Ruta para generar un nuevo recibo
Route::post('/recibos',[reciboController::class , 'store']);

//Ruta para visualizar un recibo
Route::get('/recibos/{id}', [reciboController::class , 'show']);

//Ruta para actualizar un recibo
Route::put('/actualizar-recibo/{id}', [reciboController::class, 'update']);



/**
 * Rutas propiedades
 */

 // Ruta para registrar propiedades a un usuario
 Route::get('/registro-propiedades',[propiedadController::class , 'store']);
// Ruta para visualizar todas las propiedades

//Ruta para visualizar todas las propiedades de un socio en especifico
Route::get('/propiedades/socio/{id}', [propiedadController::class, 'show']);

/**
 * Rutas multas
 */

// Ruta para visualizar todas las multas
Route::get('/multas',[multasController::class , 'index']);

// Ruta para registrar una multa
Route::post('/multas', [multasController::class , 'store']);

// Ruta para actualizar una multa
Route::put('/multas/{id}', [multasController::class , 'update']);


// Ruta para enlazar una multa a un propietario
Route::post('/propietario/multa', [multasController::class, 'enlazarMulta']);

// Ruta para visualizar las recibos de un propietario
Route::get('/socio/recibo/{fecha_inicio}/{fecha_fin}', [socioController::class, 'socio_recibo']);

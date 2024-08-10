<?php

use App\Http\Controllers\Api\cuentaController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Ruta para las funciones del usuario

// Devuelve a los usuarios
Route::get('/socios',[socioController::class, 'index']);

// Devuelve un solo usuario con su id
Route::get('/socios/{id}',[socioController::class,'show']);

// Modifican un recurso, el impacto es sobre la totalidad
// de los atributos de recurso
Route::put('actualizar/socio/{id}',[socioController::class, 'update']);

// Modifican sobre uno o varios de los atributos
Route::patch('actualizar/socio/{id}',[socioController::class, 'update_parcial']);

// Eliminar al usuario
Route::delete('socios/{id}', [socioController::class, 'destroy']);


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




/**
 * Rutas propiedades
 */

 // Ruta para registrar propiedades a un usuario
 Route::get('/registro-propiedades',[propiedadController::class , 'store']);


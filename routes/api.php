<?php

use App\Http\Controllers\Api\cuentaController;
use App\Http\Controllers\Api\medidorController;
use App\Http\Controllers\Api\multasController;
use App\Http\Controllers\Api\propiedadController;
use App\Http\Controllers\Api\reciboController;
use App\Http\Controllers\Api\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\socioController;
use App\Http\Controllers\Api\MantenimientoController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ConsumoController;
use App\Models\Consumo;
use Illuminate\Support\Facades\Auth;

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


// Registro usuarios, considerar que estos dos metodos ya no funcionan como tal, sustiutidos por login y register

Route::post('/login/socio', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::post('/reseteo/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::post('/reseteo', [ResetPasswordController::class, 'reset'])->name('password.reset');
/*
    * Rutas protegidas por sanctum para los socios y sus respectivos controles
*/
Route::middleware('auth:sanctum')->group(function () {
    // Devuelve solo una lista de los socios.
    Route::get('/socios', [socioController::class, 'index']);
    // Devuelve un solo usuario con su id
    Route::get('/socios/{id}', [socioController::class, 'show']);
    // Ruta para cerrar sesion
    Route::post('/logout', [AuthController::class, 'logout']);
    // Modifican todos los atributos del socio, actualizacion de perfil y datos del usuario
    Route::put('/actualizar/socio/{id}', [socioController::class, 'update']);
    // Modifican sobre uno o varios de los atributos del socio
    Route::patch('/actualizar/socio/{id}', [socioController::class, 'update_parcial']);
    // Eliminacion del socio
    Route::delete('/socios/{id}', [socioController::class, 'destroy']);
    // Ruta para registrar un nuevo socio
    Route::post('/registro/socio', [AuthController::class, 'register']);
});

/* Se debe considerar este caso, tokens con capacidades*/
//Route::middleware(['auth:sanctum', 'can:ver recibos'])

/*
    * Rutas acceso recibos protegidas con sanctum
*/
Route::middleware(['auth:sanctum', 'role:administrador'])->group(function () {
    //Ruta para la visualizacion de recibos de una persona, modificar luego
    Route::get('/recibos', [reciboController::class, 'index']);
    //Ruta para generar un nuevo recibo
    Route::post('/recibos', [reciboController::class, 'store']);
    //Ruta para visualizar un recibo
    Route::get('/recibos/{id}', [reciboController::class, 'show']);
    //Ruta para actualizar un recibo
    Route::put('/actualizar-recibo/{id}', [reciboController::class, 'update']);
});

/*
    * Rutas de acceso a las propiedades
*/
Route::middleware('auth:sanctum')->group(function () {
    // Ruta para registrar propiedades a un usuario
    Route::post('/registro-propiedades', [propiedadController::class, 'store']);
    //Ruta para visualizar todas las propiedades de un socio en especifico
    Route::get('/propiedades/socio/{id}', [propiedadController::class, 'show']);
    // Ruta para visualizar las propiedades de un socio
    Route::get('/propiedades/socios', [socioController::class, 'propiedades']);
});

/**
 * Rutas multas protegidas con sanctum
 */
Route::middleware('auth:sanctum')->group(function () {
// Ruta para visualizar todas las multas
Route::get('/multas', [multasController::class, 'index']);
// Ruta para registrar una multa
Route::post('/multas', [multasController::class, 'store']);
// Ruta para actualizar una multa
Route::put('/multas/{id}', [multasController::class, 'update']);
});


/**
 * Rutas de acceso a los mantenimientos
 */
Route::middleware('auth:sanctum')->group(function () {
    // Ruta para visualizar todos los mantenimientos
    Route::get('/lista/mantenimientos', [MantenimientoController::class, 'index']);
    // Ruta para registrar un mantenimiento
    Route::post('/mantenimientos', [MantenimientoController::class, 'store']);
    // Ruta para visualizar un mantenimiento
    Route::get('/mantenimientos/{id}', [MantenimientoController::class, 'show']);
    // Ruta para actualizar un mantenimiento
    Route::put('/mantenimientos/{id}', [MantenimientoController::class, 'update']);
    // Ruta para eliminar un mantenimiento
    Route::delete('/mantenimientos/{id}', [MantenimientoController::class, 'destroy']);
});

/**
 * Otras rutas protegidas por sanctum
 */
Route::middleware('auth:sanctum')->group(function () {
    // Ruta para enlazar una multa a un propietario
    Route::post('/propietario/multa', [multasController::class, 'enlazarMulta']);
    // Ruta para visualizar las recibos de un propietario
    Route::get('/socio/deudas/pagos/{fecha_inicio}/{fecha_fin}/{id}', [socioController::class, 'socio_recibo']);
    // Ruta para la busqueda de un medidor por su numero de medidor
    Route::get('/busqueda-medidor/propiedades/{id}', [medidorController::class, 'show']);
});

Route::get('/endeudados/recibos/{id}', [ConsumoController::class, 'endeudados']);

Route::patch('/recibo/estado/pago/{id}', [reciboController::class, 'update_estado']);
Route::get('/recibo/{id}', [reciboController::class, 'show']);

/**
 * Otras rutas protegidas por sanctum
 */
Route::middleware('auth:sanctum')->group(function () {
    // Ruta para consulta de cantidad socios
    Route::get('/cantidad/socios', [socioController::class, 'cantidadSocios']);
    // Ruta para consulta de cantidad propiedades
    Route::get('/cantidad/propiedades', [propiedadController::class, 'cantidadPropiedades']);
    // Ruta para consulta de cantidad recibos pagados global
    Route::get('/cantidad/recibos/pagados', [reciboController::class, 'cantidadRecibosPagados']);
    // Ruta para consulta de cantidad recibos pendientes global
    Route::get('/cantidad/recibos/pendientes', [reciboController::class, 'cantidadRecibosPendientes']);
});

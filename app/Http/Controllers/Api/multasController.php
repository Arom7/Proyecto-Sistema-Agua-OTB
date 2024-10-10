<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Multa;
use App\Models\Propiedad;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use function PHPUnit\Framework\isEmpty;

class multasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $multas = Multa::all();

            if($multas->isEmpty()){
                return response()->json([
                    'message' => 'Multas vacias',
                    'status' => 400
                ], 404);
            }

            return response()->json([
                'message' => 'Solicitud aceptada .Recibos encontrados',
                'status' => 200,
                'multas' => $multas
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener los recibos: ' . $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            Multa::validar($request->all());

            $multa = Multa::create([
                'criterio_infraccion' => $request->criterio_infraccion,
                'descripcion_infraccion' => $request->descripcion_infraccion,
                'monto_infraccion' => $request->monto_infraccion
            ]);

            $multa->save();

            return response()->json([
                'message' => 'Multa registrada',
                'status' => '200',
                'multa' => $multa,
            ], 200);

        }catch (ValidationException $e) {
            return response()->json([
                'message' => 'Datos invalidados.',
                'errores' => $e->getMessage(),
                'status' => 422,
            ], 422);
        }catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 404,
            ], 404);
        }catch (\Exception $e) {
            return response()->json([
                'message' => 'Error interno del servidor.',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try{
            $multa = Multa::find($id);

            if(!$multa){
                throw new ModelNotFoundException('Multa no encontrada');
            }

            Multa::validar($request->all());

            $multa->criterio_infraccion = $request->criterio_infraccion;
            $multa->descripcion_infraccion = $request->descripcion_infraccion;
            $multa->monto_infraccion = $request->monto_infraccion;

            if($multa->estado_activo && $request->estado_activo == false){
                $multa->estado_activo = false;
            }else{
                $multa->estado_activo = true;
            }

            $multa->save();

            return response()->json([
                'message' => 'Datos actualizados',
                'status' => 200,
                'multa' => $multa,
            ],200);

        }catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 404,
            ], 404);
        }catch (ValidationException $e) {
            return response()->json([
                'message' => 'Datos invalidados.',
                'errores' => $e->getMessage(),
                'status' => 422,
            ], 422);
        }catch (\Exception $e) {
            return response()->json([
                'message' => 'Error interno del servidor.',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Funcion que enlaza una multa con un propietario.
     */
    public function enlazarMulta(Request $request){
        try{
            $multa = Multa::find($request->infracion_id);
            $propiedad = Propiedad::find($request->propiedad_id);

            if(!$multa){
                throw new ModelNotFoundException('Multa no encontrada');
            }

            if(!$propiedad){
                throw new ModelNotFoundException('Propiedad no encontrada');
            }

            $propiedad->multas()->attach($multa->id,[
                'fecha_multa' => Carbon::now(),
                'estado_pago' => false
            ]);

            return response()->json([
                'message' => 'Multa enlazada a la propiedad',
                'status' => 200,
                'multa' => $multa,
                'propiedad' => $propiedad
            ], 200);

        }catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 404,
            ], 404);
        }catch (\Exception $e) {
            return response()->json([
                'message' => 'Error interno del servidor.',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }
}

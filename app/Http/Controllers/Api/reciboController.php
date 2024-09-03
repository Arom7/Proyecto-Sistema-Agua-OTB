<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Propiedad;
use App\Models\Recibo;
use App\Models\Socio;
use App\Models\Medidor;
use App\Models\Consumo;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class reciboController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $recibos = Recibo::all();
            if ($recibos->isEmpty()) {
                $data = [
                    'message' => 'No se encontraron recibos',
                    'status' => 400
                ];
                return response()->json($data, 404);
            } else {
                $data = [
                    'message' => 'Solicitud aceptada .Recibos encontrados',
                    'status' => 200,
                    'recibos' => $recibos
                ];
                return response()->json($data, 200);
            }
        } catch (\Exception $e) {
            $data = [
                'message' => 'Error al obtener los recibos: ' . $e->getMessage(),
                'status' => 500
            ];
            return response()->json($data, 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        /**
         * Se realiza el llamado a la funcion de busqueda
         */
        try {
            // Formulario validacion Consumo / Recibo / Socio
            Socio::validar_socio_recibo($request->all());
            Consumo::validar($request->all());
            Recibo::validar($request->all());

            $id_socio = Socio::buscar_id_usuario($request->nombre, $request->primerApellido, $request->segundoApellido);

            if (!$id_socio) {
                throw new ModelNotFoundException('Socio no encontrado');
            }

            $propiedad = Propiedad::buscar_id_propiedad($id_socio->id, $request->cuadra);

            if (!$propiedad) {
                throw new ModelNotFoundException('Propiedad no encontrada');
            }

            $medidor = Medidor::busquedaMedidor($propiedad->id);

            if (!$medidor) {
                throw new ModelNotFoundException('Medidor no encontrado');
            }

            $medidor->medida_inicial = $medidor->ultima_medida;
            $medidor->ultima_medida = $request->lectura_actual;
            $medidor->save();

            $consumo = Consumo::create([
                'mes_correspondiente' => $request->mes_correspondiente,
                'lectura_actual' => $request->lectura_actual,
                'consumo_total' => $request->lectura_actual - $medidor->medida_inicial,
                'propiedad_id_consumo' => $propiedad->id
            ]);

            $consumo->save();

            $recibo = Recibo::create([
                'estado_pago' => false,
                'total' => Recibo::calcularTotal($consumo->consumo_total),
                'fecha_lectura' => Carbon::now(),
                'id_consumo_recibo' => $consumo->id,
                'observaciones' => $request->observaciones
            ]);

            $recibo->save();

            return response()->json([
                'message' => 'Socio encontrado',
                'status' => 200,
                'recibo' => $recibo,
                'consumo' => $consumo,
                'medidor' => $medidor
            ], 200);
            //Proceso realizado con exito
        } catch (ValidationException $e) {
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
        try{
            $recibo = Recibo::find($id);

            if(!$recibo){
                throw new ModelNotFoundException('Recibo no encontrado');
            }

            return response()->json([
                'usuario' => $recibo,
                'status' => 200
            ],200);
        }catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 404,
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try{
            $recibo = Recibo::find($id);

            if(!$recibo){
                throw new ModelNotFoundException('Recibo no encontrado');
            }

            Recibo::validar($request->all());
            Consumo::validar($request->all());

            $recibo->observaciones = $request->observaciones;

            $consumo = Consumo::buscarConsumo($recibo->id_consumo_recibo);

            if(!$consumo){
                throw new ModelNotFoundException('Error en la busqueda del consumo de la propiedad');
            }

            $consumo->lectura_actual = $request->lectura_actual ;
            $consumo->mes_correspondiente = $request->mes_correspondiente;

            $medidor = Medidor::find($consumo->propiedad_id_consumo);
            if(!$medidor){
                throw new ModelNotFoundException('Error en la busqueda medidor de la propiedad');
            }

            $medidor->ultima_medida = $request->lectura_actual;
            $consumo->consumo_total = $medidor->ultima_medida - $medidor->medida_inicial;

            if($consumo->consumo_total < 0){
                throw new \Exception("El consumo total no puede ser negativo", 400);
            }

            $recibo->total = Recibo::calcularTotal($consumo->consumo_total);

            $medidor->save();
            $consumo->save();
            $recibo->save();

            return response()->json([
                'message' => 'Datos actualizados',
                'usuario' => $recibo,
                'medidor' => $medidor,
                'consumo' => $consumo,
                'status' => 200
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
     * Remove the specified resource from storage. //se debe eliminar recibos??
     */
    public function destroy(string $id)
    {
        try{
            $recibo = Recibo::find($id);

            if(!$recibo){
                throw new ModelNotFoundException('Socio no encontrado');
            }

            $recibo -> delete();

            return response()->json([
                'message' => 'Usuario eliminado',
                'status' => 200
            ],200);
        }catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 404,
            ], 404);
        }
    }
}

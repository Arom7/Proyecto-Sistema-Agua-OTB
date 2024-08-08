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

            $ultima_medida = $medidor->ultima_medida;

            $medidor->ultima_medida = $request->lectura_actual;
            $medidor->save();

            $consumo_total = $request->lectura_actual - $ultima_medida;

            $consumo = Consumo::create([
                'mes_correspondiente' => $request->mes_correspondiente,
                'lectura_actual' => $request->lectura_actual,
                'consumo_total' => $consumo_total,
                'propiedad_id_consumo' => $propiedad->id
            ]);

            $consumo->save();

            $recibo = Recibo::create([
                'estado_pago' => false,
                'total' => Recibo::calcularTotal($consumo_total),
                'fecha_lectura' => Carbon::now(),
                'id_consumo_recibo' => $consumo->id,
                'observaciones' => $request->observaciones
            ]);

            $recibo->save();

            $data = [
                'message' => 'Socio encontrado',
                'status' => 200,
                'recibo' => $recibo,
                'consumo' => $consumo,
            ];
            return response()->json($data, 200);
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

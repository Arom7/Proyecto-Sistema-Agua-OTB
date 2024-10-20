<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Propiedad;
use App\Models\Recibo;
use App\Models\Socio;
use App\Models\Medidor;
use App\Models\Consumo;
use App\Models\Multa;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
            }

            foreach($recibos as $recibo){
                $id_consumo = $recibo->id_consumo_recibo;
                $consumo = Consumo::find($id_consumo);
                $id_propiedad = $consumo->propiedad_id_consumo;
                $recibo->codigo_propiedad = $id_propiedad;
                $propiedad = Propiedad::find($id_propiedad);
                $recibo->multa_propiedad = Multa::multasPorPropiedad($id_propiedad);
                $id_socio = $propiedad->socio_id;
                $socio = Socio::find($id_socio);
                $nombre_completo = $socio->nombre_socio . " " . $socio->primer_apellido_socio . " " . $socio->segundo_apellido_socio;
                $recibo->nombre_completo = $nombre_completo;
                $medidor = Medidor::find($id_propiedad);
                $recibo->lectura_actual = $medidor->ultima_medida;
                $recibo->lectura_anterior = $medidor->medida_inicial;
            }
            $data = [
                'message' => 'Solicitud aceptada .Recibos encontrados',
                'status' => 200,
                'recibos' => $recibos
            ];
            return response()->json($data, 200);
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
        DB::beginTransaction();
        try {
            // Formulario validacion Consumo / Recibo / Socio
            Consumo::validar($request->all());
            Recibo::validar($request->all());

            $id_socio = Socio::find($request -> id_socio);

            if (!$id_socio) {
                throw new ModelNotFoundException('Socio no encontrado');
            }

            $propiedad = Propiedad::buscar_id_propiedad($id_socio->id, $request->codigo_propiedad);

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
                'mes_correspondiente' => Carbon::now()->subMonth()->day(20),
                'lectura_actual' => $request->lectura_actual,
                'consumo_total' => $request->lectura_actual - $medidor->medida_inicial,
                'propiedad_id_consumo' => $propiedad->id
            ]);

            $consumo->save();

            $recibo = Recibo::create([
                'estado_pago' => false,
                'total' => Recibo::calcularTotal($consumo->consumo_total),
                'fecha_lectura' => Carbon::now(),
                'id_consumo_recibo' => $consumo->id_consumo,
                'observaciones' => $request->observaciones
            ]);

            $recibo->save();

            DB::commit();

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
            DB::rollBack();
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
                'preaviso' => $recibo,
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

            DB::beginTransaction();
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

            DB::commit();
            return response()->json([
                'message' => 'Datos actualizados',
                'usuario' => $recibo,
                'medidor' => $medidor,
                'consumo' => $consumo,
                'status' => 200
            ],200);

        }catch (ModelNotFoundException $e) {
            DB::rollBack();
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
            DB::rollBack();
            return response()->json([
                'message' => 'Error interno del servidor.',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }

    public function update_estado($id) {
        try {
            $recibo = Recibo::find($id);

            if (!$recibo) {
                throw new ModelNotFoundException('Recibo no encontrado');
            }

            $recibo->estado_pago = true;
            $recibo->save();

            return response()->json([
                'message' => 'Recibo actualizado, estado de pago.',
                'status' => 200
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 404,
            ], 404);
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

    public function cantidadRecibosPagados() {
        try{
            $cantidadRecibosPagados = Recibo::where('estado_pago', true)->count();

            return response()->json([
                'status' => true,
                'cantidadRecibosPagados' => $cantidadRecibosPagados
            ],200);
        }catch (\Exception $e){
            $data = [
                'message' => 'Error al obtener los recibos pagados: '.$e->getMessage(),
                'status' => 500
            ];
            return response($data, 500);
        }
    }

    public function cantidadRecibosPendientes() {
        try{
            $cantidadRecibosPagados = Recibo::where('estado_pago', false)->count();

            return response()->json([
                'status' => true,
                'cantidadRecibosPendientes' => $cantidadRecibosPagados
            ],200);
        }catch (\Exception $e){
            $data = [
                'message' => 'Error al obtener los recibos pagados: '.$e->getMessage(),
                'status' => 500
            ];
            return response($data, 500);
        }
    }
}

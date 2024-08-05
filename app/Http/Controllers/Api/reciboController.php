<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Propiedad;
use App\Models\Recibo;
use App\Models\Socio;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class reciboController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $recibos = Recibo::all();
            if ($recibos->isEmpty()){
                $data = [
                    'message' => 'No se encontraron recibos',
                    'status' => 400
                ];
                return response()->json($data,404);
            }else{
                $data = [
                    'message' => 'Solicitud aceptada .Recibos encontrados',
                    'status' => 200,
                    'recibos' => $recibos
                ];
                return response()->json($data, 200);
            }
        }catch (\Exception $e){
            $data = [
                'message' => 'Error al obtener los recibos: '.$e->getMessage(),
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

        $validacionConsumo = Validator::make($request->all(), [
            'mes_correspondiente' => ['required', 'date'],
            'lectura_actual'=> ['required', 'integer']
        ],[
            'lectura_actual.integer' => 'El campo debe ser un numero entero'
        ]);
        // formulario validacion

        $validacionRecibo =  Validator::make($request->all(),[
            'total' => ['required', 'regex:/^\d+(\.\d{1,2})?$/'],
            'fecha_lectura' => ['required' , 'date'],
            'observaciones' => ['regex:/^[a-zA-Z0-9]+$/']
        ],[
            'total.regex'=> 'El total debe ser un numero decimal',
            'observaciones.regex' => 'Solo puedes ingresar letras y numeros.'
        ]);

        if($validacionConsumo->fails() || $validacionRecibo->fails()){
            $data = [
                'message' => 'Error en la validacion de datos',
                'status' => 400,
                'errorConsumo' => $validacionConsumo -> errors(),
                'errorRecibo' => $validacionRecibo->errors(),
            ];
            return response()->json($data,400);
        }else{
            $id_socio = Socio::buscar_id_usuario($request->nombre,$request->primerApellido,$request->segundoApellido);

            $propiedad = Propiedad::buscar_id_propiedad($id_socio, $request->cuadra);

            $data = [
                'message' => 'Socio encontrado',
                'status' => 200,
                'propiedades' => $propiedad,
            ];
            return response()->json($data,200);
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

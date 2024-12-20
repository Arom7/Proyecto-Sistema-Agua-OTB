<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Propiedad;
use App\Models\Medidor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class propiedadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Propiedad::validar($request->all());
        DB::beginTransaction();
        try {
            if(Propiedad::find($request->id)){
                $data = [
                    'message' => 'La propiedad ya se encuentra registrada',
                    'status' => false
                ];
                return response()->json($data, 200);
            }

            $propiedad = Propiedad::create([
                'id' => $request->id,
                'socio_id' => $request->socio_id,
                'direccion_propiedad' => $request->direccion_propiedad,
                'total_multas_propiedad' => $request->total_multas_propiedad,
                'descripcion_propiedad' => $request->descripcion_propiedad
            ]);

            Medidor::validar($request->all());

            // Verificacion si se ingreso una lectura (medidor usado)
            $lectura = 0;
            $medidor_nuevo = true;
            if($request -> lectura != 0 || $request -> lectura != null){
                $lectura = $request -> lectura;
                $medidor_nuevo = false;
            }

            $medidor = Medidor::create([
                'propiedad_id_medidor' => $propiedad->id,
                'id_medidor' => $request->id_medidor,
                'medida_inicial' => $lectura,
                'ultima_medida' => $lectura,
                'medidor_nuevo' => $medidor_nuevo,
            ]);

            DB::commit();

            $data = [
                'message' => 'Propiedad registrada correctamente',
                'status' => 200,
                'propiedad' => $propiedad,
                'medidor' => $medidor
            ];
            return response()->json($data, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            $data = [
                'message' => 'Error al registrar la propiedad: ' . $e->getMessage(),
                'status' => 500
            ];
            return response()->json($data, 500);
        }catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Datos invalidados.',
                'errores' => $e->getMessage(),
                'status' => 422,
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $lista_propiedades = Propiedad::buscar_id_propiedad_unica($id);
        try{
            if ($lista_propiedades->isEmpty()) {
                $data = [
                    'message' => 'No se tiene propiedades registradas de este socio',
                    'status' => false
                ];
                return response()->json($data, 200);
            }

            $data = [
                'message' => 'Solicitud aceptada .Propiedades encontrados',
                'status' => 200,
                'propiedades' => $lista_propiedades
            ];
            return response()->json($data, 200);
        }catch (\Exception $e) {
            $data = [
                'message' => 'Error al obtener los recibos: ' . $e->getMessage(),
                'status' => 500
            ];
            return response()->json($data, 500);
        }
    }

    public function cantidadPropiedades(){
        try{
            $cantidadPropiedades = Propiedad::all()->count();

            return response()->json([
                'status' => true,
                'cantidadPropiedades' => $cantidadPropiedades
            ],200);
        }catch (\Exception $e){
            $data = [
                'message' => 'Error al obtener las propiedades: '.$e->getMessage(),
                'status' => 500
            ];
            return response($data, 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function updatePropietario(Request $request, string $id)
    {
        try{
            $request -> validate([
                'nuevo_propietario_id' => 'required|exists:socios,id'
            ]);

            $propiedad = Propiedad::findOrFail($id);

            $propiedad->socio_id = $request->nuevo_propietario_id;

            $propiedad->save();

            return response()->json([
                'message' => 'Propietario actualizado correctamente',
                'propiedad' => $propiedad,
                'status' => true
            ]);

        }catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Propiedad no encontrada',
                'status' => false
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Ocurrió un error al actualizar el propietario',
                'details' => $e->getMessage(),
                'status' => false
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
}

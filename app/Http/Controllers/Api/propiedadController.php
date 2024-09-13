<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Propiedad;
use Illuminate\Http\Request;

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
     * Lista de propiedades
     */
    public function propiedadeSocio(Request $request){

    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
                    'status' => 400
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

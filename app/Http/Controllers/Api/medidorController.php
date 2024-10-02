<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Medidor;

class medidorController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
            $medidor = Medidor::busquedaMedidorPorId($id);
            if($medidor){
                return response([
                    'message' => 'Medidor encontrado',
                    'status' => 200,
                    'medidor' => $medidor
                ], 200);
            }else{
                return response([
                    'message' => 'Medidor no encontrado',
                    'status' => 404
                ], 404);
            }
        }catch (\Exception $e){
            return response([
                'message' => 'Error al obtener los medidores: '.$e->getMessage(),
                'status' => 500
            ], 500);
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

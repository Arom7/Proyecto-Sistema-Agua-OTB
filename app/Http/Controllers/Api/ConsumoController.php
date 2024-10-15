<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Consumo;
use Illuminate\Http\Request;

class ConsumoController extends Controller
{
    /**
     * Generamos un nuevo consumo para registrarlo.
     */
    public function store(Request $request){

    }

    public function endeudados($id){
        $consumos = Consumo::busquedaConsumoPropiedad($id);

        if ($consumos->isEmpty()) {
            return response()->json([
                'message' => 'No se encontraron consumos',
                'status' => false
            ], 404);
        }
        return response()->json([
            'message' => 'Solicitud aceptada. Recibos endeudados encontrados',
            'status' => true,
            'consumos' => $consumos
        ], 200);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class cuentaController extends Controller
{
    //Funcion de verificacion
    public function login(Request $request){
        info('Datos recibidos de la solicitud', $request->all());

        Usuario::validar($request->all());
        if(Usuario::cuentaExistente($request->username)){
            $cuenta = Usuario::find($request->username);
            if (Hash::check($request->contrasenia,$cuenta->contrasenia)){
                return response()->json([
                    'message' => 'Ingreso valido.',
                    'status' => 200,
                ],200);
            }else{
                return response()->json([
                    'message' => 'Contrasenia incorrecta.',
                    'status' => 400,
                ],400);
            }
        }else{
            return response()->json([
                'message' => 'Usuario no encontrado. Registrese por favor.',
                'status' => 404,
            ],404);
        }
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;

use Illuminate\Http\Request;
// Libreria para realizar la validacion
use Illuminate\Support\Facades\Validator;
// Libreria para encriptar contasenias
use Illuminate\Support\Facades\Hash;

class cuentaController extends Controller
{
    //Funcion de verificacion
    public function login(Request $request){

        Usuario::validar($request->all());

        $username = $request->username;
        //Esto puede ser almacenado en el modelo, considerar este cambio
        $verificar_cuenta = Usuario::cuentaExistente($username);
        //verificamos si la cuenta existe
        if($verificar_cuenta){
            $cuenta = Usuario::find($username);
            //Verificamos si la cadena sin cifrar coincide con su hash cifrado correspondiente almacenado en la base de datos
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

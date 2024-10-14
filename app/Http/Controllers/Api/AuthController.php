<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use App\Models\Socio;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $user = Usuario::where('username', $request->username)->first();
        if ($user && Hash::check($request->contrasenia, $user->contrasenia)) {
            $token = $user->createToken('api-token')->plainTextToken;
            return response()->json([
                'token' => $token,
                'message' => 'Usuario logueado satisfactoriamente',
                'status' => true
            ], 200);
        }
        return response()->json([
            'status' => false,
            'error' => 'Unauthorized'
        ], 401);
    }

    public function register(Request $request)
    {
        Log::info('Ingreso al metodo');

        Socio::validar($request->all());
        Usuario::validar($request->all());

        DB::beginTransaction();
        try {
            $esta_registrado = Socio::usuarioExistente($request->ci_socio);

            if ($esta_registrado) {
                return response()->json(['error' => 'El socio ya se encuentra registrado.'], 400);
            }

            $socio = Socio::create([
                'nombre_socio' => $request->nombre_socio,
                'primer_apellido_socio' => $request->primer_apellido_socio,
                'segundo_apellido_socio' => $request->segundo_apellido_socio,
                'ci_socio' => $request->ci_socio,
                'otb_id' => 1
            ]);

            Log::info('socio registrado : ', ['socio' => $socio]);

            $usuario = Usuario::create([
                'username' => Usuario::generarUsername($socio),
                'email' => $request->email,
                'contrasenia' => Hash::make($request->contrasenia),
                'socio_id_usuario' => $socio->id
            ]);

            Log::info('usuario registro : ', ['usuario' => $usuario]);

            $token = $usuario->createToken('api-token')->plainTextToken;

            DB::commit();
            return response()->json([
                'status' => true,
                'token' => $token,
                'usuario' => $usuario
            ], 200);
        } catch (\Throwable $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()], 500);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Datos invalidados.',
                'errores' => $e->getMessage(),
                'status' => false,
            ], 422);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Token eliminado',
            'status' => true
        ], 200);
    }
}

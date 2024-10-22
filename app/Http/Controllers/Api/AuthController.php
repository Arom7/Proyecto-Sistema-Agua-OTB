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
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'contrasenia' => 'required|string'
        ]);

        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt([$loginType => $request->login, 'password' => $request->contrasenia])) {
            $user = Auth::user();
            $roles = $user->getRoleNames();

            return response()->json([
                'token' => $user->createToken('api-token')->plainTextToken,
                'roles' => $roles,
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
        Socio::validar($request->all());
        Usuario::validar($request->all());

        DB::beginTransaction();
        try {
            if (Socio::where('ci_socio', $request->ci_socio)->exists()) {
                return response()->json(['error' => 'El socio ya se encuentra registrado.'], 400);
            }

            $socio = Socio::create([
                'nombre_socio' => $request->nombre_socio,
                'primer_apellido_socio' => $request->primer_apellido_socio,
                'segundo_apellido_socio' => $request->segundo_apellido_socio,
                'ci_socio' => $request->ci_socio,
                'otb_id' => 1
            ]);

            $usuario = Usuario::create([
                'username' => Usuario::generarUsername($socio),
                'email' => $request->email,
                'contrasenia' => Hash::make($request->contrasenia),
                'socio_id_usuario' => $socio->id
            ]);

            DB::commit();
            return response()->json([
                'status' => true,
                'usuario' => $usuario
            ], 200);
        } catch (\Throwable $e) {
            DB::rollback();
            return response()->json([
                'errores' => $e->getMessage(),
                'message' => 'Error al registrar el usuario.',
                'status' => false
            ], 500);
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

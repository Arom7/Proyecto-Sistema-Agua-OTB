<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            Usuario::validarIngreso($request->all());
            if (Usuario::cuentaExistente($request->username)) {
                $cuenta = Usuario::find($request->username);
                if (Hash::check($request->contrasenia, $cuenta->contrasenia)) {
                    return response()->json([
                        'message' => 'Ingreso valido.',
                        'status' => 200,
                    ], 200);
                } else {
                    return response()->json([
                        'message' => 'Contrasenia incorrecta.',
                        'status' => 401,
                    ], 200);
                }
            } else {
                throw new ModelNotFoundException('Usuario no encontrado. Registrese por favor.');
            }
        } catch (ModelNotFoundException $e) {
                return response()->json([
                    'message' => $e->getMessage(),
                    'status' => 404,
                ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener los usuarios: ' . $e->getMessage(),
                'status' => 500
            ], 500);
        } catch (ValidationException $e) {
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

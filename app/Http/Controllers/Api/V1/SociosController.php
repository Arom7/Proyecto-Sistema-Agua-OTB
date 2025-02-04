<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\SocioRequest;
use App\Models\Socio;
use App\Services\SocioService;
use App\Services\UserService;
use App\Notifications\EnvioCuenta;
use Illuminate\Http\Request;

class SociosController extends Controller
{

    protected $socioService;
    protected $userService;

    public function __construct(SocioService $socioService, UserService $userService)
    {
        $this->socioService = $socioService;
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            return $this->socioService->getAllSocios();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'No se encontraron socios.'
            ], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SocioRequest $request)
    {
        $validatedData = $request->validated();
        try{
            $usuario = $this->socioService->createSocio($validatedData, $request->file('image'));
            if(!$usuario){
                throw new \Exception('Error al crear el usuario.');
            }
            $data = [
                'username' => $usuario['username'],
                'password' => $usuario['password']
            ];
            $user = $this->userService->find($usuario['username']);
            if(!$user){
                throw new \Exception('Error al generar un usuario.');
            }
            $user->notify(new EnvioCuenta($data));

            return response()->json([
                'message' => 'Socio creado correctamente.',
                'user' => $usuario['username'],
                'password'=> $usuario['password']
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
            return $this->socioService->getSocio($id);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Socio no encontrado.'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SocioRequest $request, string $id)
    {
        try{
            $validatedData = $request->validated();
            $socio_updated = $this->socioService->updateSocio($validatedData, $id, $request->file('image'));

            if(!$socio_updated){
                throw new \Exception('Socio no encontrado.');
            }else {
                return response()->json([
                    'message' => 'Socio actualizado correctamente.'
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar el socio.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update_partial(SocioRequest $request, string $id)
    {
        try{
            $socio = Socio::find($id);
            if(!$socio){
                return response()->json([
                    'message' => 'Socio no encontrado.'
                ], 404);
            }
            $socio->update($request->all());
            return response()->json([
                'message' => 'Socio actualizado correctamente.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar el socio.'
            ], 500);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            return $this->socioService->deleteSocio($id);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar el socio.'
            ], 500);
        }
    }
}

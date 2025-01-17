<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\SocioRequest;
use App\Models\Socio;
use App\Services\SocioService;
use Illuminate\Http\Request;

class SociosController extends Controller
{

    protected $socioService;

    public function __construct(SocioService $socioService)
    {
        $this->socioService = $socioService;
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
            return $this->socioService->createSocio($validatedData, $request->file('image'));
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear el socio.'
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
    public function update(Request $request, string $id)
    {
        return response()->json([
            'message' => 'Socio actualizado correctamente.'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        response()->json([
            'message' => 'Socio eliminado correctamente'
        ], 404);
    }
}

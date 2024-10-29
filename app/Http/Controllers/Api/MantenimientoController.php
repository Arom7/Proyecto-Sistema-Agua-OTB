<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use App\Models\Mantenimiento;
use Barryvdh\DomPDF\Facade\PDF;
use App\Models\Socio;
use Illuminate\Support\Facades\Log;

class MantenimientoController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mantenimientos = Mantenimiento::all();

        if ($mantenimientos ->isEmpty()) {
            return response()->json([
                'message' => 'La lista de mantenimientos esta vacia',
                'status' => false
            ], 404);
        }

        return response()->json([
            'message' => 'Lista de mantenimiento',
            'status' => true,
            'mantenimientos' => $mantenimientos
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            Mantenimiento::validar($request->all());

            $mantenimiento = Mantenimiento::create([
                'fecha_mantenimiento_inicio' => $request->fecha_mantenimiento_inicio,
                'fecha_mantenimiento_fin' => $request->fecha_mantenimiento_fin,
                'descripcion_mantenimiento' => $request->descripcion_mantenimiento,
                'responsable' => $request->responsable,
                'precio_total' => $request->precio_total,
                'tipo_equipo' => $request->tipo_equipo,
                'fecha_proximo_mantenimiento' => $request->fecha_proximo_mantenimiento,
                'otb_id' => 1
            ]);

            return response()->json([
                'message' => 'Mantenimiento guardado correctamente',
                'status' => true,
                'mantenimiento' => $mantenimiento
            ], 201);
        }catch(\Exception $e){
            return response()->json([
                'message' => 'Error al guardar el mantenimiento: ' . $e->getMessage(),
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
            $mantenimiento = Mantenimiento::find($id);
            if(!$mantenimiento){
                throw new ModelNotFoundException('Socio no encontrado');
            }
            return response()->json([
                'usuario' => $mantenimiento,
                'status' => true
            ],200);
        }catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => false,
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try{
            $mantenimiento = Mantenimiento::find($id);

            if(!$mantenimiento){
                throw new ModelNotFoundException('No se encuentro este mantenimiento');
           }

            Mantenimiento::validar($request->all());

            $mantenimiento->update([
                'fecha_mantenimiento_inicio' => $request->fecha_mantenimiento_inicio,
                'fecha_mantenimiento_fin' => $request->fecha_mantenimiento_fin,
                'descripcion_mantenimiento' => $request->descripcion_mantenimiento,
                'responsable' => $request->responsable,
                'precio_total' => $request->precio_total,
                'tipo_equipo' => $request->tipo_equipo,
                'fecha_proximo_mantenimiento' => $request->fecha_proximo_mantenimiento,
                'otb_id' => 1
            ]);

            $mantenimiento->save();

            return response()->json([
                'message' => 'Datos del mantenimiento actualizados correctamente',
                'status' => true,
                'mantenimiento' => $mantenimiento
            ], 200);
        }catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => false,
            ], 404);
        }catch (ValidationException $e) {
            return response()->json([
                'message' => 'Datos invalidados.',
                'errores' => $e->getMessage(),
                'status' => false,
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function generarReporteMantenimientoPDF(){
        $mantenimientos = Mantenimiento::all();
        Log::info('Generando reporte de mantenimientos en PDF'. $mantenimientos);
        $pdf = PDF::loadView('email.pdf_mantenimiento', ['mantenimientos' => $mantenimientos]);
        $pdfOutput = $pdf->output();
        return response($pdfOutput)->header('Content-Type', 'application/pdf');
    }
}

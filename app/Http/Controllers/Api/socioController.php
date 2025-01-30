<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Consumo;
use App\Models\Usuario;
use App\Models\Recibo;
use Illuminate\Http\Request;
use App\Models\Socio;
use Barryvdh\DomPDF\Facade\PDF;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class socioController extends Controller
{
    public function index(){
        try{
            $socios = Socio::all();
            if($socios->isEmpty()){

                return response()->json([
                    'message' => 'Lista vacia, usuarios no encontrados',
                    'status' => 400,
                ],404);
            }else{
                return response()->json([
                    'message' => 'Usuarios encontrados',
                    'status' => 200,
                    'lista_socios' => $socios
                ],200);
            }
        }catch (\Exception $e){
            $data = [
                'message' => 'Error al obtener los usuarios: '.$e->getMessage(),
                'status' => 500
            ];
            return response($data, 500);
        }
    }

    public function store (Request $request)
    {
        DB::beginTransaction();
        try {
            Socio::validar($request->all());
            Usuario::validar($request->all());

            $esta_registrado = Socio::usuarioExistente($request->ci_socio);

            if(!$esta_registrado){
                $socio = Socio::create([
                    'nombre_socio' => $request->nombre_socio,
                    'primer_apellido_socio' => $request->primer_apellido_socio,
                    'segundo_apellido_socio' => $request->segundo_apellido_socio,
                    'ci_socio' => $request->ci_socio,
                    'otb_id' => 1
                ]);
            }else{
                throw new Exception('El socio ya se encuentra registrado');
            }

            $username = Usuario::generarUsername($socio);

            if(!Usuario::cuentaExistente($username)){

                $id_usuario = Socio::buscar_id_usuario($request->ci_socio);

                if(!$id_usuario){
                    throw new ModelNotFoundException('Socio no encontrado, registro no terminado');
                }

                $cuenta = Usuario::create([
                    'username' => $username,
                    'contrasenia' => Hash::make($request->contrasenia),
                    'email' => $request->email,
                    'socio_id_usuario' => $id_usuario->id,
                ]);
                DB::commit();
                return response()->json([
                    'message' => 'Cuenta creada exitosamente.',
                    'status' => 201,
                    'usuario' => $cuenta
                ], 201);
            }else{
                throw new Exception('El usuario ya se encuentra registrado');
            }

        }catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 404,
            ], 404);
        }catch (ValidationException $e) {
            return response()->json([
                'message' => 'Datos invalidados.',
                'errores' => $e->getMessage(),
                'status' => 422,
            ], 422);
        }catch(\Illuminate\Database\QueryException $e){
            return response()->json([
                'message' => 'Error en la consulta de la base de datos: ' . $e->getMessage(),
                'status' => 500,
            ], 500);
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al crear el usuario: ' . $e->getMessage(),
                'status' => 500,
            ], 500);
        }
    }

    public function show($id){
        try{
            $usuario = Socio::find($id);

            if(!$usuario){
                throw new ModelNotFoundException('Socio no encontrado');
            }

            return response()->json([
                'usuario' => $usuario,
                'status' => 200
            ],200);
        }catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 404,
            ], 404);
        }
    }

    public function destroy($id){
        try{
            $usuario = Socio::find($id);

            if(!$usuario){
                throw new ModelNotFoundException('Socio no encontrado');
            }

            $usuario -> delete();

            return response()->json([
                'message' => 'Usuario eliminado',
                'status' => 200
            ],200);
        }catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 404,
            ], 404);
        }
    }

    public function update (Request $request,$id)
    {
        try{
            $socio = Socio::find($id);

            if(!$socio){
                throw new ModelNotFoundException('Socio no encontrado');
            }

            Socio::validar_socio_recibo($request->all());

            $socio->nombre_socio = $request->nombre;
            $socio->primer_apellido_socio = $request->primerApellido;
            $socio->segundo_apellido_socio = $request->segundoApellido;

            $socio->save();

            return response()->json([
                'message' => 'Datos actualizados',
                'usuario' => $socio,
                'status' => 200
            ],200);

        }catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 404,
            ], 404);
        }catch (ValidationException $e) {
            return response()->json([
                'message' => 'Datos invalidados.',
                'errores' => $e->getMessage(),
                'status' => 422,
            ], 422);
        }
    }

    public function update_parcial(Request $request, $id){

        try{
            $usuario = Socio::find($id);

            if(!$usuario){
                throw new ModelNotFoundException('Usuario no encontrado');
            }

            Socio::validar_socio($request->all());

            if($request->has('nombre')){
                $usuario->nombre = $request->nombre;
            }
            if($request->has('primerApellido')){
                $usuario->primerApellido = $request->primerApellido;
            }
            if($request->has('segundoApellido')){
                $usuario->segundoApellido = $request->segundoApellido;
            }

            $usuario->save();

            return response()->json([
                'message' => 'Estudiante actualizado',
                'usuario' => $usuario,
                'status' => 200
            ],200);
        }catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 404,
            ], 404);
        }catch (ValidationException $e) {
            return response()->json([
                'message' => 'Datos invalidados.',
                'errores' => $e->getMessage(),
                'status' => 422,
            ], 422);
        }
    }

    public function propiedades(){
        try{
            $sociosPropiedades = Socio::with('propiedades', 'usuarios')->get();

            if($sociosPropiedades->isEmpty()){
                return response()->json([
                    'message' => 'Lista vacia, socios y propiedades no encontradas',
                    'status' => 400,
                ],404);
            }else{
                return response()->json([
                    'message' => 'Socios con sus respectivas propiedades encontradas',
                    'status' => 200,
                    'socios' => $sociosPropiedades
                ],200);
            }
        }catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 404,
            ], 404);
        }
    }

    public function socio_recibo($fecha_inicio , $fecha_fin, $pagoDeuda,$generaPDF){
        try{
            $sociosRecibos = Socio::with('propiedades')->get();

            foreach ($sociosRecibos as $socio) {
                foreach ($socio->propiedades as $propiedad) {
                    $recibos = collect();
                    $consumos = Consumo::where('propiedad_id_consumo',$propiedad->id)->get();
                    foreach ($consumos as $consumo) {
                        if($pagoDeuda != 1){
                            Log::info('Visualizar consumo' . $consumo);
                            $recibosConsumo = Recibo::buscarRecibosFecha($consumo,$fecha_inicio,$fecha_fin)->where('estado_pago',false);
                            foreach ($recibosConsumo as $recibo) {
                                $recibo->mes = $consumo->mes_correspondiente;
                                $recibo->consumo = $consumo->consumo_total;
                            }
                            Log::info('Recibos:' . $recibosConsumo);
                        }else{
                            $recibosConsumo = Recibo::buscarRecibosFecha($consumo,$fecha_inicio,$fecha_fin)->where('estado_pago',true);
                            foreach ($recibosConsumo as $recibo) {
                                $recibo->mes = $consumo->mes_correspondiente;
                                $recibo->consumo = $consumo->consumo_total;
                            }
                        }
                        if(!$recibosConsumo->isEmpty()){
                            $recibos = $recibos->concat($recibosConsumo);
                        }
                    }
                    $propiedad->setAttribute('recibos', $recibos);
                }
            }

            if($sociosRecibos->isEmpty()){
                return response()->json([
                    'message' => 'Lista vacia, socios y recibos no encontrados',
                    'status' => 400,
                ],404);
            }else{
                Log::info('valor de generapdf: ' . $generaPDF);

                $datosTitle = [
                    'fecha_inicio' => $fecha_inicio,
                    'fecha_fin' => $fecha_fin,
                    'tipo_reporte'=> $pagoDeuda
                ];


                if($generaPDF == 1){
                    $pdf = PDF::loadView('email.pdf_reporte', ['socios' => $sociosRecibos, 'datosTitle' => $datosTitle]);
                    return $pdf->download('reporte.pdf');
                }
                return response()->json([
                    'message' => 'Socios con sus respectivos recibos encontrados',
                    'status' => 200,
                    'socios' => $sociosRecibos
                ],200);
            }
        }catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 404,
            ], 404);
        }
    }

    public function cantidadSocios(){
        try{
            $cantidadSocios = Socio::all()->count();

            return response()->json([
                'status' => true,
                'cantidadSocios' => $cantidadSocios
            ],200);
        }catch (\Exception $e){
            $data = [
                'message' => 'Error al obtener los socios: '.$e->getMessage(),
                'status' => 500
            ];
            return response($data, 500);
        }
    }

    public function generarPDF()
    {
        $data = [
            'nombre' => 'Alan Giovanni',
            'usuario' => '1355063',
            'contrasenia' => 'AlanGiovanni26',
        ];

        $pdf = Pdf::loadView('pdf.usuario', $data);
        return $pdf->stream('Cuenta_Usuario.pdf'); // Muestra en el navegador
        // return $pdf->download('Cuenta_Usuario.pdf'); // Para descargar
    }
}

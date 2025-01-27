<?php

namespace App\Repositories\Eloquent;

use App\Models\Socio;
use App\Repositories\Interfaces\SocioRepositoryInterface;
use Illuminate\Support\Facades\DB;

class SocioRepository implements SocioRepositoryInterface {
    // Lista de todos los socios con Eloquent
    public function getAll()
    {
        return Socio::all();
    }

    // Busqueda de socio especifico
    public function find(string $id)
    {
        return Socio::find($id);
    }

    // Registro de un nuevo socio
    public function create(array $data)
    {
        DB::beginTransaction();
        $socio = Socio::create([
            'nombre_socio' => $data['nombre'],
            'primer_apellido_socio' => $data['primer_apellido'],
            'segundo_apellido_socio' => $data['segundo_apellido'],
            'ci_socio' => $data['ci'],
            'image' => $data['image'],
            'otb_id' => 1
        ]);

        if($socio){
            DB::commit();
            return $socio;
        }
        DB::rollBack();
        return null;
    }

    public function update(array $data, string $id) {

        $socio = Socio::find(1);
        if(!$socio){
            return null;
        }
        $socio->update([
            'nombre_socio' => $data['nombre'],
            'primer_apellido_socio' => $data['primer_apellido'],
            'segundo_apellido_socio' => $data['segundo_apellido'],
            'ci_socio' => $data['ci'],
            'image' => $data['image'],
            'otb_id' => 1
        ]);
        return $socio;
    }

    public function delete(string $id) {
        $socio = Socio::find($id);
        if(!$socio){
            return 'Socio no encontrado.';
        }
        $socio->delete();
        return 'Socio eliminado correctamente.';
    }
}

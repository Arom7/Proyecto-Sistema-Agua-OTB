<?php

namespace App\Repositories\Eloquent;

use App\Models\Socio;
use App\Repositories\Interfaces\SocioRepositoryInterface;

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
        return Socio::create($data);
    }

    public function update(array $data, string $id) {
        $socio = Socio::find($id);
        $socio->update($data);
        return $socio;
    }

    public function delete(string $id) {
        $socio = Socio::find($id);
        $socio->delete();
        return $socio;
    }
}

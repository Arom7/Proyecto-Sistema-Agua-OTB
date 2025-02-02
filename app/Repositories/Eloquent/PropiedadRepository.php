<?php

namespace App\Repositories\Eloquent;

use App\Models\Propiedad;
use App\Repositories\Interfaces\PropiedadInterfaceRepository;
use Iluminate\Support\Facades\DB;

class PropiedadRepository implements PropiedadInterfaceRepository{

        public function getAll()
        {
            return Propiedad::with(['socio' => function($query){
                $query->select('id', 'nombre_socio', 'primer_apellido_socio', 'segundo_apellido_socio');
            }])
            ->get();
        }

        public function find(string $id)
        {
            return Propiedad::with(['socio' => function($query){
                $query->select('id', 'nombre_socio', 'primer_apellido_socio', 'segundo_apellido_socio');
            }])
            ->find($id);
        }

        public function create(array $data)
        {
            return 0;
        }

        public function update(array $data, string $id)
        {
            return 0;
        }

        public function delete(string $id)
        {
            return 0;
        }
}


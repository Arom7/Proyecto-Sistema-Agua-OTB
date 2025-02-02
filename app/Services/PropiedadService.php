<?php

namespace App\Services;

use App\Http\Resources\PropiedadCollection;
use App\Http\Resources\PropiedadResource;
use App\Repositories\Interfaces\PropiedadInterfaceRepository;

class PropiedadService{

    protected $propiedadRepository;

    public function __construct(PropiedadInterfaceRepository $propiedadRepository)
    {
        $this->propiedadRepository = $propiedadRepository;
    }

    public function getAll(){
        $lista_propiedades = $this->propiedadRepository->getAll();
        if($lista_propiedades->isEmpty()){
            return response()->json([
                'message' => 'No se encontraron propiedades'
            ], 404);
        }
        return new PropiedadCollection($lista_propiedades);
    }

    public function getPropiedad(string $id){
        $propiedad = $this->propiedadRepository->find($id);
        if(!$propiedad){
            return response()->json([
                'message' => 'Propiedad no encontrada.'
            ], 404);
        }
        return new PropiedadResource($propiedad);
    }

    public function find(string $id){
        return $this->propiedadRepository->find($id);
    }

    public function create(array $data){
        return $this->propiedadRepository->create($data);
    }

    public function update(array $data, string $id){
        return $this->propiedadRepository->update($data, $id);
    }
}

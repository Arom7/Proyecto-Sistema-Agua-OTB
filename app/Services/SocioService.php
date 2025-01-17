<?php

namespace App\Services;

use App\Http\Resources\SocioCollection;
use App\Http\Resources\SocioResource;
use App\Repositories\Interfaces\SocioRepositoryInterface;

class SocioService{

    protected $socioRepository;

    public function __construct(SocioRepositoryInterface $socioRepository){
        $this->socioRepository = $socioRepository;
    }

    public function getAllSocios(){
        $lista_socios = $this->socioRepository->getAll();
        if($lista_socios->isEmpty()){
            return response()->json([
                'message' => 'No se encontraron socios.'
            ], 404);
        }
        return new SocioCollection($lista_socios);
    }

    public function getSocio($id){
        $socio = $this->socioRepository->find($id);
        if(!$socio){
            return response()->json([
                'message' => 'Socio no encontrado.'
            ], 404);
        }
        return new SocioResource($socio);
    }

    public function createSocio(array $data, $image = null){
        if(isset($image)){
            $data['image'] = $image->store('socios', 'public');
        }
        return response()->json([
            'message' => $this->socioRepository->create($data) ,
        ], 201);
    }
}

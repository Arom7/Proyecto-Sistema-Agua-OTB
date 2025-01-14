<?php

namespace App\Services;

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
        return response()->json([
            'data' => $lista_socios,
            'message' => 'Lista de socios recuperada con exito.'
        ], 200);
    }
}

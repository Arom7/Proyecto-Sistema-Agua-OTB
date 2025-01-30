<?php

namespace App\Services;

use App\Http\Resources\SocioCollection;
use App\Http\Resources\SocioResource;
use App\Models\Usuario;
use App\Repositories\Interfaces\SocioRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;

class SocioService{

    protected $socioRepository;
    protected $userRepository;

    public function __construct(SocioRepositoryInterface $socioRepository, UserRepositoryInterface $userRepository){
        $this->socioRepository = $socioRepository;
        $this->userRepository = $userRepository;
    }

    // Metodo para acceder a todos los socios
    public function getAllSocios(){
        $lista_socios = $this->socioRepository->getAll();
        if($lista_socios->isEmpty()){
            return response()->json([
                'message' => 'No se encontraron socios.'
            ], 404);
        }
        return new SocioCollection($lista_socios);
    }

    // Metodo para acceder a un nuevo socio
    public function getSocio($id){
        $socio = $this->socioRepository->find($id);
        if(!$socio){
            return response()->json([
                'message' => 'Socio no encontrado.'
            ], 404);
        }
        return new SocioResource($socio);
    }

    // Metodo para crear un nuevo socio
    public function createSocio(array $data, $image = null){
        if(isset($image) && $image != null){
            $data['image'] = $image->store('socios', 'public');
        }
        $socio = $this->socioRepository->create($data);

        if($socio){
            $data_user = [
                'nombre' => $data['nombre'],
                'primer_apellido' => $data['primer_apellido'],
                'segundo_apellido' => $data['segundo_apellido'],
                'email' => $data['email'],
                'id_socio' => $socio->id
            ];

            $usuario = $this->userRepository->create($data_user);
            if($usuario){
                return $usuario;
            }else{
                return null;
            }
        }
        return response()->json([
            'message' => 'Error creado al socio.'
        ], 500);
    }

    // Metodo para la actualizacion de socios registrados
    public function updateSocio(array $data, $id,$image = null){
        if(isset($image) && $image != null){
            $data['image'] = $image->store('socios', 'public');
        }
        return $this->socioRepository->update($data, $id);
    }

    // Metodo para la eliminacion de socios,
    public function deleteSocio($id){
        return response()->json([
            'message' => $this->socioRepository->delete($id)
        ], 200);
    }
}

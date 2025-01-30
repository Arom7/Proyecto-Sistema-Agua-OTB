<?php

namespace App\Repositories\Eloquent;

use App\Models\Usuario;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;

class UserRepository implements UserRepositoryInterface{

    public function find(string $id)
    {
        return Usuario::find($id);
    }

    public function generarUsername (array $data){
        $username = strtolower(substr($data['nombre'],0,2).substr($data['primer_apellido'],0,2).substr($data['segundo_apellido'],0,2).rand(1000,9999));
        if(!$this->find($username)){
            return $username;
        }else{
            return $this->generarUsername($data);
        }
    }

    public function create(array $data)
    {
        DB::beginTransaction();
        $username = $this->generarUsername($data);
        $password = $this->generarPassword();
        $cuenta = Usuario::create([
            'username' => $username,
            'contrasenia' => bcrypt($password),
            'email' => $data['email'],
            'socio_id_usuario' => $data['id_socio'],
        ]);
        $user = [
            'username' => $username,
            'password' => $password,
            'email' => $data['email'],
        ];
        if($cuenta){
            DB::commit();
            return $user;
        }
        DB::rollBack();
        return null;
    }

    public function generarPassword($length = 12)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ@#$%&*';
        $charactersLength = strlen($characters);
        $randomPassword = '';
        for ($i = 0; $i < $length; $i++) {
            $randomPassword .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomPassword;
    }


    public function update(array $data, string $id) {
        return 0;
    }

}

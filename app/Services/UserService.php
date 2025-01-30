<?php

namespace App\Services;
use App\Repositories\Interfaces\UserRepositoryInterface;
class UserService
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function find(string $id){
        return $this->userRepository->find($id);
    }

    public function create(array $data){
        return $this->userRepository->create($data);
    }

    public function update(array $data, string $id){
        return $this->userRepository->update($data, $id);
    }
}

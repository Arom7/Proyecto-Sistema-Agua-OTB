<?php

namespace App\Repositories\Interfaces;

interface UserRepositoryInterface{
    //public function getAll();
    public function find(string $id);
    public function create(array $data);
    public function update(array $data, string $id );
    //public function delete(string $id);
}

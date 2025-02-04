<?php

namespace App\Repositories\Interfaces;

interface SocioRepositoryInterface{
    public function getAll();
    public function find(string $id);
    public function create(array $data);
    public function update(array $data, string $id );
    //public function update_partial(array $data, string $id);
    public function delete(string $id);
}


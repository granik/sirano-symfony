<?php

namespace App\Domain\Entity;


interface CityRepositoryInterface
{
    public function list(int $page, int $perPage);
    
    public function find($id): ?City;
    
    public function update(City $city);
    
    public function store(City $city);

    public function listAll();
}
<?php

namespace App\Domain\Entity\Module\Backend;


use App\Domain\Entity\Module\ModuleTest;

interface ModuleTestRepositoryInterface
{
    public function list(int $page, int $perPage);
    
    public function store(ModuleTest $entity);
    
    public function find($id);
    
    public function update(ModuleTest $entity);
}
<?php

namespace App\Domain\Entity\Module\Backend;


use App\Domain\Entity\Module\Module;
use App\Domain\Entity\Module\ModuleSlide;

interface ModuleRepositoryInterface
{
    public function list(int $page, int $perPage, array $criteria);
    
    public function store(Module $entity);
    
    public function find($id);
    
    public function update(Module $entity);
    
    public function storeSlide(ModuleSlide $slide);
    
    public function updateSlide(ModuleSlide $slide);
    
    public function findSlide(int $id);
    
    public function listAll($id);
    
    public function delete($entity);
    
    public function deleteSlidesByIds(array $deleteIds);
}
<?php


namespace App\Domain\Entity\Banner\Backend;


use App\Domain\Entity\Banner\Banner;

interface BannerRepositoryInterface
{
    public function list(int $page, int $perPage);
    
    public function store(Banner $entity);
    
    public function find($id);
    
    public function update(Banner $entity);
    
    public function delete($entity);
}
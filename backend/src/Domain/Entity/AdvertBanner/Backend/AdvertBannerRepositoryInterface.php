<?php


namespace App\Domain\Entity\AdvertBanner\Backend;


use App\Domain\Entity\AdvertBanner\AdvertBanner;

interface AdvertBannerRepositoryInterface
{
    public function list(int $page, int $perPage);
    
    public function store(AdvertBanner $entity);
    
    public function find($id);
    
    public function update(AdvertBanner $entity);
    
    public function delete($entity);
}
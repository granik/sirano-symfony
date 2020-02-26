<?php


namespace App\Domain\Entity\News\Backend;


use App\Domain\Entity\News\News;

interface NewsRepositoryInterface
{
    public function list(int $page, int $perPage, array $criteria);
    
    public function store(News $entity);
    
    public function find($id);
    
    public function update(News $entity);
    
    public function delete($entity);
}
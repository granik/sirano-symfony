<?php


namespace App\Domain\Entity\Article\Backend;


use App\Domain\Entity\Article\Article;

interface ArticleRepositoryInterface
{
    public function list(int $page, int $perPage, array $criteria);
    
    public function store(Article $entity);
    
    public function update(Article $entity);
    
    public function find($id);
    
    public function listAll();
    
    public function findByIds(array $articleIds);
    
    public function delete($entity);
}
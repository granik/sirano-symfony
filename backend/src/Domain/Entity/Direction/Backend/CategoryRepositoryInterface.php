<?php


namespace App\Domain\Entity\Direction\Backend;


use App\Domain\Entity\Direction\Category;
use App\Domain\Entity\Direction\Direction;

interface CategoryRepositoryInterface
{
    public function deleteByIds(array $deleteIds);
    
    public function store(Category $category);
    
    public function update(Category $category);
    
    public function find($id);
    
    public function listCategoryByDirection(Direction $entity);
}
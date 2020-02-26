<?php


namespace App\Domain\Entity\Direction\Frontend;


use App\Domain\Entity\Direction\Category;

interface CategoryRepositoryInterface
{
    public function find($id): ?Category;
}
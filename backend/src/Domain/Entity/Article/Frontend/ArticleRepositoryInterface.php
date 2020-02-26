<?php


namespace App\Domain\Entity\Article\Frontend;


interface ArticleRepositoryInterface
{
    public function list(int $page, int $perPage, $direction, $category);
}
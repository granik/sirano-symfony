<?php


namespace App\Domain\Entity\News\Frontend;


use App\Domain\Entity\News\News;

interface NewsRepositoryInterface
{
    public function list(int $page, int $perPage);
    
    public function find($id);
    
    public function randomNews(News $news);
    
    public function mainPage(int $limit, $direction);
}
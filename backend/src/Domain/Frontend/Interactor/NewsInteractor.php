<?php


namespace App\Domain\Frontend\Interactor;


use App\Domain\Entity\News\Frontend\NewsRepositoryInterface;
use App\Domain\Entity\News\News;

final class NewsInteractor
{
    /**
     * @var NewsRepositoryInterface
     */
    private $repository;
    
    /**
     * NewsInteractor constructor.
     *
     * @param NewsRepositoryInterface $repository
     */
    public function __construct(NewsRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
    
    public function list(int $page, int $perPage)
    {
        return $this->repository->list($page, $perPage);
    }
    
    public function find($id)
    {
        return $this->repository->find($id);
    }
    
    public function randomNews(News $news)
    {
        return $this->repository->randomNews($news);
    }
    
    public function mainPage(int $limit, $direction)
    {
        return $this->repository->mainPage($limit, $direction);
    }
}
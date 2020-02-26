<?php


namespace App\Domain\Frontend\Interactor;


use App\Domain\Entity\Article\Frontend\ArticleRepositoryInterface;

final class ArticleInteractor
{
    /**
     * @var ArticleRepositoryInterface
     */
    private $repository;
    
    /**
     * ArticleInteractor constructor.
     *
     * @param ArticleRepositoryInterface $repository
     */
    public function __construct(ArticleRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
    
    public function list(int $page, int $perPage, $direction, $category)
    {
        return $this->repository->list($page, $perPage, $direction, $category);
    }
}
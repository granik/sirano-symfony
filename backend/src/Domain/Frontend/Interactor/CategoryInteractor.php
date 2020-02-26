<?php


namespace App\Domain\Frontend\Interactor;


use App\Domain\Entity\Direction\Category;
use App\Domain\Entity\Direction\Frontend\CategoryRepositoryInterface;

final class CategoryInteractor
{
    /**
     * @var CategoryRepositoryInterface
     */
    private $repository;
    
    /**
     * CategoryInteractor constructor.
     *
     * @param CategoryRepositoryInterface $repository
     */
    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
    
    public function find($id): ?Category
    {
        return $this->repository->find($id);
    }
}
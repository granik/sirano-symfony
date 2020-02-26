<?php


namespace App\Frontend\Repository;


use App\Domain\Entity\Direction\Category;
use App\Domain\Entity\Direction\Frontend\CategoryRepositoryInterface;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;

final class CategoryRepository implements CategoryRepositoryInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    
    /**
     * @var ObjectRepository
     */
    private $objectRepository;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager    = $entityManager;
        $this->objectRepository = $this->entityManager->getRepository(Category::class);
    }
    
    public function find($id): ?Category
    {
        return $this->objectRepository->find($id);
    }
}
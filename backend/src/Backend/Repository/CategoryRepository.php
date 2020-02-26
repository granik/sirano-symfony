<?php


namespace App\Backend\Repository;


use App\Domain\Entity\Direction\Backend\CategoryRepositoryInterface;
use App\Domain\Entity\Direction\Category;
use App\Domain\Entity\Direction\Direction;
use App\Entity\ClinicalAnalyzesArticles;
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
    
    public function deleteByIds(array $deleteIds)
    {
        $this->entityManager
            ->createQuery('DELETE ' . Category::class . ' c WHERE c.id IN (:ids)')
            ->setParameter('ids', $deleteIds)
            ->getResult();
    }
    
    public function store(Category $category)
    {
        $this->entityManager->persist($category);
        $this->entityManager->flush();
    }
    
    public function update(Category $category)
    {
        $this->entityManager->flush();
    }
    
    public function find($id)
    {
        return $this->objectRepository->find($id);
    }
    
    public function listCategoryByDirection(Direction $entity)
    {
        return $this->objectRepository->findBy(['direction' => $entity], ['name' => 'asc']);
    }
}
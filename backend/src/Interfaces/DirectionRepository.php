<?php

namespace App\Interfaces;


use App\Domain\Entity\Direction\Category;
use App\Domain\Entity\Direction\Direction;
use App\Domain\Entity\Direction\DirectionRepositoryInterface;
use App\Domain\Entity\Module\Module;
use App\Domain\Entity\Module\ModuleSlide;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

final class DirectionRepository implements DirectionRepositoryInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    
    /**
     * @var ObjectRepository
     */
    private $objectRepository;
    
    /**
     * @var ObjectRepository
     */
    private $categoryRepository;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager      = $entityManager;
        $this->objectRepository   = $this->entityManager->getRepository(Direction::class);
        $this->categoryRepository = $this->entityManager->getRepository(Category::class);
    }
    
    public function list(int $page, int $perPage)
    {
        $dql   = 'SELECT c FROM ' . Direction::class . ' c ORDER BY c.name';
        $query = $this->entityManager->createQuery($dql)
            ->setFirstResult(($page - 1) * $perPage)
            ->setMaxResults($perPage);
        
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        
        return $paginator;
    }
    
    public function store(Direction $direction): Direction
    {
        $this->entityManager->persist($direction);
        $this->entityManager->flush();
        
        return $direction;
    }
    
    public function update(Direction $direction): Direction
    {
        $this->entityManager->flush();
        
        return $direction;
    }
    
    public function find($id): ?Direction
    {
        $entity = $this->objectRepository->find($id);
    
        if ($entity instanceof Direction) {
            $this->hydrate($entity);
        }
    
        return $entity;
    }
    
    public function activeList()
    {
        return $this->objectRepository->findBy(['isActive' => true], ['name' => 'asc']);
    }
    
    public function delete($entity)
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }
    
    public function mainPageList($limit)
    {
        return $this->objectRepository->findBy(['isActive' => true, 'isMainPage' => true], ['number' => 'asc'], $limit);
    }
    
    private function hydrate(Direction $entity)
    {
        $categories = $this->categoryRepository->findBy(['direction' => $entity], ['name' => 'asc']);
        $entity->setCategories($categories);
    }
}
<?php

namespace App\Backend\Repository;


use App\Domain\Entity\ClinicalAnalysis\ClinicalAnalysis;
use App\Domain\Entity\Module\Backend\ModuleRepositoryInterface;
use App\Domain\Entity\Module\Module;
use App\Domain\Entity\Module\ModuleSlide;
use App\Entity\ModulesArticles;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

final class ModuleRepository implements ModuleRepositoryInterface
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
    private $slideRepository;
    
    /**
     * @var ObjectRepository
     */
    private $articleRepository;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager     = $entityManager;
        $this->objectRepository  = $this->entityManager->getRepository(Module::class);
        $this->slideRepository   = $this->entityManager->getRepository(ModuleSlide::class);
        $this->articleRepository = $this->entityManager->getRepository(ModulesArticles::class);
    }
    
    public function list(int $page, int $perPage, array $criteria)
    {
        $whereClauses = [];
    
        if (isset($criteria['number']) && $criteria['number'] !== null) {
            $whereClauses[] = 'c.number = :number';
        }
    
        if (isset($criteria['name']) && $criteria['name'] !== null) {
            $whereClauses[] = 'c.name LIKE :name';
        }
    
        $where = '';
        if (!empty($whereClauses)) {
            $where = ' WHERE ' . implode(' AND ', $whereClauses);
        }
    
        $dql   = 'SELECT c FROM ' . Module::class . ' c ' . $where . ' ORDER BY c.number';
        $query = $this->entityManager->createQuery($dql)
            ->setFirstResult(($page - 1) * $perPage)
            ->setMaxResults($perPage);
    
        if (isset($criteria['number']) && $criteria['number'] !== null) {
            $query->setParameter('number', $criteria['number']);
        }
    
        if (isset($criteria['name']) && $criteria['name'] !== null) {
            $query->setParameter('name', "%{$criteria['name']}%");
        }
        
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        
        return $paginator;
    }
    
    public function store(Module $entity)
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        
        $this->storeModuleArticles($entity);
        
        return $entity;
    }
    
    public function find($id)
    {
        $entity = $this->objectRepository->find($id);
        
        if ($entity instanceof Module) {
            $this->hydrate($entity);
        }
        
        return $entity;
    }
    
    public function update(Module $entity)
    {
        $this->entityManager
            ->createQuery('DELETE ' . ModulesArticles::class . ' c WHERE c.module = :module')
            ->setParameter('module', $entity)
            ->getResult();
        
        $this->storeModuleArticles($entity);
        
        return $entity;
    }
    
    public function findSlide(int $id)
    {
        $entity = $this->slideRepository->find($id);
        
        return $entity;
    }
    
    public function storeSlide(ModuleSlide $slide)
    {
        $this->entityManager->persist($slide);
        $this->entityManager->flush();
    }
    
    public function updateSlide(ModuleSlide $slide)
    {
        $this->entityManager->flush();
    }
    
    public function listAll($id)
    {
        if ($id === null) {
            $exists = 'SELECT c FROM ' . ClinicalAnalysis::class . ' c WHERE c.module = m';
        } else {
            $exists = 'SELECT c FROM ' . ClinicalAnalysis::class . ' c WHERE c.module = m AND c.id != :id';
        };
        
        $query = $this->entityManager->createQuery(
            'SELECT m FROM '
            . Module::class
            . ' m WHERE NOT EXISTS ('
            . $exists
            . ')  ORDER BY m.name'
        );
        
        if ($id !== null) {
            $query->setParameter('id', $id);
        }
        
        return $query->getResult();
    }
    
    public function delete($entity)
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }
    
    public function deleteSlidesByIds(array $deleteIds)
    {
        $this->entityManager
            ->createQuery('DELETE ' . ModuleSlide::class . ' c WHERE c.id IN (:ids)')
            ->setParameter('ids', $deleteIds)
            ->getResult();
    }
    
    private function hydrate(Module $module)
    {
        $slides = $this->slideRepository->findBy(['module' => $module], ['number' => 'asc']);
        $module->setSlides($slides);
        
        $modulesArticles = $this->articleRepository->findBy(['module' => $module]);
        $articles        = [];
        foreach ($modulesArticles as $modulesArticle) {
            $articles[] = $modulesArticle->getArticle();
        }
        
        $module->setArticles($articles);
    }
    
    /**
     * @param Module $entity
     */
    private function storeModuleArticles(Module $entity): void
    {
        foreach ($entity->getArticles() as $article) {
            $entityArticle = (new ModulesArticles())
                ->setModule($entity)
                ->setArticle($article);
            $this->entityManager->persist($entityArticle);
        }
        
        $this->entityManager->flush();
    }
}
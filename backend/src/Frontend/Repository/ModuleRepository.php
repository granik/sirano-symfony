<?php

namespace App\Frontend\Repository;


use App\Domain\Entity\Customer\Customer;
use App\Domain\Entity\Direction\Category;
use App\Domain\Entity\Direction\Direction;
use App\Domain\Entity\Module\Frontend\ModuleRepositoryInterface;
use App\Domain\Entity\Module\Module;
use App\Domain\Entity\Module\ModuleSlide;
use App\Domain\Entity\Module\ModuleTestResult;
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
    
    public function list(int $page, int $perPage, $direction, $category)
    {
        $where = '';
        
        if ($direction instanceof Direction) {
            $where .= ' AND c.direction = :direction ';
        }
        
        if ($category instanceof Category) {
            $where .= ' AND c.category = :category ';
        }
        
        $dql   = 'SELECT c FROM '
            . Module::class
            . ' c WHERE c.isActive = TRUE '
            . $where
            . ' ORDER BY c.number';
        $query = $this->entityManager->createQuery($dql)
            ->setFirstResult(($page - 1) * $perPage)
            ->setMaxResults($perPage);
        
        if ($direction instanceof Direction) {
            $query->setParameter('direction', $direction);
        }
    
        if ($category instanceof Category) {
            $query->setParameter('category', $category);
        }
        
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        
        return $paginator;
    }
    
    public function find($id): ?Module
    {
        $entity = $this->objectRepository->find($id);
        
        if ($entity instanceof Module) {
            $this->hydrate($entity);
        }
        
        return $entity;
    }
    
    public function dashboard(Customer $customer)
    {
        $dql   = 'SELECT c FROM '
            . Module::class
            . ' c JOIN c.test t WHERE c.isActive = TRUE AND NOT EXISTS (SELECT r FROM '
            . ModuleTestResult::class
            . ' r WHERE r.customer = :customer AND r.module = c.id) ORDER BY c.number';
        $query = $this->entityManager->createQuery($dql)
            ->setParameter('customer', $customer)
            ->setMaxResults(2);
        
        return $query->getResult();
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
    
    public function getProfileModules(Customer $customer, int $page, int $perPage)
    {
        $dql   = 'SELECT c FROM '
            . Module::class
            . ' c JOIN c.test t WHERE c.isActive = TRUE AND NOT EXISTS (SELECT r FROM '
            . ModuleTestResult::class
            . ' r WHERE r.customer = :customer AND r.module = c.id) ORDER BY c.number';
        $query = $this->entityManager->createQuery($dql)
            ->setParameter('customer', $customer)
            ->setFirstResult(($page - 1) * $perPage)
            ->setMaxResults($perPage);
        
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        
        return $paginator;
    }
    
    public function getCustomerScore(Customer $customer)
    {
        $dql   = 'SELECT COUNT(c.id) FROM '
            . Module::class
            . ' c JOIN c.test t WHERE c.isActive = TRUE AND EXISTS (SELECT r FROM '
            . ModuleTestResult::class
            . ' r WHERE r.customer = :customer AND r.module = c.id AND r.correctAnswers >= 8)';
        $query = $this->entityManager->createQuery($dql)
            ->setParameter('customer', $customer);
        
        return $query->getSingleScalarResult();
    }
    
    public function getMaxScore()
    {
        $dql   = 'SELECT COUNT(c.id) FROM '
            . Module::class
            . ' c JOIN c.test t WHERE c.isActive = TRUE';
        $query = $this->entityManager->createQuery($dql);
    
        return $query->getSingleScalarResult();
    }
}
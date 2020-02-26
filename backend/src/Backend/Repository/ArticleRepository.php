<?php


namespace App\Backend\Repository;


use App\Domain\Entity\Article\Article;
use App\Domain\Entity\Article\Backend\ArticleRepositoryInterface;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

final class ArticleRepository implements ArticleRepositoryInterface
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
        $this->objectRepository = $this->entityManager->getRepository(Article::class);
    }
    
    public function list(int $page, int $perPage, array $criteria)
    {
        $whereClauses = [];
    
        if (isset($criteria['author']) && $criteria['author'] !== null) {
            $whereClauses[] = 'c.author LIKE :author';
        }
    
        if (isset($criteria['name']) && $criteria['name'] !== null) {
            $whereClauses[] = 'c.name LIKE :name';
        }

        $where = '';
        if (!empty($whereClauses)) {
            $where = ' WHERE ' . implode(' AND ', $whereClauses);
        }
    
        $dql   = 'SELECT c FROM ' . Article::class . ' c ' . $where . ' ORDER BY c.name';
        $query = $this->entityManager->createQuery($dql)
            ->setFirstResult(($page - 1) * $perPage)
            ->setMaxResults($perPage);
    
        if (isset($criteria['author']) && $criteria['author'] !== null) {
            $query->setParameter('author', "%{$criteria['author']}%");
        }
    
        if (isset($criteria['name']) && $criteria['name'] !== null) {
            $query->setParameter('name', "%{$criteria['name']}%");
        }
        
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        
        return $paginator;
    }
    
    public function store(Article $entity)
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        
        return $entity;
    }
    
    public function update(Article $entity)
    {
        $this->entityManager->flush();
        
        return $entity;
    }
    
    public function find($id)
    {
        $entity = $this->objectRepository->find($id);
        
        return $entity;
    }
    
    public function listAll()
    {
        return $this->objectRepository->findBy([], ['name' => 'asc']);
    }
    
    public function findByIds(array $articleIds)
    {
        $query = $this->entityManager
            ->createQuery('SELECT c FROM ' . Article::class . ' c WHERE c.id IN (:ids)')
            ->setParameter('ids', $articleIds);
            
        return $query->getResult();
    }
    
    public function delete($entity)
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }
}
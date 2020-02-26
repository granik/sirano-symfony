<?php


namespace App\Backend\Repository;


use App\Domain\Entity\News\Backend\NewsRepositoryInterface;
use App\Domain\Entity\News\News;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

final class NewsRepository implements NewsRepositoryInterface
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
        $this->entityManager    = $entityManager;
        $this->objectRepository = $this->entityManager->getRepository(News::class);
    }
    
    public function list(int $page, int $perPage, array $criteria)
    {
        $whereClauses = [];
    
        if (isset($criteria['createdAt']) && $criteria['createdAt'] !== null) {
            $whereClauses[] = 'c.createdAt >= :createdAtStart AND c.createdAt < :createdAtEnd';
        }
    
        if (isset($criteria['name']) && $criteria['name'] !== null) {
            $whereClauses[] = 'c.name LIKE :name';
        }
    
        $where = '';
        if (!empty($whereClauses)) {
            $where = ' WHERE ' . implode(' AND ', $whereClauses);
        }
    
        $dql   = 'SELECT c FROM ' . News::class . ' c ' . $where . ' ORDER BY c.createdAt DESC';
        $query = $this->entityManager->createQuery($dql)
            ->setFirstResult(($page - 1) * $perPage)
            ->setMaxResults($perPage);
    
        if (isset($criteria['createdAt']) && $criteria['createdAt'] !== null) {
            $query
                ->setParameter('createdAtStart', new \DateTime($criteria['createdAt']))
                ->setParameter('createdAtEnd', (new \DateTime($criteria['createdAt']))->modify('tomorrow'));
        }
    
        if (isset($criteria['name']) && $criteria['name'] !== null) {
            $query->setParameter('name', "%{$criteria['name']}%");
        }
        
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        
        return $paginator;
    }
    
    public function store(News $entity)
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        
        return $entity;
    }
    
    public function find($id)
    {
        $entity = $this->objectRepository->find($id);
        
        return $entity;
    }
    
    public function update(News $entity)
    {
        $this->entityManager->flush();
        
        return $entity;
    }
    
    public function delete($entity)
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }
}
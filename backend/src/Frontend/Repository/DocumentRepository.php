<?php


namespace App\Frontend\Repository;


use App\Domain\Entity\Document\Document;
use App\Domain\Entity\Document\Frontend\DocumentRepositoryInterface;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

final class DocumentRepository implements DocumentRepositoryInterface
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
        $this->objectRepository = $this->entityManager->getRepository(Document::class);
    }
    
    public function list(int $page, int $perPage)
    {
        $dql   = 'SELECT m FROM ' . Document::class . ' m WHERE m.isActive = TRUE ORDER BY m.name';
        $query = $this->entityManager->createQuery($dql)
            ->setFirstResult(($page - 1) * $perPage)
            ->setMaxResults($perPage);
        
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        
        return $paginator;
    }
}
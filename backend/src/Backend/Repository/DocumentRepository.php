<?php


namespace App\Backend\Repository;


use App\Domain\Entity\Document\Document;
use App\Domain\Entity\Document\Backend\DocumentRepositoryInterface;
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
        $dql   = 'SELECT c FROM ' . Document::class . ' c ORDER BY c.name';
        $query = $this->entityManager->createQuery($dql)
            ->setFirstResult(($page - 1) * $perPage)
            ->setMaxResults($perPage);
        
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        
        return $paginator;
    }
    
    public function store(Document $entity)
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        
        return $entity;
    }
    
    public function update(Document $entity)
    {
        $this->entityManager->flush();
        
        return $entity;
    }
    
    public function find($id)
    {
        $entity = $this->objectRepository->find($id);
        
        return $entity;
    }
    
    public function delete($entity)
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }
}
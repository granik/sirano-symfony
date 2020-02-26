<?php


namespace App\Backend\Repository;


use App\Domain\Entity\PresidiumMember\PresidiumMember;
use App\Domain\Entity\PresidiumMember\Backend\PresidiumMemberRepositoryInterface;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

final class PresidiumMemberRepository implements PresidiumMemberRepositoryInterface
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
        $this->objectRepository = $this->entityManager->getRepository(PresidiumMember::class);
    }
    
    public function list(int $page, int $perPage)
    {
        $dql   = 'SELECT c FROM ' . PresidiumMember::class . ' c ORDER BY c.number';
        $query = $this->entityManager->createQuery($dql)
            ->setFirstResult(($page - 1) * $perPage)
            ->setMaxResults($perPage);
        
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        
        return $paginator;
    }
    
    public function store(PresidiumMember $entity)
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
    
    public function update(PresidiumMember $entity)
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
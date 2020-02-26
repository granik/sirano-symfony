<?php


namespace App\Backend\Repository;


use App\Domain\Entity\Banner\Backend\BannerRepositoryInterface;
use App\Domain\Entity\Banner\Banner;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

final class BannerRepository implements BannerRepositoryInterface
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
        $this->objectRepository = $this->entityManager->getRepository(Banner::class);
    }
    
    public function list(int $page, int $perPage)
    {
        $dql   = 'SELECT c FROM ' . Banner::class . ' c ORDER BY c.number';
        $query = $this->entityManager->createQuery($dql)
            ->setFirstResult(($page - 1) * $perPage)
            ->setMaxResults($perPage);
        
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        
        return $paginator;
    }
    
    public function store(Banner $entity)
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
    
    public function update(Banner $entity)
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
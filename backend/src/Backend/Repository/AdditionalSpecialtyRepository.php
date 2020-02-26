<?php


namespace App\Backend\Repository;


use App\Domain\Entity\Specialty\AdditionalSpecialty;
use App\Domain\Entity\Specialty\AdditionalSpecialtyRepositoryInterface;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

final class AdditionalSpecialtyRepository implements AdditionalSpecialtyRepositoryInterface
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
        $this->objectRepository = $this->entityManager->getRepository(AdditionalSpecialty::class);
    }
    
    public function list(int $page, int $perPage, array $criteria)
    {
        $dql   = 'SELECT c FROM ' . AdditionalSpecialty::class . ' c ORDER BY c.name';
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
    
    public function store(AdditionalSpecialty $entity)
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }
    
    public function find($id)
    {
        return $this->objectRepository->find($id);
    }
    
    public function update(AdditionalSpecialty $entity)
    {
        $this->entityManager->flush();
    }
    
    public function delete($entity)
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }
    
    public function customerFormlist()
    {
        return $this->objectRepository->findBy([]);
    }
    
    public function findByName($name)
    {
        return $this->objectRepository->findOneBy(['name' => $name]);
    }
}
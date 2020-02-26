<?php

namespace App\Interfaces;


use App\Domain\Entity\City;
use App\Domain\Entity\CityRepositoryInterface;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

final class CityRepository implements CityRepositoryInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;
    
    /** @var ObjectRepository */
    private $objectRepository;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager    = $entityManager;
        $this->objectRepository = $this->entityManager->getRepository(City::class);
    }

    public function list(int $page, int $perPage)
    {
        $dql   = 'SELECT c FROM ' . City::class . ' c ORDER BY c.name';
        $query = $this->entityManager->createQuery($dql)
            ->setFirstResult(($page - 1) * $perPage)
            ->setMaxResults($perPage);

        $paginator = new Paginator($query, $fetchJoinCollection = true);

        return $paginator;
    }
    
    public function find($id): ?City
    {
        return $this->objectRepository->find($id);
    }
    
    public function update(City $city)
    {
        $this->entityManager->flush();
    
        return $city;
    }
    
    public function store(City $city)
    {
        $this->entityManager->persist($city);
        $this->entityManager->flush();
    
        return $city;
    }

    public function listAll()
    {
        return $this->objectRepository->findBy([], ['name' => 'asc']);
    }
}
<?php


namespace App\Backend\Repository;


use App\Domain\Entity\Conference\Backend\ConferenceSeriesRepositoryInterface;
use App\Domain\Entity\Conference\ConferenceSeries;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

final class ConferenceSeriesRepository implements ConferenceSeriesRepositoryInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;
    
    /** @var ObjectRepository */
    private $objectRepository;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager    = $entityManager;
        $this->objectRepository = $this->entityManager->getRepository(ConferenceSeries::class);
    }
    
    public function list(int $page, int $perPage)
    {
        $dql   = 'SELECT c FROM ' . ConferenceSeries::class . ' c ORDER BY c.name';
        $query = $this->entityManager->createQuery($dql)
            ->setFirstResult(($page - 1) * $perPage)
            ->setMaxResults($perPage);
        
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        
        return $paginator;
    }
    
    public function store(ConferenceSeries $conferenceSeries)
    {
        $this->entityManager->persist($conferenceSeries);
        $this->entityManager->flush();
    }
    
    public function find($id)
    {
        return $this->objectRepository->find($id);
    }
    
    public function update(ConferenceSeries $conferenceSeries)
    {
        $this->entityManager->flush();
    }
    
    public function delete($entity)
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }
    
    public function listAll()
    {
        return $this->objectRepository->findBy([], ['name' => 'asc']);
    }
}
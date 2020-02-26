<?php

namespace App\Backend\Repository;


use App\Domain\Entity\Conference\Backend\ConferenceRepositoryInterface;
use App\Domain\Entity\Conference\Conference;
use App\Domain\Entity\Conference\ConferenceProgram;
use App\Domain\Entity\Conference\ConferenceSubscriber;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

final class ConferenceRepository implements ConferenceRepositoryInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;
    
    /** @var ObjectRepository */
    private $objectRepository;
    private $programRepository;
    /**
     * @var ObjectRepository
     */
    private $subscriberRepository;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager        = $entityManager;
        $this->objectRepository     = $this->entityManager->getRepository(Conference::class);
        $this->programRepository    = $this->entityManager->getRepository(ConferenceProgram::class);
        $this->subscriberRepository = $this->entityManager->getRepository(ConferenceSubscriber::class);
    }
    
    public function list(int $page, int $perPage, array $criteria)
    {
        $whereClauses = [];
    
        if (isset($criteria['startDate']) && $criteria['startDate'] !== null) {
            $whereClauses[] = 'c.startDateTime >= :startDateStart AND c.startDateTime < :startDateEnd';
        }
    
        if (isset($criteria['city']) && $criteria['city'] !== null) {
            $whereClauses[] = 'c.city = :city';
        }
    
        if (isset($criteria['name']) && $criteria['name'] !== null) {
            $whereClauses[] = 'c.name LIKE :name';
        }
    
        $where = '';
        if (!empty($whereClauses)) {
            $where = ' WHERE ' . implode(' AND ', $whereClauses);
        }
    
        $dql   = 'SELECT c FROM ' . Conference::class . ' c ' . $where . ' ORDER BY c.startDateTime DESC';
        $query = $this->entityManager->createQuery($dql)
            ->setFirstResult(($page - 1) * $perPage)
            ->setMaxResults($perPage);
    
        if (isset($criteria['startDate']) && $criteria['startDate'] !== null) {
            $query
                ->setParameter('startDateStart', new \DateTime($criteria['startDate']))
                ->setParameter('startDateEnd', (new \DateTime($criteria['startDate']))->modify('tomorrow'));
        }
    
        if (isset($criteria['city']) && $criteria['city'] !== null) {
            $query->setParameter('city', $criteria['city']);
        }
    
        if (isset($criteria['name']) && $criteria['name'] !== null) {
            $query->setParameter('name', "%{$criteria['name']}%");
        }
    
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        
        if ($paginator->count() > 0) {
            foreach ($paginator as $conference) {
                $this->hydrate($conference);
            }
        }
        
        return $paginator;
    }
    
    public function store(Conference $conference)
    {
        $this->entityManager->persist($conference);
        $this->entityManager->flush();
        
        return $conference;
    }
    
    public function find($id)
    {
        $conference = $this->objectRepository->find($id);
        
        if ($conference instanceof Conference) {
            $this->hydrate($conference);
        }
        
        return $conference;
    }
    
    public function update(Conference $conference)
    {
        $this->entityManager->flush();
        
        return $conference;
    }
    
    public function delete($entity)
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }
    
    private function hydrate(Conference $conference)
    {
        $programs = $this->programRepository->findBy(['conference' => $conference]);
        $conference->setPrograms($programs);
        
        $subscribers = $this->entityManager->createQuery(
            'SELECT s FROM '
            . ConferenceSubscriber::class
            . ' s JOIN s.customer c WHERE s.conference = :conference ORDER BY c.lastname, c.name, c.middlename'
        )
            ->setParameter('conference', $conference)
            ->getResult();
        $conference->setSubscribers($subscribers);
    }
}
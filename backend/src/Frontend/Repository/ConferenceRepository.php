<?php

namespace App\Frontend\Repository;


use App\Domain\Entity\Conference\Conference;
use App\Domain\Entity\Conference\ConferenceProgram;
use App\Domain\Entity\Conference\Frontend\ConferenceRepositoryInterface;
use App\Domain\Entity\Customer\Customer;
use App\Domain\Entity\Direction\Direction;
use DateTime;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

final class ConferenceRepository implements ConferenceRepositoryInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;
    
    /** @var ObjectRepository */
    private $objectRepository;
    
    /** @var ObjectRepository */
    private $programRepository;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager     = $entityManager;
        $this->objectRepository  = $this->entityManager->getRepository(Conference::class);
        $this->programRepository = $this->entityManager->getRepository(ConferenceProgram::class);
    }
    
    public function list(int $page, int $perPage, DateTime $tillDate, $direction)
    {
        $where = '';
        
        if ($direction instanceof Direction) {
            $where = ' AND c.direction = :direction ';
        }
        
        $dql   = 'SELECT c FROM '
            . Conference::class
            . ' c WHERE c.isActive = TRUE AND c.endDateTime > :endDate AND c.startDateTime <= :tillDate '
            . $where
            . 'ORDER BY c.startDateTime';
        $query = $this->entityManager->createQuery($dql)
            ->setParameter('endDate', (new DateTime())->modify('-1 hour'))
            ->setParameter('tillDate', $tillDate)
            ->setFirstResult(($page - 1) * $perPage)
            ->setMaxResults($perPage);
        
        if ($direction instanceof Direction) {
            $query->setParameter('direction', $direction);
        }
        
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        
        return $paginator;
    }
    
    public function archive(int $page, int $perPage, ?DateTime $fromDate, $direction)
    {
        $whereDirection = '';
        
        if ($direction instanceof Direction) {
            $whereDirection = ' AND c.direction = :direction ';
        }
        
        if ($fromDate instanceof DateTime) {
            $whereDirection = ' AND c.startDateTime >= :fromDate ';
        }
        
        $dql   = 'SELECT c FROM '
            . Conference::class
            . ' c WHERE c.isActive = TRUE AND c.endDateTime < :endDate '
            . $whereDirection
            . 'ORDER BY c.startDateTime DESC';
        $query = $this->entityManager->createQuery($dql)
            ->setParameter('endDate', (new DateTime())->modify('-1 hour'))
            ->setFirstResult(($page - 1) * $perPage)
            ->setMaxResults($perPage);
        
        if ($direction instanceof Direction) {
            $query->setParameter('direction', $direction);
        }
        
        if ($fromDate instanceof DateTime) {
            $query->setParameter('fromDate', $fromDate);
        }
        
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        
        return $paginator;
    }
    
    public function find($id)
    {
        $conference = $this->objectRepository->find($id);
        
        if ($conference instanceof Conference) {
            $this->hydrate($conference);
        }
        
        return $conference;
    }
    
    public function dashboard(Customer $customer)
    {
        $dql   = 'SELECT c FROM ' . Conference::class . ' c JOIN c.subscribers s WHERE c.isActive = TRUE AND c.startDateTime >= CURRENT_DATE() AND s.customer = :customer ORDER BY c.startDateTime';
        $query = $this->entityManager->createQuery($dql)
            ->setParameter('customer', $customer)
            ->setMaxResults(2);
        
        return $query->getResult();
    }
    
    public function getProfileConferences(Customer $customer, int $page, int $perPage, DateTime $tillDate)
    {
        $dql   = 'SELECT c FROM ' . Conference::class . ' c JOIN c.subscribers s WHERE c.isActive = TRUE AND c.startDateTime >= CURRENT_DATE() AND c.startDateTime <= :tillDate AND s.customer = :customer ORDER BY c.startDateTime';
        $query = $this->entityManager->createQuery($dql)
            ->setParameter('tillDate', $tillDate)
            ->setParameter('customer', $customer)
            ->setFirstResult(($page - 1) * $perPage)
            ->setMaxResults($perPage);
        
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        
        return $paginator;
    }
    
    public function getCustomerScore(Customer $customer)
    {
        $dql   = 'SELECT SUM(c.score) FROM ' . Conference::class . ' c JOIN c.subscribers s WHERE c.isActive = TRUE AND s.customer = :customer AND s.visit = TRUE';
        $query = $this->entityManager->createQuery($dql)
            ->setParameter('customer', $customer);
        
        return $query->getSingleScalarResult();
    }
    
    public function listComingSoon(int $limit, ?Direction $direction)
    {
        $where = '';
        
        if ($direction instanceof Direction) {
            $where = ' AND c.direction = :direction ';
        }
        
        $dql   = 'SELECT c FROM '
            . Conference::class
            . ' c WHERE c.isActive = TRUE AND c.endDateTime > :endDate '
            . $where
            . 'ORDER BY c.startDateTime';
        $query = $this->entityManager->createQuery($dql)
            ->setParameter('endDate', (new DateTime())->modify('-1 hour'))
            ->setMaxResults($limit);
        
        if ($direction instanceof Direction) {
            $query->setParameter('direction', $direction);
        }
        
        return $query->getResult();
    }
    
    public function getMaxScore()
    {
        $dql   = 'SELECT SUM(c.score) FROM ' . Conference::class . ' c WHERE c.isActive = TRUE';
        $query = $this->entityManager->createQuery($dql);
        
        return $query->getSingleScalarResult();
    }
    
    private function hydrate(Conference $conference)
    {
        $programs = $this->programRepository->findBy(['conference' => $conference]);
        $conference->setPrograms($programs);
    }
}
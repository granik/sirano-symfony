<?php

namespace App\Interfaces;


use App\Domain\Entity\Customer\Customer;
use App\Domain\Entity\Direction\Direction;
use App\Webinar\Frontend\WebinarRepositoryInterface;
use App\Webinar\Webinar;
use DateTime;
use DateTimeZone;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class WebinarRepository implements WebinarRepositoryInterface
{
    const WHERE_CLAUSE = 'c.isActive = TRUE';
    
    /** @var EntityManagerInterface */
    private $entityManager;
    
    /** @var ObjectRepository */
    private $objectRepository;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager    = $entityManager;
        $this->objectRepository = $this->entityManager->getRepository(Webinar::class);
    }
    
    public function listAll()
    {
        return $this->objectRepository->findAll();
    }
    
    public function find($id)
    {
        return $this->objectRepository->find($id);
    }
    
    public function archive(int $page, int $perPage, ?DateTime $fromDate, $direction)
    {
        $where = '';
        
        if ($direction instanceof Direction) {
            $where = ' AND c.direction = :direction ';
        }
        
        if ($fromDate instanceof DateTime) {
            $where = ' AND c.startDatetime >= :fromDate ';
        }
        
        $dql   = 'SELECT c FROM '
            . Webinar::class
            . ' c WHERE ' . self::WHERE_CLAUSE . ' AND c.endDatetime < :endDateTime '
            . $where
            . ' ORDER BY c.startDatetime DESC';
        $query = $this->entityManager->createQuery($dql)
            ->setParameter('endDateTime', (new DateTime())->modify('-1 hour'))
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
    
    public function update(Webinar $webinar)
    {
        $this->entityManager->flush();
        
        return $webinar;
    }
    
    public function store(Webinar $webinar)
    {
        $this->entityManager->persist($webinar);
        $this->entityManager->flush();
        
        return $webinar;
    }
    
    public function list(int $page, int $perPage, DateTime $tillDate, $direction)
    {
        $where = '';
        
        if ($direction instanceof Direction) {
            $where = ' AND c.direction = :direction ';
        }
        
        $dql   = 'SELECT c FROM '
            . Webinar::class
            . ' c WHERE ' . self::WHERE_CLAUSE . ' AND c.endDatetime >= :endDateTime AND c.startDatetime <= :tillDate'
            . $where
            . ' ORDER BY c.startDatetime';
        $query = $this->entityManager->createQuery($dql)
            ->setParameter('endDateTime', (new DateTime())->modify('-1 hour'))
            ->setParameter('tillDate', $tillDate)
            ->setFirstResult(($page - 1) * $perPage)
            ->setMaxResults($perPage);
        
        if ($direction instanceof Direction) {
            $query->setParameter('direction', $direction);
        }
        
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        
        return $paginator;
    }
    
    public function dashboard(Customer $customer)
    {
        $dql   = 'SELECT c FROM '
            . Webinar::class
            . ' c JOIN c.subscribers s WHERE ' . self::WHERE_CLAUSE . ' AND c.endDatetime >= :endDateTime AND s.customer = :customer ORDER BY c.startDatetime';
        $query = $this->entityManager->createQuery($dql)
            ->setParameter('customer', $customer)
            ->setParameter('endDateTime', (new DateTime())->modify('-1 hour'))
            ->setMaxResults(2);
        
        return $query->getResult();
    }
    
    public function getProfileWebinars(Customer $customer, int $page, int $perPage, DateTime $tillDate)
    {
        $dql   = 'SELECT c FROM '
            . Webinar::class
            . ' c JOIN c.subscribers s WHERE ' . self::WHERE_CLAUSE . ' AND c.endDatetime >= :endDateTime AND c.startDatetime <= :tillDate  AND s.customer = :customer';
        $query = $this->entityManager->createQuery($dql)
            ->setParameter('tillDate', $tillDate)
            ->setParameter('customer', $customer)
            ->setParameter('endDateTime', (new DateTime())->modify('-1 hour'))
            ->setFirstResult(($page - 1) * $perPage)
            ->setMaxResults($perPage);
        
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        
        return $paginator;
    }
    
    public function randomArchive(Webinar $webinar)
    {
        $resultSetMapping = new ResultSetMappingBuilder($this->entityManager);
        $resultSetMapping->addRootEntityFromClassMetadata(Webinar::class, 'c');
        
        $query = $this->entityManager->createNativeQuery(<<<SQL
SELECT
    `c`.`id`,
    `c`.`name`,
    `c`.`description`,
    `c`.`youtube_code`,
    `c`.`start_datetime`,
    `c`.`end_datetime`,
    `c`.`direction_id`,
    `c`.`subject`,
    `c`.`score`,
    `c`.`confirmation_time1`,
    `c`.`confirmation_time2`,
    `c`.`confirmation_time3`,
    `c`.`email`,
    `c`.`is_active`
FROM
    `webinars` `c`
WHERE
    `is_active` IS TRUE
    AND `c`.`id` != :id
    AND `c`.`end_datetime` < :endDateTime
ORDER BY
    RAND()
LIMIT
    1
SQL
            , $resultSetMapping);
        $query
            ->setParameter('id', $webinar->getId())
            ->setParameter('endDateTime', (new DateTime())->modify('-1 hour'));
        
        return $query->getOneOrNullResult();
    }
    
    public function getCustomerScore(Customer $customer)
    {
        $dql   = 'SELECT SUM(c.score) FROM ' . Webinar::class . ' c JOIN c.subscribers s WHERE ' . self::WHERE_CLAUSE . ' AND s.customer = :customer AND s.confirmNumber >= 2';
        $query = $this->entityManager->createQuery($dql)
            ->setParameter('customer', $customer);
        
        return $query->getSingleScalarResult();
    }
    
    public function getMaxScore()
    {
        $dql   = 'SELECT SUM(c.score) FROM ' . Webinar::class . ' c WHERE ' . self::WHERE_CLAUSE;
        $query = $this->entityManager->createQuery($dql);
        
        return $query->getSingleScalarResult();
    }
    
    public function getInADayWebinars(DateTime $dateTime)
    {
        $dateTime->setTimezone(new DateTimeZone('Europe/Moscow'));
        $hour        = $dateTime->format('H');
        $currentHour = clone $dateTime;
        $currentHour->setTime($hour, 0);
        
        $from = clone $currentHour;
        $from->modify('+1 day');
        $till = clone $from;
        $till->modify('+1 hour');
        
        $dql   = 'SELECT c FROM '
            . Webinar::class
            . ' c WHERE ' . self::WHERE_CLAUSE . ' AND c.startDatetime >= :from AND c.startDatetime < :till';
        $query = $this->entityManager->createQuery($dql)
            ->setParameter('from', $from)
            ->setParameter('till', $till);
        
        return $query->getResult();
    }
}
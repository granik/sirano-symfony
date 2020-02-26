<?php

namespace App\Backend\Repository;


use App\Webinar\Backend\WebinarRepositoryInterface;
use App\Webinar\Webinar;
use App\Webinar\WebinarSubscriber;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

class WebinarRepository implements WebinarRepositoryInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;
    
    /** @var ObjectRepository */
    private $objectRepository;
    /**
     * @var ObjectRepository
     */
    private $subscriberRepository;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager        = $entityManager;
        $this->objectRepository     = $this->entityManager->getRepository(Webinar::class);
        $this->subscriberRepository = $this->entityManager->getRepository(WebinarSubscriber::class);
    }
    
    public function list(int $page, int $perPage, array $criteria)
    {
        $whereClauses = [];
    
        if (isset($criteria['startDate']) && $criteria['startDate'] !== null) {
            $whereClauses[] = 'c.startDatetime >= :startDateStart AND c.startDatetime < :startDateEnd';
        }
    
        if (isset($criteria['name']) && $criteria['name'] !== null) {
            $whereClauses[] = 'c.name LIKE :name';
        }
    
        $where = '';
        if (!empty($whereClauses)) {
            $where = ' WHERE ' . implode(' AND ', $whereClauses);
        }
    
        $dql   = 'SELECT c FROM ' . Webinar::class . ' c ' . $where . ' ORDER BY c.startDatetime DESC';
        $query = $this->entityManager->createQuery($dql)
            ->setFirstResult(($page - 1) * $perPage)
            ->setMaxResults($perPage);
    
        if (isset($criteria['startDate']) && $criteria['startDate'] !== null) {
            $query
                ->setParameter('startDateStart', new \DateTime($criteria['startDate']))
                ->setParameter('startDateEnd', (new \DateTime($criteria['startDate']))->modify('tomorrow'));
        }
    
        if (isset($criteria['name']) && $criteria['name'] !== null) {
            $query->setParameter('name', "%{$criteria['name']}%");
        }
        
        $paginator = new Paginator($query, $fetchJoinCollection = true);
    
        if ($paginator->count() > 0) {
            foreach ($paginator as $item) {
                $this->hydrate($item);
            }
        }
        
        return $paginator;
    }
    
    public function find($id): ?Webinar
    {
        $entity = $this->objectRepository->find($id);
        
        if ($entity instanceof Webinar) {
            $this->hydrate($entity);
        }
        
        return $entity;
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
    
    public function delete($entity)
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }
    
    private function hydrate(Webinar $webinar)
    {
        $subscribers = $this->subscriberRepository->findBy(['webinar' => $webinar]);
        $webinar->setSubscribers($subscribers);
    }
}
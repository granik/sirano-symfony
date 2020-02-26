<?php

namespace App\Interfaces;


use App\Domain\Entity\Customer\Backend\CustomerRepositoryInterface;
use App\Domain\Entity\Customer\Customer;
use App\Domain\Interactor\User\User;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

final class CustomerRepository implements CustomerRepositoryInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;
    
    /** @var ObjectRepository */
    private $objectRepository;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager    = $entityManager;
        $this->objectRepository = $this->entityManager->getRepository(Customer::class);
    }
    
    public function findByEmail($email)
    {
        return $this->objectRepository->findOneBy(['email' => $email]);
    }
    
    public function store(Customer $customer): Customer
    {
        $this->entityManager->persist($customer);
        $this->entityManager->flush();
        
        return $customer;
    }
    
    public function list(int $page, int $perPage, array $criteria)
    {
        $whereClauses = [];
        
        if (isset($criteria['name']) && $criteria['name'] !== null) {
            $whereClauses[] = '(c.name LIKE :name OR c.lastname LIKE :name OR c.middlename LIKE :name)';
        }
        
        if (isset($criteria['email']) && $criteria['email'] !== null) {
            $whereClauses[] = 'c.email LIKE :email';
        }
        
        if (isset($criteria['addedFrom']) && $criteria['addedFrom'] !== null) {
            $whereClauses[] = 'u.addedFrom = :addedFrom';
        }
        
        if (isset($criteria['registeredAt']) && $criteria['registeredAt'] !== null) {
            $whereClauses[] = 'u.registeredAt >= :registeredAtStart AND u.registeredAt < :registeredAtEnd';
        }
        
        if (isset($criteria['isActive']) && $criteria['isActive'] !== null) {
            $whereClauses[] = 'u.isActive = :isActive';
        }
        
        if (isset($criteria['sendingCounter']) && $criteria['sendingCounter'] !== null) {
            $whereClauses[] = 'u.sendingCounter = :sendingCounter';
        }
        
        if (isset($criteria['sendingDateTime']) && $criteria['sendingDateTime'] !== null) {
            $whereClauses[] = 'u.sendingDateTime >= :sendingDateTimeStart AND u.sendingDateTime < :sendingDateTimeEnd';
        }
        
        $where = '';
        if (!empty($whereClauses)) {
            $where = ' AND ' . implode(' AND ', $whereClauses);
        }
        
        $dql   = 'SELECT c FROM '
            . Customer::class
            . ' c JOIN '
            . User::class
            . ' u WHERE c.id = u.customerId'
            . $where
            . ' ORDER BY c.lastname, c.name, c.middlename';
        $query = $this->entityManager->createQuery($dql)
            ->setFirstResult(($page - 1) * $perPage)
            ->setMaxResults($perPage);
        
        if (isset($criteria['name']) && $criteria['name'] !== null) {
            $query->setParameter('name', "%{$criteria['name']}%");
        }
        
        if (isset($criteria['email']) && $criteria['email'] !== null) {
            $query->setParameter('email', "%{$criteria['email']}%");
        }
        
        if (isset($criteria['addedFrom']) && $criteria['addedFrom'] !== null) {
            $query->setParameter('addedFrom', $criteria['addedFrom']);
        }
        
        if (isset($criteria['registeredAt']) && $criteria['registeredAt'] !== null) {
            $query
                ->setParameter('registeredAtStart', new \DateTime($criteria['registeredAt']))
                ->setParameter('registeredAtEnd', (new \DateTime($criteria['registeredAt']))->modify('tomorrow'));
        }
        
        if (isset($criteria['isActive']) && $criteria['isActive'] !== null) {
            $query->setParameter('isActive', $criteria['isActive']);
        }
        
        if (isset($criteria['sendingCounter']) && $criteria['sendingCounter'] !== null) {
            $query->setParameter('sendingCounter', $criteria['sendingCounter']);
        }
        
        if (isset($criteria['sendingDateTime']) && $criteria['sendingDateTime'] !== null) {
            $query
                ->setParameter('sendingDateTimeStart', new \DateTime($criteria['sendingDateTime']))
                ->setParameter('sendingDateTimeEnd', (new \DateTime($criteria['sendingDateTime']))->modify('tomorrow'));
        }
        
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        
        return $paginator;
    }
    
    public function total(array $criteria)
    {
        $whereClauses = [];
        
        if (isset($criteria['name']) && $criteria['name'] !== null) {
            $whereClauses[] = '(c.name LIKE :name OR c.lastname LIKE :name OR c.middlename LIKE :name)';
        }
        
        if (isset($criteria['email']) && $criteria['email'] !== null) {
            $whereClauses[] = 'c.email LIKE :email';
        }
        
        if (isset($criteria['addedFrom']) && $criteria['addedFrom'] !== null) {
            $whereClauses[] = 'u.addedFrom = :addedFrom';
        }
        
        if (isset($criteria['registeredAt']) && $criteria['registeredAt'] !== null) {
            $whereClauses[] = 'u.registeredAt >= :registeredAtStart AND u.registeredAt < :registeredAtEnd';
        }
        
        if (isset($criteria['isActive']) && $criteria['isActive'] !== null) {
            $whereClauses[] = 'u.isActive = :isActive';
        }
        
        if (isset($criteria['sendingCounter']) && $criteria['sendingCounter'] !== null) {
            $whereClauses[] = 'u.sendingCounter = :sendingCounter';
        }
        
        if (isset($criteria['sendingDateTime']) && $criteria['sendingDateTime'] !== null) {
            $whereClauses[] = 'u.sendingDateTime >= :sendingDateTimeStart AND u.sendingDateTime < :sendingDateTimeEnd';
        }
        
        $where = '';
        if (!empty($whereClauses)) {
            $where = ' AND ' . implode(' AND ', $whereClauses);
        }
        
        $dql   = 'SELECT COUNT(c.id) FROM '
            . Customer::class
            . ' c JOIN '
            . User::class
            . ' u WHERE c.id = u.customerId'
            . $where
            . ' ORDER BY c.lastname, c.name, c.middlename';
        $query = $this->entityManager->createQuery($dql);
        
        if (isset($criteria['name']) && $criteria['name'] !== null) {
            $query->setParameter('name', "%{$criteria['name']}%");
        }
        
        if (isset($criteria['email']) && $criteria['email'] !== null) {
            $query->setParameter('email', "%{$criteria['email']}%");
        }
        
        if (isset($criteria['addedFrom']) && $criteria['addedFrom'] !== null) {
            $query->setParameter('addedFrom', $criteria['addedFrom']);
        }
        
        if (isset($criteria['registeredAt']) && $criteria['registeredAt'] !== null) {
            $query
                ->setParameter('registeredAtStart', new \DateTime($criteria['registeredAt']))
                ->setParameter('registeredAtEnd', (new \DateTime($criteria['registeredAt']))->modify('tomorrow'));
        }
        
        if (isset($criteria['isActive']) && $criteria['isActive'] !== null) {
            $query->setParameter('isActive', $criteria['isActive']);
        }
        
        if (isset($criteria['sendingCounter']) && $criteria['sendingCounter'] !== null) {
            $query->setParameter('sendingCounter', $criteria['sendingCounter']);
        }
        
        if (isset($criteria['sendingDateTime']) && $criteria['sendingDateTime'] !== null) {
            $query
                ->setParameter('sendingDateTimeStart', new \DateTime($criteria['sendingDateTime']))
                ->setParameter('sendingDateTimeEnd', (new \DateTime($criteria['sendingDateTime']))->modify('tomorrow'));
        }
        
        return $query->getSingleScalarResult();
    }
    
    public function find($id)
    {
        return $this->objectRepository->find($id);
    }
    
    public function update(Customer $customer)
    {
        $this->entityManager->flush();
    }
    
    public function delete($entity)
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }
    
    public function listAll(array $criteria = [])
    {
        $whereClauses = [];
    
        if (isset($criteria['name']) && $criteria['name'] !== null) {
            $whereClauses[] = '(c.name LIKE :name OR c.lastname LIKE :name OR c.middlename LIKE :name)';
        }
    
        if (isset($criteria['email']) && $criteria['email'] !== null) {
            $whereClauses[] = 'c.email LIKE :email';
        }
    
        if (isset($criteria['addedFrom']) && $criteria['addedFrom'] !== null) {
            $whereClauses[] = 'u.addedFrom = :addedFrom';
        }
    
        if (isset($criteria['registeredAt']) && $criteria['registeredAt'] !== null) {
            $whereClauses[] = 'u.registeredAt >= :registeredAtStart AND u.registeredAt < :registeredAtEnd';
        }
    
        if (isset($criteria['isActive']) && $criteria['isActive'] !== null) {
            $whereClauses[] = 'u.isActive = :isActive';
        }
    
        if (isset($criteria['sendingCounter']) && $criteria['sendingCounter'] !== null) {
            $whereClauses[] = 'u.sendingCounter = :sendingCounter';
        }
    
        if (isset($criteria['sendingDateTime']) && $criteria['sendingDateTime'] !== null) {
            $whereClauses[] = 'u.sendingDateTime >= :sendingDateTimeStart AND u.sendingDateTime < :sendingDateTimeEnd';
        }
    
        $where = '';
        if (!empty($whereClauses)) {
            $where = ' AND ' . implode(' AND ', $whereClauses);
        }
    
        $dql   = 'SELECT c FROM '
            . Customer::class
            . ' c JOIN '
            . User::class
            . ' u WHERE c.id = u.customerId'
            . $where;
        $query = $this->entityManager->createQuery($dql);
    
        if (isset($criteria['name']) && $criteria['name'] !== null) {
            $query->setParameter('name', "%{$criteria['name']}%");
        }
    
        if (isset($criteria['email']) && $criteria['email'] !== null) {
            $query->setParameter('email', "%{$criteria['email']}%");
        }
    
        if (isset($criteria['addedFrom']) && $criteria['addedFrom'] !== null) {
            $query->setParameter('addedFrom', $criteria['addedFrom']);
        }
    
        if (isset($criteria['registeredAt']) && $criteria['registeredAt'] !== null) {
            $query
                ->setParameter('registeredAtStart', new \DateTime($criteria['registeredAt']))
                ->setParameter('registeredAtEnd', (new \DateTime($criteria['registeredAt']))->modify('tomorrow'));
        }
    
        if (isset($criteria['isActive']) && $criteria['isActive'] !== null) {
            $query->setParameter('isActive', $criteria['isActive']);
        }
    
        if (isset($criteria['sendingCounter']) && $criteria['sendingCounter'] !== null) {
            $query->setParameter('sendingCounter', $criteria['sendingCounter']);
        }
    
        if (isset($criteria['sendingDateTime']) && $criteria['sendingDateTime'] !== null) {
            $query
                ->setParameter('sendingDateTimeStart', new \DateTime($criteria['sendingDateTime']))
                ->setParameter('sendingDateTimeEnd', (new \DateTime($criteria['sendingDateTime']))->modify('tomorrow'));
        }
    
        return $query->getResult();
    }
}
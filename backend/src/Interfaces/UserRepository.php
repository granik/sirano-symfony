<?php

namespace App\Interfaces;


use App\Domain\Entity\Customer\Customer;
use App\Domain\Interactor\User\User;
use App\Domain\Interactor\User\UserRepositoryInterface;
use DateTime;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserRepository implements UserRepositoryInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;
    
    /** @var ObjectRepository */
    private $objectRepository;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager    = $entityManager;
        $this->objectRepository = $this->entityManager->getRepository(User::class);
    }
    
    public function findByLogin($login)
    {
        return $this->objectRepository->findOneBy(['login' => $login, 'isActive' => true]);
    }
    
    public function find($id)
    {
        return $this->objectRepository->find($id);
    }
    
    public function findByCode($code)
    {
        return $this->objectRepository->findOneBy(['activationCode' => $code]);
    }
    
    public function store(User $user): User
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        
        return $user;
    }
    
    public function update(User $user): User
    {
        $this->entityManager->flush();
        
        return $user;
    }
    
    public function findAnyByLogin($login)
    {
        return $this->objectRepository->findOneBy(['login' => $login]);
    }
    
    public function findByCustomer(Customer $customer): ?User
    {
        return $this->objectRepository->findOneBy(['customerId' => $customer->getId()]);
    }
    
    public function delete(User $entity)
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }
    
    /**
     * @param DateTime $sendDateTime
     * @param int      $maxTries
     * @param int      $limit
     *
     * @return User[]
     */
    public function getNotConfirmedUsers(DateTime $sendDateTime, int $maxTries, int $limit): array
    {
        $dql   = 'SELECT c FROM '
            . User::class
            . ' c WHERE c.customerId IS NOT NULL AND c.isActive = 0 AND c.sendingDateTime <= :sendDateTime AND c.sendingCounter < :count ORDER BY c.sendingDateTime, c.sendingCounter';
        $query = $this->entityManager->createQuery($dql)
            ->setParameter('count', $maxTries)
            ->setParameter('sendDateTime', $sendDateTime)
            ->setMaxResults($limit);
        
        return $query->getResult();
    }
}
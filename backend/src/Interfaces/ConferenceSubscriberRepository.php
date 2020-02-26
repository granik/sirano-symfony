<?php

namespace App\Interfaces;


use App\Domain\Entity\Conference\Conference;
use App\Domain\Entity\Conference\ConferenceSubscriber;
use App\Domain\Entity\Conference\ConferenceSubscriberRepositoryInterface;
use App\Domain\Entity\Customer\Customer;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;

final class ConferenceSubscriberRepository implements ConferenceSubscriberRepositoryInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;
    
    /** @var ObjectRepository */
    private $objectRepository;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager    = $entityManager;
        $this->objectRepository = $this->entityManager->getRepository(ConferenceSubscriber::class);
    }
    
    public function store(ConferenceSubscriber $subscriber)
    {
        $this->entityManager->persist($subscriber);
        $this->entityManager->flush();
        
        return $subscriber;
    }
    
    public function findByConferenceAndCustomer(Conference $conference, Customer $customer)
    {
        return $this->objectRepository->findOneBy(['conference' => $conference, 'customer' => $customer]);
    }
    
    public function delete(ConferenceSubscriber $subscriber)
    {
        $this->entityManager->remove($subscriber);
        $this->entityManager->flush();
    }
    
    public function updateVisits(Conference $conference, array $customerIds)
    {
        $subscribers = $this->objectRepository->findBy(['conference' => $conference]);
        
        /** @var ConferenceSubscriber $subscriber */
        foreach ($subscribers as $subscriber) {
            $subscriber->setVisit(in_array($subscriber->getCustomer()->getId(), $customerIds));
        }
        
        $this->entityManager->flush();
    }
    
    public function update(ConferenceSubscriber $subscriber)
    {
        $this->entityManager->flush();
    }
}
<?php

namespace App\Frontend\Repository;


use App\Domain\Entity\Customer\Customer;
use App\Webinar\Webinar;
use App\Webinar\WebinarSubscriber;
use App\Webinar\WebinarSubscriberRepositoryInterface;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;

final class WebinarSubscriberRepository implements WebinarSubscriberRepositoryInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;
    
    /** @var ObjectRepository */
    private $objectRepository;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager    = $entityManager;
        $this->objectRepository = $this->entityManager->getRepository(WebinarSubscriber::class);
    }
    
    public function store(WebinarSubscriber $subscriber)
    {
        $this->entityManager->persist($subscriber);
        $this->entityManager->flush();
        
        return $subscriber;
    }
    
    public function findByWebinarAndCustomer(Webinar $webinar, Customer $customer)
    {
        return $this->objectRepository->findOneBy(['webinar' => $webinar, 'customer' => $customer]);
    }
    
    public function delete(WebinarSubscriber $subscriber)
    {
        $this->entityManager->remove($subscriber);
        $this->entityManager->flush();
    }
    
    public function update(WebinarSubscriber $subscriber)
    {
        $this->entityManager->flush();
        
        return $subscriber;
    }
}
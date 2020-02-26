<?php

namespace App\Domain\Frontend\Interactor;


use App\Domain\Entity\Customer\Customer;
use App\Domain\Entity\Conference\Conference;
use App\Domain\Entity\Conference\ConferenceSubscriber;
use App\Domain\Entity\Conference\ConferenceSubscriberRepositoryInterface;
use App\Interactors\NonExistentEntity;

final class ConferenceSubscriberInteractor
{
    private $subscriberRepository;
    
    /**
     * ConferenceSubscriberInteractor constructor.
     *
     * @param ConferenceSubscriberRepositoryInterface $subscriberRepository
     */
    public function __construct(ConferenceSubscriberRepositoryInterface $subscriberRepository)
    {
        $this->subscriberRepository = $subscriberRepository;
    }
    
    public function create(Conference $conference, Customer $customer)
    {
        $subscriber = new ConferenceSubscriber();
        $subscriber
            ->setConference($conference)
            ->setCustomer($customer);
        
        $this->subscriberRepository->store($subscriber);
        
        return $subscriber;
    }
    
    public function find(Conference $conference, Customer $customer)
    {
        return $this->subscriberRepository->findByConferenceAndCustomer($conference, $customer);
    }
    
    /**
     * @param Conference  $conference
     * @param Customer $customer
     *
     * @return mixed
     * @throws NonExistentEntity
     */
    public function delete(Conference $conference, Customer $customer)
    {
        $subscriber = $this->find($conference, $customer);
        
        if (!$subscriber instanceof ConferenceSubscriber) {
            throw new NonExistentEntity();
        }
    
        return $this->subscriberRepository->delete($subscriber);
    }
}
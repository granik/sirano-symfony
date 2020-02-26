<?php

namespace App\Webinar;


use App\Domain\Entity\Customer\Customer;
use App\Interactors\NonExistentEntity;

final class WebinarSubscriberInteractor
{
    private $subscriberRepository;
    
    /**
     * WebinarSubscriberInteractor constructor.
     *
     * @param WebinarSubscriberRepositoryInterface $subscriberRepository
     */
    public function __construct(WebinarSubscriberRepositoryInterface $subscriberRepository)
    {
        $this->subscriberRepository = $subscriberRepository;
    }
    
    public function create(Webinar $webinar, Customer $customer)
    {
        $subscriber = new WebinarSubscriber();
        $subscriber
            ->setWebinar($webinar)
            ->setCustomer($customer);
        
        $this->subscriberRepository->store($subscriber);
        
        return $subscriber;
    }
    
    public function find(Webinar $webinar, Customer $customer)
    {
        return $this->subscriberRepository->findByWebinarAndCustomer($webinar, $customer);
    }
    
    /**
     * @param Webinar  $webinar
     * @param Customer $customer
     *
     * @return mixed
     * @throws NonExistentEntity
     */
    public function delete(Webinar $webinar, Customer $customer)
    {
        $subscriber = $this->find($webinar, $customer);
        
        if (!$subscriber instanceof WebinarSubscriber) {
            throw new NonExistentEntity();
        }
    
        return $this->subscriberRepository->delete($subscriber);
    }
    
    /**
     * @param Webinar  $webinar
     * @param Customer $customer
     *
     * @return mixed
     * @throws NonExistentEntity
     */
    public function confirmView(Webinar $webinar, Customer $customer)
    {
        $subscriber = $this->find($webinar, $customer);
        
        if (!$subscriber instanceof WebinarSubscriber) {
            throw new NonExistentEntity();
        }
        
        $subscriber->confirmView();
        
        return $this->subscriberRepository->update($subscriber);
    }
}
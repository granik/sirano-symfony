<?php

namespace App\Domain\Entity\Conference;


use App\Domain\Entity\Customer\Customer;

interface ConferenceSubscriberRepositoryInterface
{
    public function store(ConferenceSubscriber $subscriber);
    
    public function findByConferenceAndCustomer(Conference $conference, Customer $customer);
    
    public function delete(ConferenceSubscriber $subscriber);
    
    public function updateVisits(Conference $conference, array $customerIds);
    
    public function update(ConferenceSubscriber $subscriber);
}
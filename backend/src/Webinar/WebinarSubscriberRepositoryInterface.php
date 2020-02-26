<?php

namespace App\Webinar;


use App\Domain\Entity\Customer\Customer;

interface WebinarSubscriberRepositoryInterface
{
    public function store(WebinarSubscriber $subscriber);
    
    public function findByWebinarAndCustomer(Webinar $webinar, Customer $customer);
    
    public function delete(WebinarSubscriber $subscriber);
    
    public function update(WebinarSubscriber $subscriber);
}
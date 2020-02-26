<?php

namespace App\Domain\Entity\Conference\Frontend;


use App\Domain\Entity\Customer\Customer;
use App\Domain\Entity\Direction\Direction;
use DateTime;

interface ConferenceRepositoryInterface
{
    public function list(int $page, int $perPage, DateTime $tillDate, $direction);
    
    public function archive(int $page, int $perPage, ?DateTime $tillDate, $direction);
    
    public function find($id);
    
    public function dashboard(Customer $customer);
    
    public function getProfileConferences(Customer $customer, int $page, int $perPage, DateTime $tillDate);
    
    public function getCustomerScore(Customer $customer);
    
    public function listComingSoon(int $limit, ?Direction $direction);
    
    public function getMaxScore();
}
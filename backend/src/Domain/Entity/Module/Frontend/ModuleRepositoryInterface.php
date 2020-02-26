<?php

namespace App\Domain\Entity\Module\Frontend;


use App\Domain\Entity\Customer\Customer;
use App\Domain\Entity\Module\Module;

interface ModuleRepositoryInterface
{
    public function list(int $page, int $perPage, $direction, $category);
    
    public function find($id): ?Module;
    
    public function dashboard(Customer $customer);
    
    public function getProfileModules(Customer $customer, int $page, int $perPage);
    
    public function getCustomerScore(Customer $customer);
    
    public function getMaxScore();
}
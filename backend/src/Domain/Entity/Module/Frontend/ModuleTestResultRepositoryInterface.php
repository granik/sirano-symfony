<?php


namespace App\Domain\Entity\Module\Frontend;


use App\Domain\Entity\Customer\Customer;
use App\Domain\Entity\Module\Module;
use App\Domain\Entity\Module\ModuleTestResult;

interface ModuleTestResultRepositoryInterface
{
    public function findByModuleAndCustomer(Module $module, Customer $customer);
    
    public function store(ModuleTestResult $result);
}
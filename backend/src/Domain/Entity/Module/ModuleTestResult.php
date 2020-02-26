<?php


namespace App\Domain\Entity\Module;


use App\Domain\Entity\Customer\Customer;

class ModuleTestResult
{
    /** @var Module */
    private $module;
    
    /** @var Customer */
    private $customer;
    
    /** @var int */
    private $correctAnswers;
    
    public static function create(Module $module, Customer $customer, int $correctAnswerNumber)
    {
        $result = new self();
        
        return $result
            ->setModule($module)
            ->setCustomer($customer)
            ->setCorrectAnswers($correctAnswerNumber);
    }

    /**
     * @return Module
     */
    public function getModule(): Module
    {
        return $this->module;
    }
    
    /**
     * @param Module $module
     *
     * @return ModuleTestResult
     */
    public function setModule(Module $module): ModuleTestResult
    {
        $this->module = $module;
        return $this;
    }
    
    /**
     * @return Customer
     */
    public function getCustomer(): Customer
    {
        return $this->customer;
    }
    
    /**
     * @param Customer $customer
     *
     * @return ModuleTestResult
     */
    public function setCustomer(Customer $customer): ModuleTestResult
    {
        $this->customer = $customer;
        return $this;
    }
    
    /**
     * @return int
     */
    public function getCorrectAnswers(): int
    {
        return $this->correctAnswers;
    }
    
    /**
     * @param int $correctAnswers
     *
     * @return ModuleTestResult
     */
    public function setCorrectAnswers(int $correctAnswers): ModuleTestResult
    {
        $this->correctAnswers = $correctAnswers;
        return $this;
    }
}
<?php


namespace App\Tests\Builders;


use App\Domain\Entity\Customer\Customer;

final class CustomerBuilder
{
    private $id        = 1;
    private $email     = 'user@example.com';
    private $name      = 'Name';
    private $lastname  = 'Lastname';
    private $phone     = '+7(000)000-00-00';
    private $cityName  = 'City';
    private $specialty = 'Specialty';
    
    public static function instance()
    {
        return new self();
    }
    
    public function withId($id)
    {
        $this->id = $id;
        
        return $this;
    }
    
    public function build()
    {
        $customer = new Customer();
        $customer
            ->setLastname($this->lastname)
            ->setName($this->name)
            ->setCityName($this->cityName)
            ->setEmail($this->email)
            ->setPhone($this->phone)
            ->setSpecialty($this->specialty);
        
        return $customer;
    }
}
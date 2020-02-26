<?php

namespace App\Webinar;


use App\Domain\Entity\Customer\Customer;

class WebinarSubscriber
{
    /** @var int */
    private $id;
    /** @var Customer */
    private $customer;
    /** @var Webinar */
    private $webinar;
    /** @var int|null */
    private $confirmNumber;
    
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
    
    /**
     * @param int $id
     *
     * @return WebinarSubscriber
     */
    public function setId(int $id): WebinarSubscriber
    {
        $this->id = $id;
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
     * @return WebinarSubscriber
     */
    public function setCustomer(Customer $customer): WebinarSubscriber
    {
        $this->customer = $customer;
        return $this;
    }
    
    /**
     * @return Webinar
     */
    public function getWebinar(): Webinar
    {
        return $this->webinar;
    }
    
    /**
     * @param Webinar $webinar
     *
     * @return WebinarSubscriber
     */
    public function setWebinar(Webinar $webinar): WebinarSubscriber
    {
        $this->webinar = $webinar;
        return $this;
    }
    
    /**
     * @return int
     */
    public function getConfirmNumber(): int
    {
        return (int)$this->confirmNumber;
    }
    
    /**
     * @param int|null $confirmNumber
     *
     * @return WebinarSubscriber
     */
    public function setConfirmNumber(?int $confirmNumber): WebinarSubscriber
    {
        $this->confirmNumber = $confirmNumber;
        return $this;
    }
    
    public function confirmView()
    {
        if ($this->confirmNumber < $this->webinar->getMaxConfirmNumber()) {
            $this->confirmNumber++;
        }
    }
}
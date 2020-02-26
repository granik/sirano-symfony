<?php

namespace App\Domain\Entity\Conference;


use App\Domain\Entity\Customer\Customer;

class ConferenceSubscriber
{
    /**
     * @var int
     */
    private $id;
    
    /**
     * @var Customer
     */
    private $customer;
    
    /**
     * @var Conference
     */
    private $conference;
    
    /**
     * @var bool
     */
    private $visit = false;
    
    /**
     * @var int|null
     */
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
     * @return ConferenceSubscriber
     */
    public function setId(int $id): ConferenceSubscriber
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
     * @return ConferenceSubscriber
     */
    public function setCustomer(Customer $customer): ConferenceSubscriber
    {
        $this->customer = $customer;
        return $this;
    }
    
    /**
     * @return Conference
     */
    public function getConference(): Conference
    {
        return $this->conference;
    }
    
    /**
     * @param Conference $conference
     *
     * @return ConferenceSubscriber
     */
    public function setConference(Conference $conference): ConferenceSubscriber
    {
        $this->conference = $conference;
        return $this;
    }
    
    /**
     * @return bool
     */
    public function isVisit(): bool
    {
        return $this->visit;
    }
    
    /**
     * @param bool $visit
     *
     * @return ConferenceSubscriber
     */
    public function setVisit(bool $visit): ConferenceSubscriber
    {
        $this->visit = $visit;
        return $this;
    }
    
    /**
     * @return int|null
     */
    public function getConfirmNumber(): ?int
    {
        return $this->confirmNumber;
    }
    
    /**
     * @param int|null $confirmNumber
     *
     * @return ConferenceSubscriber
     */
    public function setConfirmNumber(?int $confirmNumber): ConferenceSubscriber
    {
        $this->confirmNumber = $confirmNumber;
        return $this;
    }
}
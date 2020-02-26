<?php

namespace App\Domain\Entity\Conference\DTO;


final class ConferenceSubscriberDto
{
    /**
     * @var string
     */
    public $customerName;
    
    /**
     * @var string
     */
    public $customerEmail;
    
    /**
     * @var bool
     */
    public $visit;
    
    /**
     * @var int
     */
    public $customerId;
}
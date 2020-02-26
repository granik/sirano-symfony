<?php


namespace App\Domain\Entity\Customer\Frontend\DTO;


final class CustomerProfileDto
{
    /**
     * @var string
     */
    public $name;
    
    /**
     * @var string
     */
    public $avatar;
    
    /**
     * @var string
     */
    public $mainSpecialty;
    
    /**
     * @var string|null
     */
    public $additionalSpecialty;
}
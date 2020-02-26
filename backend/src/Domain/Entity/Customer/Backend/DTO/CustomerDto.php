<?php

namespace App\Domain\Entity\Customer\Backend\DTO;


final class CustomerDto
{
    public $id;
    public $name;
    public $middlename;
    public $lastname;
    public $phone;
    public $email;
    public $password;
    public $directionId;
    public $cityName;
    
    /**
     * @var int
     */
    public $mainSpecialtyId;
    
    /**
     * @var int|null
     */
    public $additionalSpecialtyId;
    
    public $admin = false;
    
    /**
     * @var string
     */
    public $registeredAt;
    
    /**
     * @var string
     */
    public $sendingDateTime;
    
    /**
     * @var string
     */
    public $addedFrom;
    
    /**
     * @var string
     */
    public $isActive;
    
    /**
     * @var int|null
     */
    public $sendingCounter;
    /**
     * @var string|null
     */
    public $kladrId;
    /**
     * @var string;
     */
    public $country;
    /**
     * @var string
     */
    public $fullCityName;
}
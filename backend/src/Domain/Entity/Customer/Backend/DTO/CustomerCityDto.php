<?php


namespace App\Domain\Entity\Customer\Backend\DTO;


final class CustomerCityDto
{
    /**
     * @var string|null
     */
    public $kladrId;
    
    /**
     * @var string
     */
    public $country;
    
    /**
     * @var string
     */
    public $name;
    
    /**
     * @var string
     */
    public $fullName;
}
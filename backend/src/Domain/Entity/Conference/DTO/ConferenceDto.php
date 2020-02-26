<?php

namespace App\Domain\Entity\Conference\DTO;


use App\Domain\Entity\City;
use App\Domain\Entity\Direction\Direction;
use DateTime;

final class ConferenceDto
{
    /**
     * @var int
     */
    public $id;
    
    /**
     * @var string
     */
    public $name;
    
    /**
     * @var Direction
     */
    public $direction;
    
    /**
     * @var City
     */
    public $city;
    
    /**
     * @var string
     */
    public $address;
    
    /**
     * @var DateTime
     */
    public $startDateTime;
    
    /**
     * @var DateTime
     */
    public $endDateTime;
    
    /**
     * @var int
     */
    public $score;
    
    /**
     * @var string|null
     */
    public $description;
    
    /**
     * @var boolean
     */
    public $isActive;
    
    /**
     * @var []
     */
    public $programs;
    
    /**
     * @var bool
     */
    public $isArchive;
    
    /**
     * @var string
     */
    public $cityName;
    
    /**
     * @var int|null
     */
    public $series;
    
    /**
     * @var string
     */
    public $startDate;
    
    /**
     * @var string
     */
    public $startTime;
    
    /**
     * @var string
     */
    public $directionName;
    
    /**
     * @var bool
     */
    public $isSubscribed;
}
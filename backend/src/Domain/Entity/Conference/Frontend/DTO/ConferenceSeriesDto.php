<?php


namespace App\Domain\Entity\Conference\Frontend\DTO;


final class ConferenceSeriesDto
{
    /**
     * @var string
     */
    public $name;
    
    /**
     * @var string
     */
    public $directionName;
    
    /**
     * @var string
     */
    public $image;
    
    /**
     * @var array
     */
    public $conferences;
}
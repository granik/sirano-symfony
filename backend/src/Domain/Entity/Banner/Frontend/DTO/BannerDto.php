<?php


namespace App\Domain\Entity\Banner\Frontend\DTO;


final class BannerDto
{
    /**
     * @var string|null
     */
    public $link;
    
    /**
     * @var string
     */
    public $desktopImage;
    
    /**
     * @var string
     */
    public $mobileImage;
    
    /**
     * @var string|null
     */
    public $backgroundColor;
}
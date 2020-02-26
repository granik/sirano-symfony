<?php


namespace App\Domain\Entity\Banner\Backend\DTO;


use App\Domain\Backend\Interactor\File;

final class BannerDto
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
     * @var string|null
     */
    public $link;
    
    /**
     * @var int
     */
    public $number;
    
    /**
     * @var string
     */
    public $desktopImage;
    
    /**
     * @var File
     */
    public $desktopImageFile;
    
    /**
     * @var string
     */
    public $mobileImage;
    
    /**
     * @var File
     */
    public $mobileImageFile;
    
    /**
     * @var bool
     */
    public $isActive;
    
    /**
     * @var string
     */
    public $backgroundColor;
}
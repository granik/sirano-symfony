<?php

namespace App\Domain\Entity\Direction\DTO;


use App\Domain\Backend\Interactor\File;
use App\Domain\Entity\Direction\Backend\DTO\DirectionCategoryDto;

final class DirectionDto
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
     * @var string
     */
    public $icon;
    
    /**
     * @var string
     */
    public $image;
    
    /**
     * @var boolean
     */
    public $isActive;
    
    /**
     * @var File
     */
    public $iconFile;
    
    /**
     * @var File
     */
    public $imageFile;
    
    /**
     * @var bool
     */
    public $isMainPage;
    
    /**
     * @var int|null
     */
    public $number;
    
    /**
     * @var DirectionCategoryDto[]
     */
    public $categories = [];
    
    /**
     * @var File
     */
    public $activeIconFile;
    
    /**
     * @var string
     */
    public $activeIcon;
}
<?php


namespace App\Domain\Entity\PresidiumMember\Backend\DTO;


use App\Domain\Backend\Interactor\File;

final class PresidiumMemberDto
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
    public $middlename;
    
    /**
     * @var string
     */
    public $lastname;
    
    /**
     * @var string
     */
    public $image;
    
    /**
     * @var File
     */
    public $imageFile;
    
    /**
     * @var string
     */
    public $description;
    
    /**
     * @var bool
     */
    public $isActive;
    
    /**
     * @var int
     */
    public $number;
}
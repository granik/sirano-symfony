<?php

namespace App\Domain\Entity\Module\Backend\DTO;


use App\Domain\Entity\Direction\Direction;

final class ModuleDto
{
    /** @var int */
    public $id;
    
    /** @var string Название */
    public $name;
    
    /** @var \App\Domain\Entity\Direction\Direction Направление */
    public $direction;
    
    /** @var int Порядковый номер */
    public $number;
    
    public $slides = [];
    
    /** @var string Видео к модулю (youtube) */
    public $youtubeCode;
    
    public $articles = [];
    
    /** @var boolean */
    public $isActive;
    
    public $category;
}
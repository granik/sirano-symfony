<?php

namespace App\Domain\Entity\Module\Backend\DTO;


use App\Domain\Backend\Interactor\File;

final class ModuleSlideDto
{
    /** @var int */
    public $id;
    
    /** @var string Заголовок */
    public $name;
    
    /** @var string */
    public $image;
    
    /** @var File */
    public $imageFile;
    
    /** @var int Порядковый номер */
    public $number;
}
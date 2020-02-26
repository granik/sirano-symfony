<?php


namespace App\Domain\Entity\Article\Backend\DTO;


use App\Domain\Backend\Interactor\File;
use App\Domain\Entity\Direction\Direction;

final class ArticleDto
{
    /** @var int */
    public $id;
    
    /** @var string Название */
    public $name;
    
    /** @var Direction Направление */
    public $direction;
    
    /** @var string Автор */
    public $author;
    
    /** @var string Материал */
    public $file;
    
    /** @var File */
    public $fileFile;
    
    /** @var boolean Опубликован/не опубликован */
    public $isActive;
    
    public $category;
}
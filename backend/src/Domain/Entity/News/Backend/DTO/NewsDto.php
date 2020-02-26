<?php


namespace App\Domain\Entity\News\Backend\DTO;


use App\Domain\Backend\Interactor\File;
use App\Domain\Entity\Direction\Direction;

final class NewsDto
{
    /**
     * @var int
     */
    public $id;
    
    /**
     * @var string Название
     */
    public $name;
    
    /**
     * @var Direction Направление
     */
    public $direction;
    
    /**
     * @var \DateTime Дата публикации
     */
    public $createdAt;
    
    /**
     * @var string|null Изображение анонса
     */
    public $announceImage;
    
    /**
     * @var File|null
     */
    public $announceImageFile;
    
    /**
     * @var string Изображение новости
     */
    public $image;
    
    /**
     * @var File
     */
    public $imageFile;
    
    /**
     * @var string Текст новости
     */
    public $text;
    
    /**
     * @var boolean
     */
    public $isActive;
    
    /**
     * @var string
     */
    public $createdAtString;
}
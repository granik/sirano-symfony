<?php


namespace App\Domain\Entity\Article\Frontend\DTO;


final class ArticleDto
{
    /**
     * @var string
     */
    public $name;
    
    /**
     * @var string
     */
    public $author;
    
    /**
     * @var string
     */
    public $file;
    
    /**
     * @var int
     */
    public $fileSize;
    
    /**
     * @var string
     */
    public $direction;
    
    /**
     * @var string
     */
    public $category;
}
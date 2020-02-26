<?php

namespace App\Domain\Entity\Module\Frontend\DTO;


final class ModuleDto
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
     * @var string Направление
     */
    public $directionName;
    
    /**
     * @var int Порядковый номер
     */
    public $number;
    
    /**
     * @var string Видео к модулю (youtube)
     */
    public $youtubeCode;
    
    /**
     * @var bool
     */
    public $hasTest = false;
    
    public $slides = [];
    
    public $articles = [];
    
    /**
     * @var int
     */
    public $correctAnswers = 0;
    
    /**
     * @var bool
     */
    public $isTested = false;
    
    /**
     * @var string
     */
    public $testName;
    
    /**
     * @var bool
     */
    public $isPassed = false;
    
    /**
     * @var string
     */
    public $category;
}
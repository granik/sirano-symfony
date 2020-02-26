<?php

namespace App\Domain\Entity\Module;


use App\Domain\Entity\Article\Article;
use App\Domain\Entity\Direction\Category;
use App\Domain\Entity\Direction\Direction;

class Module
{
    /** @var int */
    private $id;
    
    /** @var string Название */
    private $name;
    
    /** @var Direction Направление */
    private $direction;
    
    /** @var Category|null */
    private $category;
    
    /** @var int Порядковый номер */
    private $number;
    
    /** @var ModuleSlide[] */
    private $slides = [];
    
    /** @var string|null Видео к модулю (youtube) */
    private $youtubeCode;
    
    /** @var Article[] Материалы по теме модуля */
    private $articles = [];
    
    /** @var boolean */
    private $isActive;
    
    /** @var ModuleTest|null */
    private $test;
    
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
    
    /**
     * @param int $id
     *
     * @return Module
     */
    public function setId(int $id): Module
    {
        $this->id = $id;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
    
    /**
     * @param string $name
     *
     * @return Module
     */
    public function setName(string $name): Module
    {
        $this->name = $name;
        return $this;
    }
    
    /**
     * @return Direction
     */
    public function getDirection(): Direction
    {
        return $this->direction;
    }
    
    /**
     * @param Direction $direction
     *
     * @return Module
     */
    public function setDirection(Direction $direction): Module
    {
        $this->direction = $direction;
        return $this;
    }
    
    /**
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }
    
    /**
     * @param int $number
     *
     * @return Module
     */
    public function setNumber(int $number): Module
    {
        $this->number = $number;
        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getYoutubeCode(): ?string
    {
        return $this->youtubeCode;
    }
    
    /**
     * @param string|null $youtubeCode
     *
     * @return Module
     */
    public function setYoutubeCode(?string $youtubeCode): Module
    {
        $this->youtubeCode = $youtubeCode;
        return $this;
    }
    
    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }
    
    /**
     * @param bool $isActive
     *
     * @return Module
     */
    public function setIsActive(bool $isActive): Module
    {
        $this->isActive = $isActive;
        return $this;
    }
    
    /**
     * @return ModuleSlide[]
     */
    public function getSlides(): array
    {
        return $this->slides;
    }
    
    /**
     * @param ModuleSlide[] $slides
     *
     * @return Module
     */
    public function setSlides(array $slides): Module
    {
        $this->slides = $slides;
        return $this;
    }
    
    /**
     * @return ModuleTest|null
     */
    public function getTest(): ?ModuleTest
    {
        return $this->test;
    }
    
    /**
     * @param ModuleTest|null $test
     *
     * @return Module
     */
    public function setTest(?ModuleTest $test): Module
    {
        $this->test = $test;
        return $this;
    }
    
    /**
     * @return Article[]
     */
    public function getArticles(): array
    {
        return $this->articles;
    }
    
    /**
     * @param Article[] $articles
     *
     * @return Module
     */
    public function setArticles(array $articles): Module
    {
        $this->articles = $articles;
        
        return $this;
    }
    
    /**
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }
    
    /**
     * @param Category|null $category
     *
     * @return Module
     */
    public function setCategory(?Category $category): Module
    {
        $this->category = $category;
        return $this;
    }
}
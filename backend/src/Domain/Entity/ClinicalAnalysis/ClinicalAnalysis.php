<?php


namespace App\Domain\Entity\ClinicalAnalysis;


use App\Domain\Entity\Article\Article;
use App\Domain\Entity\Direction\Category;
use App\Domain\Entity\Direction\Direction;
use App\Domain\Entity\Module\Module;

class ClinicalAnalysis
{
    /** @var int */
    private $id;
    
    /** @var string Название */
    private $name;
    
    /** @var Direction Направление */
    private $direction;
    
    /** @var Category|null */
    private $category;
    
    /** @var Module Модуль */
    private $module;
    
    /** @var int Порядковый номер */
    private $number;
    
    /** @var ClinicalAnalysisSlide[] Слайды для слайдера */
    private $slides = [];
    
    /** @var Article[] Материалы по теме модуля */
    private $articles = [];
    
    /** @var string|null */
    private $companyEmail;
    
    /** @var string|null */
    private $lecturerEmail;
    
    /** @var boolean */
    private $isActive;
    
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
     * @return ClinicalAnalysis
     */
    public function setId(int $id): ClinicalAnalysis
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
     * @return ClinicalAnalysis
     */
    public function setName(string $name): ClinicalAnalysis
    {
        $this->name = $name;
        return $this;
    }
    
    /**
     * @return \App\Domain\Entity\Direction\Direction
     */
    public function getDirection(): Direction
    {
        return $this->direction;
    }
    
    /**
     * @param \App\Domain\Entity\Direction\Direction $direction
     *
     * @return ClinicalAnalysis
     */
    public function setDirection(Direction $direction): ClinicalAnalysis
    {
        $this->direction = $direction;
        return $this;
    }
    
    /**
     * @return Module
     */
    public function getModule(): Module
    {
        return $this->module;
    }
    
    /**
     * @param Module $module
     *
     * @return ClinicalAnalysis
     */
    public function setModule(Module $module): ClinicalAnalysis
    {
        $this->module = $module;
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
     * @return ClinicalAnalysis
     */
    public function setNumber(int $number): ClinicalAnalysis
    {
        $this->number = $number;
        return $this;
    }
    
    /**
     * @return ClinicalAnalysisSlide[]
     */
    public function getSlides(): array
    {
        return $this->slides;
    }
    
    /**
     * @param ClinicalAnalysisSlide[] $slides
     *
     * @return ClinicalAnalysis
     */
    public function setSlides(array $slides): ClinicalAnalysis
    {
        $this->slides = $slides;
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
     * @return ClinicalAnalysis
     */
    public function setArticles(array $articles): ClinicalAnalysis
    {
        $this->articles = $articles;
        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getCompanyEmail(): ?string
    {
        return $this->companyEmail;
    }
    
    /**
     * @param string|null $companyEmail
     *
     * @return ClinicalAnalysis
     */
    public function setCompanyEmail(?string $companyEmail): ClinicalAnalysis
    {
        $this->companyEmail = $companyEmail;
        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getLecturerEmail(): ?string
    {
        return $this->lecturerEmail;
    }
    
    /**
     * @param string|null $lecturerEmail
     *
     * @return ClinicalAnalysis
     */
    public function setLecturerEmail(?string $lecturerEmail): ClinicalAnalysis
    {
        $this->lecturerEmail = $lecturerEmail;
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
     * @return ClinicalAnalysis
     */
    public function setIsActive(bool $isActive): ClinicalAnalysis
    {
        $this->isActive = $isActive;
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
     * @return ClinicalAnalysis
     */
    public function setCategory(?Category $category): ClinicalAnalysis
    {
        $this->category = $category;
        return $this;
    }
}
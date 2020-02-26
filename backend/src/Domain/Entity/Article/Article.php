<?php


namespace App\Domain\Entity\Article;


use App\Domain\Entity\Direction\Category;
use App\Domain\Entity\Direction\Direction;

class Article
{
    /** @var int */
    private $id;
    
    /** @var string Название */
    private $name;
    
    /** @var Direction Направление */
    private $direction;
    
    /** @var Category|null */
    private $category;
    
    /** @var string Автор */
    private $author;
    
    /** @var string|null Материал */
    private $file;
    
    /** @var boolean Опубликован/не опубликован */
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
     * @return Article
     */
    public function setId(int $id): Article
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
     * @return Article
     */
    public function setName(string $name): Article
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
     * @return Article
     */
    public function setDirection(Direction $direction): Article
    {
        $this->direction = $direction;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }
    
    /**
     * @param string $author
     *
     * @return Article
     */
    public function setAuthor(string $author): Article
    {
        $this->author = $author;
        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getFile(): ?string
    {
        return $this->file;
    }
    
    /**
     * @param string $file
     *
     * @return Article
     */
    public function setFile(string $file): Article
    {
        $this->file = $file;
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
     * @return Article
     */
    public function setIsActive(bool $isActive): Article
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
     * @return Article
     */
    public function setCategory(?Category $category): Article
    {
        $this->category = $category;
        return $this;
    }
}
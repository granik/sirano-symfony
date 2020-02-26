<?php

namespace App\Domain\Entity\Direction;


class Direction
{
    /** @var int */
    private $id;
    
    /** @var string */
    private $name;
    
    /** @var string|null */
    private $icon;
    
    /** @var string|null */
    private $activeIcon;
    
    /** @var string */
    private $image;
    
    /** @var boolean */
    private $isActive;
    
    /** @var boolean */
    private $isMainPage;
    
    /** @var int|null Порядковый номер */
    private $number;
    
    /** @var Category[] */
    private $categories = [];
    
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
     * @return Direction
     */
    public function setId(int $id): Direction
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
     * @return Direction
     */
    public function setName(string $name): Direction
    {
        $this->name = $name;
        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getIcon(): ?string
    {
        return $this->icon;
    }
    
    /**
     * @param string|null $icon
     *
     * @return Direction
     */
    public function setIcon(?string $icon): Direction
    {
        $this->icon = $icon;
        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getImage(): ?string
    {
        return $this->image;
    }
    
    /**
     * @param string $image
     *
     * @return Direction
     */
    public function setImage(string $image): Direction
    {
        $this->image = $image;
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
     * @return Direction
     */
    public function setIsActive(bool $isActive): Direction
    {
        $this->isActive = $isActive;
        return $this;
    }
    
    /**
     * @return bool
     */
    public function isMainPage(): bool
    {
        return $this->isMainPage;
    }
    
    /**
     * @param bool $isMainPage
     *
     * @return Direction
     */
    public function setIsMainPage(bool $isMainPage): Direction
    {
        $this->isMainPage = $isMainPage;
        return $this;
    }
    
    /**
     * @return int|null
     */
    public function getNumber(): ?int
    {
        return $this->number;
    }
    
    /**
     * @param int|null $number
     *
     * @return Direction
     */
    public function setNumber(?int $number): Direction
    {
        $this->number = $number;
        return $this;
    }
    
    /**
     * @return Category[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }
    
    /**
     * @param Category[] $categories
     *
     * @return Direction
     */
    public function setCategories(array $categories): Direction
    {
        $this->categories = $categories;
        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getActiveIcon(): ?string
    {
        return $this->activeIcon;
    }
    
    /**
     * @param string|null $activeIcon
     *
     * @return Direction
     */
    public function setActiveIcon(?string $activeIcon): Direction
    {
        $this->activeIcon = $activeIcon;
        return $this;
    }
}
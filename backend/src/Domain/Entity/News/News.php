<?php


namespace App\Domain\Entity\News;


use App\Domain\Entity\Direction\Direction;

class News
{
    /** @var int */
    private $id;
    
    /** @var string Название */
    private $name;
    
    /** @var \App\Domain\Entity\Direction\Direction Направление */
    private $direction;
    
    /** @var \DateTime Дата публикации */
    private $createdAt;
    
    /** @var string|null Изображение анонса */
    private $announceImage;
    
    /** @var string|null Изображение новости */
    private $image;
    
    /** @var string Текст новости */
    private $text;
    
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
     * @return News
     */
    public function setId(int $id): News
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
     * @return News
     */
    public function setName(string $name): News
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
     * @return News
     */
    public function setDirection(Direction $direction): News
    {
        $this->direction = $direction;
        return $this;
    }
    
    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
    
    /**
     * @param \DateTime $createdAt
     *
     * @return News
     */
    public function setCreatedAt(\DateTime $createdAt): News
    {
        $this->createdAt = $createdAt;
        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getAnnounceImage(): ?string
    {
        return $this->announceImage;
    }
    
    /**
     * @param string|null $announceImage
     *
     * @return News
     */
    public function setAnnounceImage(?string $announceImage): News
    {
        $this->announceImage = $announceImage;
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
     * @return News
     */
    public function setImage(string $image): News
    {
        $this->image = $image;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }
    
    /**
     * @param string $text
     *
     * @return News
     */
    public function setText(string $text): News
    {
        $this->text = $text;
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
     * @return News
     */
    public function setIsActive(bool $isActive): News
    {
        $this->isActive = $isActive;
        return $this;
    }
}
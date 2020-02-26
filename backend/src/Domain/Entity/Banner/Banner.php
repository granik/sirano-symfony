<?php


namespace App\Domain\Entity\Banner;


class Banner
{
    /** @var int */
    private $id;
    
    /** @var string Название */
    private $name;
    
    /** @var string|null Ссылка */
    private $link;
    
    /** @var string|null Изображение для десктопа */
    private $desktopImage;
    
    /** @var string|null Изображение для мобильного */
    private $mobileImage;
    
    /** @var int Порядковый номер */
    private $number;
    
    /** @var boolean */
    private $isActive;
    
    /** @var string|null */
    private $backgroundColor;
    
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
     * @return Banner
     */
    public function setId(int $id): Banner
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
     * @return Banner
     */
    public function setName(string $name): Banner
    {
        $this->name = $name;
        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getLink(): ?string
    {
        return $this->link;
    }
    
    /**
     * @param string|null $link
     *
     * @return Banner
     */
    public function setLink(?string $link): Banner
    {
        $this->link = $link;
        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getDesktopImage(): ?string
    {
        return $this->desktopImage;
    }
    
    /**
     * @param string $desktopImage
     *
     * @return Banner
     */
    public function setDesktopImage(string $desktopImage): Banner
    {
        $this->desktopImage = $desktopImage;
        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getMobileImage(): ?string
    {
        return $this->mobileImage;
    }
    
    /**
     * @param string $mobileImage
     *
     * @return Banner
     */
    public function setMobileImage(string $mobileImage): Banner
    {
        $this->mobileImage = $mobileImage;
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
     * @return Banner
     */
    public function setNumber(int $number): Banner
    {
        $this->number = $number;
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
     * @return Banner
     */
    public function setIsActive(bool $isActive): Banner
    {
        $this->isActive = $isActive;
        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getBackgroundColor(): ?string
    {
        return $this->backgroundColor;
    }
    
    /**
     * @param string|null $backgroundColor
     *
     * @return Banner
     */
    public function setBackgroundColor(?string $backgroundColor): Banner
    {
        $this->backgroundColor = $backgroundColor;
        return $this;
    }
}
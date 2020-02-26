<?php


namespace App\Domain\Entity\AdvertBanner;


class AdvertBanner
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
     * @return AdvertBanner
     */
    public function setId(int $id): AdvertBanner
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
     * @return AdvertBanner
     */
    public function setName(string $name): AdvertBanner
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
     * @return AdvertBanner
     */
    public function setLink(?string $link): AdvertBanner
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
     * @return AdvertBanner
     */
    public function setDesktopImage(string $desktopImage): AdvertBanner
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
     * @return AdvertBanner
     */
    public function setMobileImage(string $mobileImage): AdvertBanner
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
     * @return AdvertBanner
     */
    public function setNumber(int $number): AdvertBanner
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
     * @return AdvertBanner
     */
    public function setIsActive(bool $isActive): AdvertBanner
    {
        $this->isActive = $isActive;
        return $this;
    }
}
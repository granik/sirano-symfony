<?php

namespace App\Webinar;


class WebinarReport
{
    /** @var int */
    private $id;
    
    /** @var Webinar */
    private $webinar;
    
    /** @var string Подзаголовок */
    private $subtitle;
    
    /** @var string */
    private $youtubeCode;
    
    /** @var string */
    private $description;
    
    /** @var string|null Изображение анонса */
    private $announceImage;
    
    /** @var string|null Изображение вебинара */
    private $image;
    
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
     * @return WebinarReport
     */
    public function setId(int $id): WebinarReport
    {
        $this->id = $id;
        return $this;
    }
    
    /**
     * @return Webinar
     */
    public function getWebinar(): Webinar
    {
        return $this->webinar;
    }
    
    /**
     * @param Webinar $webinar
     *
     * @return WebinarReport
     */
    public function setWebinar(Webinar $webinar): WebinarReport
    {
        $this->webinar = $webinar;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getSubtitle(): string
    {
        return $this->subtitle;
    }
    
    /**
     * @param string $subtitle
     *
     * @return WebinarReport
     */
    public function setSubtitle(string $subtitle): WebinarReport
    {
        $this->subtitle = $subtitle;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getYoutubeCode(): string
    {
        return $this->youtubeCode;
    }
    
    /**
     * @param string $youtubeCode
     *
     * @return WebinarReport
     */
    public function setYoutubeCode(string $youtubeCode): WebinarReport
    {
        $this->youtubeCode = $youtubeCode;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }
    
    /**
     * @param string $description
     *
     * @return WebinarReport
     */
    public function setDescription(string $description): WebinarReport
    {
        $this->description = $description;
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
     * @return WebinarReport
     */
    public function setAnnounceImage(?string $announceImage): WebinarReport
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
     * @param string|null $image
     *
     * @return WebinarReport
     */
    public function setImage(?string $image): WebinarReport
    {
        $this->image = $image;
        return $this;
    }
}
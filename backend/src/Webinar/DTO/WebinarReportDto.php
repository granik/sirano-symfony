<?php

namespace App\Webinar\DTO;


use App\Domain\Backend\Interactor\File;

final class WebinarReportDto
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
     * @var string|null Тема
     */
    public $subject;
    
    /**
     * @var string Направление
     */
    public $directionName;
    
    /**
     * @var string
     */
    public $startDate;
    
    /**
     * @var string
     */
    public $startTime;
    
    /**
     * @var string Подзаголовок
     */
    public $subtitle;
    
    /**
     * @var string
     */
    public $youtubeCode;
    
    /**
     * @var string
     */
    public $description;
    
    /**
     * @var File|null
     */
    public $imageFile;
    
    /**
     * @var File|null
     */
    public $announceImageFile;
    
    /**
     * @var string
     */
    public $announceImage;
    
    /**
     * @var string
     */
    public $image;
}
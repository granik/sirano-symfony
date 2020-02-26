<?php


namespace App\Domain\Entity\ClinicalAnalysis\Backend\DTO;


use App\Domain\Entity\Article\Article;
use App\Domain\Entity\Direction\Direction;
use App\Domain\Entity\Module\Module;

final class ClinicalAnalysisDto
{
    /** @var int */
    public $id;
    
    /** @var string Название */
    public $name;
    
    /** @var \App\Domain\Entity\Direction\Direction Направление */
    public $direction;
    
    /** @var Module Модуль */
    public $module;
    
    /** @var int Порядковый номер */
    public $number;
    
    /** @var ClinicalAnalysisSlide[] Слайды для слайдера */
    public $slides = [];
    
    /** @var Article[] Материалы по теме модуля */
    public $articles = [];
    
    /** @var string|null */
    public $companyEmail;
    
    /** @var string|null */
    public $lecturerEmail;
    
    /** @var boolean */
    public $isActive;
    
    public $category;
}
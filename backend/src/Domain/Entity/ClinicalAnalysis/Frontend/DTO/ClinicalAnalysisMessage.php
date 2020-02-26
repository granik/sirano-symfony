<?php


namespace App\Domain\Entity\ClinicalAnalysis\Frontend\DTO;


final class ClinicalAnalysisMessage
{
    public $to;
    public $name;
    public $direction;
    public $subscriberName;
    public $subscriberEmail;
    public $text;
}
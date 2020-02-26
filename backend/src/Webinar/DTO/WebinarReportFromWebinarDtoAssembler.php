<?php

namespace App\Webinar\DTO;


use App\DTO\DtoAssembler;
use App\Webinar\Webinar;
use App\Webinar\WebinarReport;

final class WebinarReportFromWebinarDtoAssembler extends DtoAssembler
{
    /**
     * @var string
     */
    private $fileUrlPrefix;
    
    /**
     * NewsDtoAssembler constructor.
     *
     * @param string $fileUrlPrefix
     */
    public function __construct(string $fileUrlPrefix)
    {
        $this->fileUrlPrefix = $fileUrlPrefix;
    }
    
    protected function createDto()
    {
        return new WebinarReportDto();
    }
    
    /**
     * @param WebinarReportDto $dto
     * @param Webinar          $entity
     */
    protected function fill($dto, $entity)
    {
        $dto->id            = $entity->getId();
        $dto->name          = $entity->getName();
        $dto->subject       = $entity->getSubject();
        $dto->directionName = $entity->getDirection()->getName();
        $dto->startDate     = self::getRussianDate($entity->getStartDatetime());
        $dto->startTime     = $entity->getStartDatetime()->format('H:i');
        
        if ($entity->getReport() instanceof WebinarReport) {
            $dto->subtitle      = $entity->getReport()->getSubtitle();
            $dto->youtubeCode   = $entity->getReport()->getYoutubeCode();
            $dto->description   = $entity->getReport()->getDescription();
    
            $dto->announceImage = empty($entity->getReport()->getAnnounceImage())
                ? ''
                : $this->fileUrlPrefix . '/' . $entity->getReport()->getAnnounceImage();
            $dto->image = empty($entity->getReport()->getImage())
                ? ''
                : $this->fileUrlPrefix . '/' . $entity->getReport()->getImage();
        }
    }
}
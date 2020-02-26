<?php

namespace App\Webinar\DTO;


use App\Domain\Backend\Interactor\File;
use App\DTO\DtoAssembler;
use App\Webinar\WebinarReport;

final class WebinarReportDtoAssembler extends DtoAssembler
{
    protected function createDto()
    {
        return new WebinarReportDto();
    }
    
    /**
     * @param WebinarReportDto $dto
     * @param WebinarReport    $entity
     */
    protected function fill($dto, $entity)
    {
        if (!$entity->getWebinar()->getReport() instanceof WebinarReport) {
            return;
        }
        
        $dto->subtitle          = $entity->getSubtitle();
        $dto->youtubeCode       = $entity->getYoutubeCode();
        $dto->description       = $entity->getDescription();
        $dto->image             = $entity->getImage();
        $dto->imageFile         = $entity->getImage() === null
            ? null
            : (new File())->setFilePath($entity->getImage());
        $dto->announceImage     = $entity->getImage();
        $dto->announceImageFile = $entity->getAnnounceImage() === null
            ? null
            : (new File())->setFilePath($entity->getAnnounceImage());
    }
}
<?php

namespace App\Domain\Entity\ClinicalAnalysis\Backend\DTO;


use App\Domain\Backend\Interactor\File;
use App\Domain\Entity\ClinicalAnalysis\ClinicalAnalysisSlide;
use App\DTO\DtoAssembler;

final class ClinicalAnalysisSlideDtoAssembler extends DtoAssembler
{
    protected function createDto()
    {
        return new ClinicalAnalysisSlideDto();
    }
    
    /**
     * @param ClinicalAnalysisSlideDto $dto
     * @param ClinicalAnalysisSlide    $entity
     */
    protected function fill($dto, $entity)
    {
        $dto->id        = $entity->getId();
        $dto->name      = $entity->getName();
        $dto->number    = $entity->getNumber();
        $dto->image     = $entity->getImage();
        $dto->imageFile = (new File())->setFilePath($entity->getImage());
    }
}
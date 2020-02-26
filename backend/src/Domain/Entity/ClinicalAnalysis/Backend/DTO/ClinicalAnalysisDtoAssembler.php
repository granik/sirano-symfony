<?php


namespace App\Domain\Entity\ClinicalAnalysis\Backend\DTO;


use App\Domain\Entity\ClinicalAnalysis\ClinicalAnalysis;
use App\DTO\DtoAssembler;

final class ClinicalAnalysisDtoAssembler extends DtoAssembler
{
    /**
     * @var ClinicalAnalysisSlideDtoAssembler
     */
    private $slideDtoAssembler;
    
    /**
     * ClinicalAnalysisDtoAssembler constructor.
     *
     * @param ClinicalAnalysisSlideDtoAssembler $slideDtoAssembler
     */
    public function __construct(ClinicalAnalysisSlideDtoAssembler $slideDtoAssembler)
    {
        $this->slideDtoAssembler = $slideDtoAssembler;
    }
    
    protected function createDto()
    {
        return new ClinicalAnalysisDto();
    }
    
    /**
     * @param ClinicalAnalysisDto $dto
     * @param ClinicalAnalysis    $entity
     */
    protected function fill($dto, $entity)
    {
        $dto->id            = $entity->getId();
        $dto->name          = $entity->getName();
        $dto->direction     = $entity->getDirection()->getId();
        $dto->category      = $entity->getCategory() === null ? null : $entity->getCategory()->getId();
        $dto->module        = $entity->getModule()->getId();
        $dto->number        = $entity->getNumber();
        $dto->companyEmail  = $entity->getCompanyEmail();
        $dto->lecturerEmail = $entity->getLecturerEmail();
        $dto->isActive      = $entity->isActive();
        
        $dto->slides = $this->slideDtoAssembler->assembleList($entity->getSlides());
        
        foreach ($entity->getArticles() as $article) {
            $dto->articles[] = $article->getId();
        }
    }
}
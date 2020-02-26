<?php


namespace App\Domain\Entity\Specialty\Backend\DTO;


use App\Domain\Entity\Specialty\AdditionalSpecialty;
use App\Domain\Entity\Specialty\MainSpecialty;
use App\DTO\DtoAssembler;

final class SpecialtyDtoAssembler extends DtoAssembler
{
    protected function createDto()
    {
        return new SpecialtyDto();
    }
    
    /**
     * @param SpecialtyDto                      $dto
     * @param MainSpecialty|AdditionalSpecialty $entity
     */
    protected function fill($dto, $entity)
    {
        $dto->id   = $entity->getId();
        $dto->name = $entity->getName();
        
        if ($entity instanceof MainSpecialty) {
            $dto->isResearcher = $entity->isResearcher();
        }
    }
}
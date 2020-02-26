<?php


namespace App\Domain\Entity\Conference\Backend\DTO;


use App\Domain\Entity\Conference\ConferenceSeries;
use App\DTO\DtoAssembler;

final class ConferenceSeriesDtoAssembler extends DtoAssembler
{
    protected function createDto()
    {
        return new ConferenceSeriesDto();
    }
    
    /**
     * @param ConferenceSeriesDto $dto
     * @param ConferenceSeries    $entity
     */
    protected function fill($dto, $entity)
    {
        $dto->id        = $entity->getId();
        $dto->name      = $entity->getName();
        $dto->direction = $entity->getDirection()->getId();
    }
}
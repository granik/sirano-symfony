<?php

namespace App\DTO;


use App\Domain\Entity\City;
use App\Domain\Interactor\CityDto;

final class CityDtoAssembler extends DtoAssembler
{
    protected function createDto()
    {
        return new CityDto();
    }
    
    /**
     * @param CityDto $dto
     * @param City    $entity
     */
    protected function fill($dto, $entity)
    {
        $dto->id   = $entity->getId();
        $dto->name = $entity->getName();
    }
}
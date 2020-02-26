<?php


namespace App\Domain\Entity\Direction\Backend\DTO;


use App\Domain\Entity\Direction\Category;
use App\DTO\DtoAssembler;

final class DirectionCategoryDtoAssembler extends DtoAssembler
{
    protected function createDto()
    {
        return new DirectionCategoryDto();
    }
    
    /**
     * @param DirectionCategoryDto $dto
     * @param Category             $entity
     */
    protected function fill($dto, $entity)
    {
        $dto->id   = $entity->getId();
        $dto->name = $entity->getName();
    }
}
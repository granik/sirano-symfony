<?php


namespace App\Domain\Entity\Direction\Frontend\DTO;


use App\Domain\Entity\Direction\Category;
use App\DTO\DtoAssembler;

final class CategoryDtoAssembler extends DtoAssembler
{
    protected function createDto()
    {
        return new CategoryDto();
    }
    
    /**
     * @param CategoryDto $dto
     * @param Category    $entity
     */
    protected function fill($dto, $entity)
    {
        $dto->id   = $entity->getId();
        $dto->name = $entity->getName();
    }
}
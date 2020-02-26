<?php


namespace App\Domain\Entity\Module\Backend\DTO;


use App\Domain\Entity\Article\Article;
use App\DTO\DtoAssembler;

final class ModuleArticleDtoAssembler extends DtoAssembler
{
    protected function createDto()
    {
        return new ModuleArticleDto();
    }
    
    /**
     * @param ModuleArticleDto $dto
     * @param Article          $entity
     */
    protected function fill($dto, $entity)
    {
        $dto->id   = $entity->getId();
        $dto->name = $entity->getName();
    }
}
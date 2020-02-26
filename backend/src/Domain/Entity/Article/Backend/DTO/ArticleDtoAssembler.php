<?php


namespace App\Domain\Entity\Article\Backend\DTO;


use App\Domain\Backend\Interactor\File;
use App\Domain\Entity\Article\Article;
use App\DTO\DtoAssembler;

final class ArticleDtoAssembler extends DtoAssembler
{
    protected function createDto()
    {
        return new ArticleDto();
    }
    
    /**
     * @param ArticleDto $dto
     * @param Article    $entity
     */
    protected function fill($dto, $entity)
    {
        $dto->id        = $entity->getId();
        $dto->name      = $entity->getName();
        $dto->author    = $entity->getAuthor();
        $dto->isActive  = $entity->isActive();
        $dto->direction = $entity->getDirection()->getId();
        $dto->category  = $entity->getCategory() === null ? null : $entity->getCategory()->getId();
        $dto->file      = $entity->getFile();
        $dto->fileFile  = (new File())->setFilePath($entity->getFile());
    }
}
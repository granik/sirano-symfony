<?php


namespace App\Domain\Entity\News\Backend\DTO;


use App\Domain\Backend\Interactor\File;
use App\Domain\Entity\News\News;
use App\DTO\DtoAssembler;

final class NewsDtoAssembler extends DtoAssembler
{
    protected function createDto()
    {
        return new NewsDto();
    }
    
    /**
     * @param NewsDto $dto
     * @param News    $entity
     */
    protected function fill($dto, $entity)
    {
        $dto->id                = $entity->getId();
        $dto->name              = $entity->getName();
        $dto->direction         = $entity->getDirection()->getId();
        $dto->createdAt         = $entity->getCreatedAt();
        $dto->createdAtString   = $entity->getCreatedAt()->format('d.m.Y');
        $dto->image             = $entity->getImage();
        $dto->imageFile         = (new File())->setFilePath($entity->getImage());
        $dto->announceImage     = $entity->getImage();
        $dto->announceImageFile = empty($entity->getAnnounceImage())
            ? null
            : (new File())->setFilePath($entity->getAnnounceImage());
        $dto->text              = $entity->getText();
        $dto->isActive          = $entity->isActive();
    }
}
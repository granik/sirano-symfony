<?php


namespace App\Domain\Entity\Document\Backend\DTO;


use App\Domain\Backend\Interactor\File;
use App\Domain\Entity\Document\Document;
use App\DTO\DtoAssembler;

final class DocumentDtoAssembler extends DtoAssembler
{
    protected function createDto()
    {
        return new DocumentDto();
    }
    
    /**
     * @param DocumentDto $dto
     * @param Document    $entity
     */
    protected function fill($dto, $entity)
    {
        $dto->id       = $entity->getId();
        $dto->name     = $entity->getName();
        $dto->isActive = $entity->isActive();
        $dto->file     = $entity->getFile();
        $dto->fileFile = (new File())->setFilePath($entity->getFile());
    }
}
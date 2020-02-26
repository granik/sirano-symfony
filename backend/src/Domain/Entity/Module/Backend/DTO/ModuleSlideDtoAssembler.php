<?php

namespace App\Domain\Entity\Module\Backend\DTO;


use App\Domain\Backend\Interactor\File;
use App\Domain\Entity\Module\ModuleSlide;
use App\DTO\DtoAssembler;

final class ModuleSlideDtoAssembler extends DtoAssembler
{
    protected function createDto()
    {
        return new ModuleSlideDto();
    }
    
    /**
     * @param ModuleSlideDto $dto
     * @param ModuleSlide    $entity
     */
    protected function fill($dto, $entity)
    {
        $dto->id        = $entity->getId();
        $dto->name      = $entity->getName();
        $dto->number    = $entity->getNumber();
        $dto->image     = $entity->getImage();
        $dto->imageFile = (new File())->setFilePath($entity->getImage());
    }
}
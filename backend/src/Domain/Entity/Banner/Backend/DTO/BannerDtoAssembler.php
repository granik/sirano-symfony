<?php


namespace App\Domain\Entity\Banner\Backend\DTO;


use App\Domain\Backend\Interactor\File;
use App\Domain\Entity\Banner\Banner;
use App\DTO\DtoAssembler;

final class BannerDtoAssembler extends DtoAssembler
{
    protected function createDto()
    {
        return new BannerDto();
    }
    
    /**
     * @param BannerDto $dto
     * @param Banner    $entity
     */
    protected function fill($dto, $entity)
    {
        $dto->id               = $entity->getId();
        $dto->name             = $entity->getName();
        $dto->link             = $entity->getLink();
        $dto->number           = $entity->getNumber();
        $dto->desktopImage     = $entity->getDesktopImage();
        $dto->desktopImageFile = (new File())->setFilePath($entity->getDesktopImage());
        $dto->mobileImage      = $entity->getMobileImage();
        $dto->mobileImageFile  = (new File())->setFilePath($entity->getMobileImage());
        $dto->isActive         = $entity->isActive();
        $dto->backgroundColor  = $entity->getBackgroundColor();
    }
}
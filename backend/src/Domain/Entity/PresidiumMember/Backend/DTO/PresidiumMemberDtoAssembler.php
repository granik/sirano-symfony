<?php


namespace App\Domain\Entity\PresidiumMember\Backend\DTO;


use App\Domain\Backend\Interactor\File;
use App\Domain\Entity\PresidiumMember\PresidiumMember;
use App\DTO\DtoAssembler;

final class PresidiumMemberDtoAssembler extends DtoAssembler
{
    protected function createDto()
    {
        return new PresidiumMemberDto();
    }
    
    /**
     * @param PresidiumMemberDto $dto
     * @param PresidiumMember    $entity
     */
    protected function fill($dto, $entity)
    {
        $dto->id          = $entity->getId();
        $dto->name        = $entity->getName();
        $dto->middlename  = $entity->getMiddlename();
        $dto->lastname    = $entity->getLastname();
        $dto->image       = $entity->getImage();
        $dto->imageFile   = (new File())->setFilePath($entity->getImage());
        $dto->description = $entity->getDescription();
        $dto->isActive    = $entity->isActive();
        $dto->number      = $entity->getNumber();
    }
}
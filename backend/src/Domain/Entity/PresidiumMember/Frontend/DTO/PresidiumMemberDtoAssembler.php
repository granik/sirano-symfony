<?php


namespace App\Domain\Entity\PresidiumMember\Frontend\DTO;


use App\Domain\Entity\PresidiumMember\PresidiumMember;
use App\DTO\DtoAssembler;

final class PresidiumMemberDtoAssembler extends DtoAssembler
{
    /**
     * @var string
     */
    private $fileUrlPrefix;

    /**
     * PresidiumMemberDtoAssembler constructor.
     *
     * @param string $fileUrlPrefix
     */
    public function __construct(string $fileUrlPrefix)
    {
        $this->fileUrlPrefix = $fileUrlPrefix;
    }

    protected function createDto()
    {
        return new PresidiumMemberDto();
    }

    /**
     * @param PresidiumMemberDto $dto
     * @param PresidiumMember $entity
     */
    protected function fill($dto, $entity)
    {
        $dto->name = $entity->getLastname() . ' ' . $entity->getName();
        if (!empty($entity->getMiddlename())) {
            $dto->name .= ' ' . $entity->getMiddlename();
        }

        $dto->desription = $entity->getDescription();
        $dto->photo = $this->fileUrlPrefix . '/' . $entity->getImage();
    }
}
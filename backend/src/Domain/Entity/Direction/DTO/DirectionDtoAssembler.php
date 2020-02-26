<?php

namespace App\Domain\Entity\Direction\DTO;


use App\Domain\Backend\Interactor\File;
use App\Domain\Entity\Direction\Backend\DTO\DirectionCategoryDtoAssembler;
use App\Domain\Entity\Direction\Direction;
use App\DTO\DtoAssembler;

final class DirectionDtoAssembler extends DtoAssembler
{
    /**
     * @var string
     */
    private $fileUrlPrefix;
    /**
     * @var DirectionCategoryDtoAssembler
     */
    private $categoryDtoAssembler;
    
    /**
     * NewsDtoAssembler constructor.
     *
     * @param DirectionCategoryDtoAssembler $categoryDtoAssembler
     * @param string                        $fileUrlPrefix
     */
    public function __construct(DirectionCategoryDtoAssembler $categoryDtoAssembler, string $fileUrlPrefix)
    {
        $this->fileUrlPrefix        = $fileUrlPrefix;
        $this->categoryDtoAssembler = $categoryDtoAssembler;
    }
    
    protected function createDto()
    {
        return new DirectionDto();
    }
    
    /**
     * @param DirectionDto $dto
     * @param Direction    $entity
     */
    protected function fill($dto, $entity)
    {
        $dto->id         = $entity->getId();
        $dto->name       = $entity->getName();
        $dto->isActive   = $entity->isActive();
        $dto->isMainPage = $entity->isMainPage();
        $dto->number     = $entity->getNumber();
        
        $icon          = $entity->getIcon();
        $dto->icon     = empty($icon) ? '' : $this->fileUrlPrefix . '/' . $icon;
        $dto->iconFile = empty($icon) ? null : (new File())->setFilePath($icon);
        
        $activeIcon          = $entity->getActiveIcon();
        $dto->activeIcon     = empty($activeIcon) ? '' : $this->fileUrlPrefix . '/' . $activeIcon;
        $dto->activeIconFile = empty($activeIcon) ? null : (new File())->setFilePath($activeIcon);
        
        $image          = $entity->getImage();
        $dto->image     = empty($image) ? '' : $this->fileUrlPrefix . '/' . $image;
        $dto->imageFile = empty($image) ? null : (new File())->setFilePath($image);
        
        $dto->categories = $this->categoryDtoAssembler->assembleList($entity->getCategories());
    }
}
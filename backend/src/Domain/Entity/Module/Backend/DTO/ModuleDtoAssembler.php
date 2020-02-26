<?php

namespace App\Domain\Entity\Module\Backend\DTO;


use App\Domain\Entity\Module\Module;
use App\DTO\DtoAssembler;

final class ModuleDtoAssembler extends DtoAssembler
{
    /**
     * @var ModuleSlideDtoAssembler
     */
    private $slideDtoAssembler;
    /**
     * @var ModuleArticleDtoAssembler
     */
    private $articleDtoAssembler;
    
    /**
     * ModuleDtoAssembler constructor.
     *
     * @param ModuleSlideDtoAssembler   $slideDtoAssembler
     * @param ModuleArticleDtoAssembler $articleDtoAssembler
     */
    public function __construct(
        ModuleSlideDtoAssembler $slideDtoAssembler,
        ModuleArticleDtoAssembler $articleDtoAssembler
    ) {
        $this->slideDtoAssembler   = $slideDtoAssembler;
        $this->articleDtoAssembler = $articleDtoAssembler;
    }
    
    protected function createDto()
    {
        return new ModuleDto();
    }
    
    /**
     * @param ModuleDto $dto
     * @param Module    $entity
     */
    protected function fill($dto, $entity)
    {
        $dto->id          = $entity->getId();
        $dto->name        = $entity->getName();
        $dto->number      = $entity->getNumber();
        $dto->youtubeCode = $entity->getYoutubeCode();
        $dto->isActive    = $entity->isActive();
        $dto->direction   = $entity->getDirection()->getId();
        $dto->category    = $entity->getCategory() === null ? null : $entity->getCategory()->getId();
    
        $dto->slides = $this->slideDtoAssembler->assembleList($entity->getSlides());
        
        foreach ($entity->getArticles() as $article) {
            $dto->articles[] = $article->getId();
        }
    }
}
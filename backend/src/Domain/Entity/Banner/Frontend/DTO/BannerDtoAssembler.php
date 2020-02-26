<?php


namespace App\Domain\Entity\Banner\Frontend\DTO;


use App\Domain\Entity\Banner\Banner;
use App\DTO\DtoAssembler;

final class BannerDtoAssembler extends DtoAssembler
{
    /**
     * @var string
     */
    private $fileUrlPrefix;
    
    /**
     * ArticleDtoAssembler constructor.
     *
     * @param string $fileUrlPrefix
     */
    public function __construct(string $fileUrlPrefix)
    {
        $this->fileUrlPrefix = $fileUrlPrefix;
    }
    
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
        $dto->link            = $entity->getLink();
        $dto->desktopImage    = $this->fileUrlPrefix . '/' . $entity->getDesktopImage();
        $dto->mobileImage     = $this->fileUrlPrefix . '/' . $entity->getMobileImage();
        $dto->backgroundColor = $entity->getBackgroundColor();
    }
}
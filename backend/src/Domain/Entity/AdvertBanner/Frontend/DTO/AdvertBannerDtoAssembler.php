<?php


namespace App\Domain\Entity\AdvertBanner\Frontend\DTO;


use App\Domain\Entity\AdvertBanner\AdvertBanner;
use App\Domain\Entity\Banner\Frontend\DTO\BannerDto;
use App\DTO\DtoAssembler;

final class AdvertBannerDtoAssembler extends DtoAssembler
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
     * @param BannerDto    $dto
     * @param AdvertBanner $entity
     */
    protected function fill($dto, $entity)
    {
        $dto->link         = $entity->getLink();
        $dto->desktopImage = $this->fileUrlPrefix . '/' . $entity->getDesktopImage();
        $dto->mobileImage  = $this->fileUrlPrefix . '/' . $entity->getMobileImage();
    }
}
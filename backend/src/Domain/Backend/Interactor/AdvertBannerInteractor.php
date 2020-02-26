<?php


namespace App\Domain\Backend\Interactor;


use App\Domain\Entity\AdvertBanner\AdvertBanner;
use App\Domain\Entity\AdvertBanner\Backend\AdvertBannerRepositoryInterface;
use App\Domain\Entity\AdvertBanner\Backend\DTO\AdvertBannerDto;
use App\Interactors\NonExistentEntity;

final class AdvertBannerInteractor
{
    use DeleteInteractor;
    
    const UPLOAD_DIRECTORY = 'advert_banner';
    
    /**
     * @var AdvertBannerRepositoryInterface
     */
    private $repository;
    /**
     * @var DirectionInteractor
     */
    private $directionInteractor;
    /**
     * @var FileUploader
     */
    private $fileUploader;
    
    /**
     * AdvertBannerInteractor constructor.
     *
     * @param AdvertBannerRepositoryInterface $repository
     * @param DirectionInteractor       $directionInteractor
     * @param FileUploader              $fileUploader
     */
    public function __construct(
        AdvertBannerRepositoryInterface $repository,
        DirectionInteractor $directionInteractor,
        FileUploader $fileUploader
    ) {
        $this->repository          = $repository;
        $this->directionInteractor = $directionInteractor;
        $this->fileUploader        = $fileUploader;
    }
    
    public function list(int $page, int $perPage)
    {
        return $this->repository->list($page, $perPage);
    }
    
    public function create(AdvertBannerDto $dto)
    {
        $entity = new AdvertBanner();
        $entity = $this->fillEntity($entity, $dto);
        
        $this->repository->store($entity);
        
        $this->fillEntityImages($entity, $dto);
        
        $this->repository->update($entity);
        
        return $entity;
    }
    
    public function find($id)
    {
        return $this->repository->find($id);
    }
    
    /**
     * @param $dto
     *
     * @return mixed
     * @throws NonExistentEntity
     */
    public function update($dto)
    {
        $entity = $this->find($dto->id);
        
        if (!$entity instanceof AdvertBanner) {
            throw new NonExistentEntity();
        }
        
        $entity = $this->fillEntity($entity, $dto);
        $this->fillEntityImages($entity, $dto);
        
        return $this->repository->update($entity);
    }
    
    /**
     * @param AdvertBanner    $entity
     * @param AdvertBannerDto $dto
     *
     * @return AdvertBanner
     * @throws NonExistentEntity
     */
    private function fillEntity(AdvertBanner $entity, AdvertBannerDto $dto): AdvertBanner
    {
        $entity
            ->setName($dto->name)
            ->setLink($dto->link)
            ->setNumber($dto->number)
            ->setIsActive($dto->isActive);
        
        return $entity;
    }
    
    /**
     * @param AdvertBanner    $entity
     * @param AdvertBannerDto $dto
     *
     * @return AdvertBannerInteractor
     * @throws Exceptions\NoUploadFile
     */
    private function fillEntityImages(AdvertBanner $entity, AdvertBannerDto $dto): AdvertBannerInteractor
    {
        if (
            $dto->desktopImageFile instanceof File
            && $dto->desktopImageFile->getUploadedFile() instanceof UploadedFile
        ) {
            $entity->setDesktopImage(
                $this->fileUploader->upload(
                    $dto->desktopImageFile,
                    'desktop',
                    self::UPLOAD_DIRECTORY . '/' . $entity->getId()
                )
            );
        }
        
        if (
            $dto->mobileImageFile instanceof File
            && $dto->mobileImageFile->getUploadedFile() instanceof UploadedFile
        ) {
            $entity->setMobileImage(
                $this->fileUploader->upload(
                    $dto->mobileImageFile,
                    'mobile',
                    self::UPLOAD_DIRECTORY . '/' . $entity->getId()
                )
            );
        }
        
        return $this;
    }
}
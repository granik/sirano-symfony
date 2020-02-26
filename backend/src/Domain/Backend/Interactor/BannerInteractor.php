<?php


namespace App\Domain\Backend\Interactor;


use App\Domain\Entity\Banner\Backend\BannerRepositoryInterface;
use App\Domain\Entity\Banner\Backend\DTO\BannerDto;
use App\Domain\Entity\Banner\Banner;
use App\Interactors\NonExistentEntity;

final class BannerInteractor
{
    use DeleteInteractor;
    
    const UPLOAD_DIRECTORY = 'banner';
    
    /**
     * @var BannerRepositoryInterface
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
     * BannerInteractor constructor.
     *
     * @param BannerRepositoryInterface $repository
     * @param DirectionInteractor       $directionInteractor
     * @param FileUploader              $fileUploader
     */
    public function __construct(
        BannerRepositoryInterface $repository,
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
    
    public function create(BannerDto $dto)
    {
        $entity = new Banner();
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
        
        if (!$entity instanceof Banner) {
            throw new NonExistentEntity();
        }
        
        $entity = $this->fillEntity($entity, $dto);
        $this->fillEntityImages($entity, $dto);
        
        return $this->repository->update($entity);
    }
    
    /**
     * @param Banner    $entity
     * @param BannerDto $dto
     *
     * @return Banner
     * @throws NonExistentEntity
     */
    private function fillEntity(Banner $entity, BannerDto $dto): Banner
    {
        $entity
            ->setName($dto->name)
            ->setLink($dto->link)
            ->setNumber($dto->number)
            ->setIsActive($dto->isActive)
            ->setBackgroundColor($dto->backgroundColor);
        
        return $entity;
    }
    
    /**
     * @param Banner    $entity
     * @param BannerDto $dto
     *
     * @return BannerInteractor
     * @throws Exceptions\NoUploadFile
     */
    private function fillEntityImages(Banner $entity, BannerDto $dto): BannerInteractor
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
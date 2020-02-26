<?php


namespace App\Domain\Backend\Interactor;


use App\Domain\Entity\Direction\Direction;
use App\Domain\Entity\News\Backend\DTO\NewsDto;
use App\Domain\Entity\News\Backend\NewsRepositoryInterface;
use App\Domain\Entity\News\News;
use App\Interactors\NonExistentEntity;

final class NewsInteractor
{
    use DeleteInteractor;
    
    const UPLOAD_DIRECTORY = 'news';
    
    /**
     * @var NewsRepositoryInterface
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
     * NewsInteractor constructor.
     *
     * @param NewsRepositoryInterface $repository
     * @param DirectionInteractor     $directionInteractor
     * @param FileUploader            $fileUploader
     */
    public function __construct(
        NewsRepositoryInterface $repository,
        DirectionInteractor $directionInteractor,
        FileUploader $fileUploader
    ) {
        $this->repository          = $repository;
        $this->directionInteractor = $directionInteractor;
        $this->fileUploader        = $fileUploader;
    }
    
    public function list(int $page, int $perPage, array $criteria)
    {
        return $this->repository->list($page, $perPage, $criteria);
    }
    
    public function create(NewsDto $dto)
    {
        $entity = new News();
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
        
        if (!$entity instanceof News) {
            throw new NonExistentEntity();
        }
        
        $entity = $this->fillEntity($entity, $dto);
        $this->fillEntityImages($entity, $dto);
        
        return $this->repository->update($entity);
    }
    
    /**
     * @param News    $entity
     * @param NewsDto $dto
     *
     * @return News
     * @throws NonExistentEntity
     */
    private function fillEntity(News $entity, NewsDto $dto): News
    {
        $direction = $this->directionInteractor->find($dto->direction);
        
        if (!$direction instanceof Direction) {
            throw new NonExistentEntity();
        }
        
        $entity
            ->setName($dto->name)
            ->setDirection($direction)
            ->setCreatedAt($dto->createdAt)
            ->setText($dto->text)
            ->setIsActive($dto->isActive);
        
        return $entity;
    }
    
    /**
     * @param News    $entity
     * @param NewsDto $dto
     *
     * @return NewsInteractor
     * @throws Exceptions\NoUploadFile
     */
    private function fillEntityImages(News $entity, NewsDto $dto): NewsInteractor
    {
        if (
            $dto->announceImageFile instanceof File
            && $dto->announceImageFile->getUploadedFile() instanceof UploadedFile
        ) {
            $entity->setAnnounceImage(
                $this->fileUploader->upload(
                    $dto->announceImageFile,
                    'announce',
                    self::UPLOAD_DIRECTORY . '/' . $entity->getId()
                )
            );
        }
        
        if (
            $dto->imageFile instanceof File
            && $dto->imageFile->getUploadedFile() instanceof UploadedFile
        ) {
            $entity->setImage(
                $this->fileUploader->upload(
                    $dto->imageFile,
                    'news',
                    self::UPLOAD_DIRECTORY . '/' . $entity->getId()
                )
            );
        }
        
        return $this;
    }
}
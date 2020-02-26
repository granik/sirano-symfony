<?php


namespace App\Domain\Backend\Interactor;


use App\Domain\Backend\Interactor\Exceptions\NoUploadFile;
use App\Domain\Entity\Direction\Backend\CategoryRepositoryInterface;
use App\Domain\Entity\Direction\Backend\DTO\DirectionCategoryDto;
use App\Domain\Entity\Direction\Category;
use App\Domain\Entity\Direction\Direction;
use App\Domain\Entity\Direction\DirectionRepositoryInterface;
use App\Domain\Entity\Direction\DTO\DirectionDto;
use App\Interactors\NonExistentEntity;

final class DirectionInteractor
{
    use DeleteInteractor;
    
    const UPLOAD_DIRECTORY = 'direction';
    
    /**
     * @var DirectionRepositoryInterface
     */
    private $repository;
    /**
     * @var FileUploader
     */
    private $fileUploader;
    
    private $targetDirectory;
    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;
    
    /**
     * DirectionInteractor constructor.
     *
     * @param DirectionRepositoryInterface $repository
     * @param CategoryRepositoryInterface  $categoryRepository
     * @param FileUploader                 $fileUploader
     * @param                              $targetDirectory
     */
    public function __construct(
        DirectionRepositoryInterface $repository,
        CategoryRepositoryInterface $categoryRepository,
        FileUploader $fileUploader,
        $targetDirectory
    ) {
        $this->repository         = $repository;
        $this->fileUploader       = $fileUploader;
        $this->targetDirectory    = $targetDirectory;
        $this->categoryRepository = $categoryRepository;
    }
    
    public function list(int $page, int $perPage)
    {
        return $this->repository->list($page, $perPage);
    }
    
    public function activeList()
    {
        return $this->repository->activeList();
    }
    
    /**
     * @param DirectionDto $dto
     *
     * @return Direction
     * @throws NoUploadFile
     * @throws NonExistentEntity
     */
    public function create(DirectionDto $dto): Direction
    {
        $entity = new Direction();
        $entity = $this->fillEntity($entity, $dto);
        
        $this->repository->store($entity);
    
        $this->updateCategories($entity, $dto->categories);
        $this->fillEntityImages($entity, $dto);
        
        $this->repository->update($entity);
        
        return $entity;
    }
    
    /**
     * @param DirectionDto $dto
     *
     * @return mixed
     * @throws NonExistentEntity
     * @throws NoUploadFile
     */
    public function update(DirectionDto $dto)
    {
        $entity = $this->find($dto->id);
        
        if (!$entity instanceof Direction) {
            throw new NonExistentEntity();
        }
        
        $entity = $this->fillEntity($entity, $dto);
        
        $this->updateCategories($entity, $dto->categories);
        $this->fillEntityImages($entity, $dto);
        
        return $this->repository->update($entity);
    }
    
    public function find($id): ?Direction
    {
        return $this->repository->find($id);
    }
    
    /**
     * @param $directionId
     *
     * @throws NonExistentEntity
     */
    public function listCategoryByDirection($directionId)
    {
        $entity = $this->find($directionId);
    
        if (!$entity instanceof Direction) {
            throw new NonExistentEntity();
        }
        
        return $this->categoryRepository->listCategoryByDirection($entity);
    }
    
    public function findCategory($id)
    {
        return $this->categoryRepository->find($id);
    }
    
    private function fillEntity(Direction $entity, DirectionDto $dto)
    {
        $entity
            ->setName($dto->name)
            ->setIsActive($dto->isActive)
            ->setIsMainPage($dto->isMainPage)
            ->setNumber($dto->number);
        
        return $entity;
    }
    
    /**
     * @param Direction    $entity
     * @param DirectionDto $dto
     *
     * @return DirectionInteractor
     * @throws Exceptions\NoUploadFile
     */
    private function fillEntityImages(Direction $entity, DirectionDto $dto): DirectionInteractor
    {
        if (
            $dto->iconFile instanceof File
            && $dto->iconFile->getUploadedFile() instanceof UploadedFile
        ) {
            $entity->setIcon(
                $this->fileUploader->upload(
                    $dto->iconFile,
                    'icon',
                    self::UPLOAD_DIRECTORY . '/' . $entity->getId()
                )
            );
        }
        
        if (
            $dto->activeIconFile instanceof File
            && $dto->activeIconFile->getUploadedFile() instanceof UploadedFile
        ) {
            $entity->setActiveIcon(
                $this->fileUploader->upload(
                    $dto->activeIconFile,
                    'active_icon',
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
    
    /**
     * @param Direction              $entity
     * @param DirectionCategoryDto[] $categoryDtos
     *
     * @throws NonExistentEntity
     */
    private function updateCategories(Direction $entity, array $categoryDtos)
    {
        $entityCategoryIds = [];
        foreach ($entity->getCategories() as $categoryItem) {
            $entityCategoryIds[] = $categoryItem->getId();
        }
        
        $submittedCategoryIds = [];
        foreach ($categoryDtos as $categoryDtoItem) {
            if (!empty($categoryDtoItem->id)) {
                $submittedCategoryIds[] = $categoryDtoItem->id;
            }
        }
        
        $deleteIds = [];
        foreach ($entityCategoryIds as $id) {
            if (!in_array($id, $submittedCategoryIds)) {
                $deleteIds[] = $id;
            }
        }
        
        if (!empty($deleteIds)) {
            $this->deleteCategories($deleteIds);
        }
        
        foreach ($categoryDtos as $categoryDtoItem) {
            if (empty($categoryDtoItem->id)) {
                $this->createCategory($entity, $categoryDtoItem);
            } else {
                $this->updateCategory($entity, $categoryDtoItem);
            }
        }
    }
    
    private function deleteCategories(array $deleteIds)
    {
        $this->categoryRepository->deleteByIds($deleteIds);
    }
    
    private function createCategory(Direction $entity, DirectionCategoryDto $categoryDto)
    {
        $category = new Category();
        $this->fillCategory($category, $entity, $categoryDto);
        $this->categoryRepository->store($category);
    }
    
    /**
     * @param Direction            $entity
     * @param DirectionCategoryDto $categoryDto
     *
     * @throws NonExistentEntity
     */
    private function updateCategory(Direction $entity, DirectionCategoryDto $categoryDto)
    {
        $category = $this->categoryRepository->find($categoryDto->id);
        
        if (!$category instanceof Category) {
            throw new NonExistentEntity();
        }
        
        $this->fillCategory($category, $entity, $categoryDto);
        $this->categoryRepository->update($category);
    }
    
    /**
     * @param Category             $category
     * @param Direction            $entity
     * @param DirectionCategoryDto $categoryDto
     */
    private function fillCategory(Category $category, Direction $entity, DirectionCategoryDto $categoryDto): void
    {
        $category->setName($categoryDto->name)->setDirection($entity);
    }
}

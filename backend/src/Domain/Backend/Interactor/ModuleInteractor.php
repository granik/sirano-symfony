<?php

namespace App\Domain\Backend\Interactor;


use App\Domain\Entity\Direction\Category;
use App\Domain\Entity\Direction\Direction;
use App\Domain\Entity\Module\Backend\DTO\ModuleDto;
use App\Domain\Entity\Module\Backend\DTO\ModuleSlideDto;
use App\Domain\Entity\Module\Backend\ModuleRepositoryInterface;
use App\Domain\Entity\Module\Module;
use App\Domain\Entity\Module\ModuleSlide;
use App\Domain\Entity\Module\ModuleTest;
use App\Domain\Entity\Module\ModuleTestQuestion;
use App\Interactors\NonExistentEntity;

final class ModuleInteractor
{
    use DeleteInteractor;
    
    const UPLOAD_DIRECTORY = 'module';
    
    /**
     * @var ModuleRepositoryInterface
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
     * @var ModuleTestInteractor
     */
    private $testInteractor;
    /**
     * @var ArticleInteractor
     */
    private $articleInteractor;
    
    /**
     * ModuleInteractor constructor.
     *
     * @param ModuleRepositoryInterface $repository
     * @param DirectionInteractor       $directionInteractor
     * @param ModuleTestInteractor      $testInteractor
     * @param FileUploader              $fileUploader
     * @param ArticleInteractor         $articleInteractor
     */
    public function __construct(
        ModuleRepositoryInterface $repository,
        DirectionInteractor $directionInteractor,
        ModuleTestInteractor $testInteractor,
        FileUploader $fileUploader,
        ArticleInteractor $articleInteractor
    ) {
        $this->repository          = $repository;
        $this->directionInteractor = $directionInteractor;
        $this->testInteractor      = $testInteractor;
        $this->fileUploader        = $fileUploader;
        $this->articleInteractor   = $articleInteractor;
    }
    
    public function list(int $page, int $perPage, array $criteria)
    {
        return $this->repository->list($page, $perPage, $criteria);
    }
    
    /**
     * @param ModuleDto $dto
     *
     * @return Module
     * @throws Exceptions\NoUploadFile
     * @throws NonExistentEntity
     */
    public function create(ModuleDto $dto)
    {
        $entity = new Module();
        $entity = $this->fillEntity($entity, $dto);
    
        $this->repository->store($entity);
    
        $this->updateSlides($entity, $dto->slides);
        $this->updateArticles($entity, $dto->articles);
        
        return $entity;
    }
    
    public function find($id)
    {
        return $this->repository->find($id);
    }
    
    /**
     * @param ModuleDto $dto
     *
     * @return mixed
     * @throws Exceptions\NoUploadFile
     * @throws NonExistentEntity
     */
    public function update(ModuleDto $dto)
    {
        $entity = $this->find($dto->id);
        
        if (!$entity instanceof Module) {
            throw new NonExistentEntity();
        }
        
        $entity = $this->fillEntity($entity, $dto);
        
        $this->updateSlides($entity, $dto->slides);
        $this->updateArticles($entity, $dto->articles);
        
        return $this->repository->update($entity);
    }
    
    /**
     * @param Module $entity
     *
     * @return ModuleTest
     */
    public function getTest(Module $entity)
    {
        $test = $entity->getTest();
        
        if ($test instanceof ModuleTest) {
            $test = $this->testInteractor->find($test->getId());
        } else {
            $test = (new ModuleTest())->setModule($entity);
            
            for ($i = 1; $i <= ModuleTest::QUESTIONS_NUMBER; $i++) {
                $test->addQuestion(new ModuleTestQuestion());
            }
        }
        
        return $test;
    }
    
    public function listAll($clinicalAnalysisId)
    {
        return $this->repository->listAll($clinicalAnalysisId);
    }
    
    /**
     * @param Module    $entity
     * @param ModuleDto $dto
     *
     * @return Module
     * @throws NonExistentEntity
     */
    private function fillEntity(Module $entity, ModuleDto $dto): Module
    {
        $direction = $this->directionInteractor->find($dto->direction);
        
        if (!$direction instanceof Direction) {
            throw new NonExistentEntity();
        }
        
        if (count($direction->getCategories()) === 0) {
            $dto->category = null;
        }
        
        if ($dto->category === null) {
            $category = null;
        } else {
            $category = $this->directionInteractor->findCategory($dto->category);
    
            if (!$category instanceof Category) {
                throw new NonExistentEntity();
            }
            
            if ($category->getDirection()->getId() !== $direction->getId()) {
                $category = null;
            }
        }
    
        $entity
            ->setName($dto->name)
            ->setDirection($direction)
            ->setCategory($category)
            ->setNumber($dto->number)
            ->setYoutubeCode($dto->youtubeCode)
            ->setIsActive($dto->isActive);
        
        return $entity;
    }
    
    /**
     * @param Module           $entity
     * @param ModuleSlideDto[] $slideDtos
     *
     * @throws NonExistentEntity
     */
    private function updateSlides(Module $entity, array $slideDtos)
    {
        $entitySlideIds = [];
        foreach ($entity->getSlides() as $slideItem) {
            $entitySlideIds[] = $slideItem->getId();
        }
        
        $submittedSlideIds = [];
        foreach ($slideDtos as $slideDtoItem) {
            if (!empty($slideDtoItem->id)) {
                $submittedSlideIds[] = $slideDtoItem->id;
            }
        }
        
        $deleteIds = [];
        foreach ($entitySlideIds as $id) {
            if (!in_array($id, $submittedSlideIds)) {
                $deleteIds[] = $id;
            }
        }
        
        if (!empty($deleteIds)) {
            $this->deleteSlides($deleteIds);
        }
        
        foreach ($slideDtos as $slideDtoItem) {
            if (empty($slideDtoItem->id)) {
                $this->createSlide($entity, $slideDtoItem);
            } else {
                if (
                    $slideDtoItem->imageFile instanceof File
                    && $slideDtoItem->imageFile->getUploadedFile() instanceof UploadedFile
                ) {
                    $this->updateSlide($entity, $slideDtoItem);
                }
            }
        }
    }
    
    /**
     * @param Module         $module
     * @param ModuleSlideDto $dto
     *
     * @return ModuleSlide
     */
    private function createSlide(Module $module, ModuleSlideDto $dto): ModuleSlide
    {
        $slide = new ModuleSlide();
        $this->fillSlide($module, $slide, $dto);
        
        $this->repository->storeSlide($slide);
        
        return $slide;
    }
    
    /**
     * @param Module         $module
     * @param ModuleSlideDto $dto
     *
     * @return mixed
     * @throws NonExistentEntity
     */
    private function updateSlide(Module $module, ModuleSlideDto $dto)
    {
        $slide = $this->findSlide($dto->id);
        
        if (!$slide instanceof ModuleSlide) {
            throw new NonExistentEntity();
        }
        
        $this->fillSlide($module, $slide, $dto);
        
        return $this->repository->updateSlide($slide);
    }
    
    private function findSlide(int $id)
    {
        return $this->repository->findSlide($id);
    }
    
    private function fillSlide(Module $module, ModuleSlide $slide, ModuleSlideDto $dto)
    {
        $slide
            ->setModule($module)
            ->setNumber($dto->number)
            ->setName($dto->name)
            ->setImage(
                $this->fileUploader->upload(
                    $dto->imageFile,
                    "slide_{$dto->number}",
                    self::UPLOAD_DIRECTORY . '/' . $module->getId()
                )
            );
    }
    
    private function updateArticles(Module $entity, array $articleIds)
    {
        $articles = $this->articleInteractor->findByIds($articleIds);
        $entity->setArticles($articles);
    }
    
    private function deleteSlides(array $deleteIds)
    {
        $this->repository->deleteSlidesByIds($deleteIds);
    }
}
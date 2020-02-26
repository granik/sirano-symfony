<?php


namespace App\Domain\Backend\Interactor;


use App\Domain\Entity\ClinicalAnalysis\Backend\ClinicalAnalysisRepositoryInterface;
use App\Domain\Entity\ClinicalAnalysis\Backend\DTO\ClinicalAnalysisDto;
use App\Domain\Entity\ClinicalAnalysis\Backend\DTO\ClinicalAnalysisSlideDto;
use App\Domain\Entity\ClinicalAnalysis\ClinicalAnalysis;
use App\Domain\Entity\ClinicalAnalysis\ClinicalAnalysisSlide;
use App\Domain\Entity\Direction\Category;
use App\Domain\Entity\Direction\Direction;
use App\Domain\Entity\Module\Module;
use App\Interactors\NonExistentEntity;

final class ClinicalAnalysisInteractor
{
    use DeleteInteractor;
    
    const UPLOAD_DIRECTORY = 'clinical_analysis';
    
    /**
     * @var ClinicalAnalysisRepositoryInterface
     */
    private $repository;
    /**
     * @var DirectionInteractor
     */
    private $directionInteractor;
    /**
     * @var ArticleInteractor
     */
    private $articleInteractor;
    /**
     * @var ModuleInteractor
     */
    private $moduleInteractor;
    /**
     * @var FileUploader
     */
    private $fileUploader;
    
    /**
     * ClinicalAnalysisInteractor constructor.
     *
     * @param ClinicalAnalysisRepositoryInterface $repository
     * @param DirectionInteractor                 $directionInteractor
     * @param ArticleInteractor                   $articleInteractor
     * @param ModuleInteractor                    $moduleInteractor
     * @param FileUploader                        $fileUploader
     */
    public function __construct(
        ClinicalAnalysisRepositoryInterface $repository,
        DirectionInteractor $directionInteractor,
        ArticleInteractor $articleInteractor,
        ModuleInteractor $moduleInteractor,
        FileUploader $fileUploader
    ) {
        $this->repository          = $repository;
        $this->directionInteractor = $directionInteractor;
        $this->articleInteractor   = $articleInteractor;
        $this->moduleInteractor    = $moduleInteractor;
        $this->fileUploader        = $fileUploader;
    }
    
    public function list(int $page, int $perPage, array $criteria)
    {
        return $this->repository->list($page, $perPage, $criteria);
    }
    
    public function create(ClinicalAnalysisDto $dto)
    {
        $entity = new ClinicalAnalysis();
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
     * @param $dto
     *
     * @return mixed
     * @throws NonExistentEntity
     */
    public function update($dto)
    {
        $entity = $this->find($dto->id);
        
        if (!$entity instanceof ClinicalAnalysis) {
            throw new NonExistentEntity();
        }
        
        $entity = $this->fillEntity($entity, $dto);
        
        $this->updateSlides($entity, $dto->slides);
        $this->updateArticles($entity, $dto->articles);
        
        return $this->repository->update($entity);
    }
    
    /**
     * @param ClinicalAnalysis    $entity
     * @param ClinicalAnalysisDto $dto
     *
     * @return ClinicalAnalysis
     * @throws NonExistentEntity
     */
    private function fillEntity(ClinicalAnalysis $entity, ClinicalAnalysisDto $dto): ClinicalAnalysis
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
    
        $module = $this->moduleInteractor->find($dto->module);
        
        if (!$module instanceof Module) {
            throw new NonExistentEntity();
        }
        
        $entity
            ->setName($dto->name)
            ->setDirection($direction)
            ->setCategory($category)
            ->setModule($module)
            ->setNumber($dto->number)
            ->setCompanyEmail($dto->companyEmail)
            ->setLecturerEmail($dto->lecturerEmail)
            ->setIsActive($dto->isActive);
        
        return $entity;
    }
    
    /**
     * @param ClinicalAnalysis           $entity
     * @param ClinicalAnalysisSlideDto[] $slideDtos
     *
     */
    private function updateSlides(ClinicalAnalysis $entity, array $slideDtos)
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
    
    private function updateArticles(ClinicalAnalysis $entity, array $articleIds)
    {
        $articles = $this->articleInteractor->findByIds($articleIds);
        $entity->setArticles($articles);
    }
    
    /**
     * @param ClinicalAnalysis         $clinicalAnalysis
     * @param ClinicalAnalysisSlideDto $dto
     *
     * @return ClinicalAnalysisSlide
     */
    private function createSlide(
        ClinicalAnalysis $clinicalAnalysis,
        ClinicalAnalysisSlideDto $dto
    ): ClinicalAnalysisSlide {
        $slide = new ClinicalAnalysisSlide();
        $this->fillSlide($clinicalAnalysis, $slide, $dto);
        
        $this->repository->storeSlide($slide);
        
        return $slide;
    }
    
    private function fillSlide(
        ClinicalAnalysis $clinicalAnalysis,
        ClinicalAnalysisSlide $slide,
        ClinicalAnalysisSlideDto $dto
    ) {
        $slide
            ->setClinicalAnalysis($clinicalAnalysis)
            ->setNumber($dto->number)
            ->setName($dto->name)
            ->setImage(
                $this->fileUploader->upload(
                    $dto->imageFile,
                    "slide_{$dto->number}",
                    self::UPLOAD_DIRECTORY . '/' . $clinicalAnalysis->getId()
                )
            );
    }
    
    private function deleteSlides(array $deleteIds)
    {
        $this->repository->deleteSlidesByIds($deleteIds);
    }
}
<?php


namespace App\Domain\Backend\Interactor;


use App\Domain\Entity\Article\Article;
use App\Domain\Entity\Article\Backend\ArticleRepositoryInterface;
use App\Domain\Entity\Article\Backend\DTO\ArticleDto;
use App\Domain\Entity\Direction\Category;
use App\Domain\Entity\Direction\Direction;
use App\Interactors\NonExistentEntity;

final class ArticleInteractor
{
    use DeleteInteractor;
    
    const UPLOAD_DIRECTORY = 'article';
    
    /**
     * @var ArticleRepositoryInterface
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
     * ArticleInteractor constructor.
     *
     * @param ArticleRepositoryInterface $repository
     * @param DirectionInteractor        $directionInteractor
     * @param FileUploader               $fileUploader
     */
    public function __construct(
        ArticleRepositoryInterface $repository,
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
    
    /**
     * @param ArticleDto $dto
     *
     * @return Article
     * @throws NonExistentEntity
     * @throws Exceptions\NoUploadFile
     */
    public function create(ArticleDto $dto)
    {
        $entity = new Article();
        $entity = $this->fillEntity($entity, $dto);
        
        $this->repository->store($entity);

        $this->fillEntityFile($entity, $dto);
        
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
     * @throws Exceptions\NoUploadFile
     */
    public function update($dto)
    {
        $entity = $this->find($dto->id);
    
        if (!$entity instanceof Article) {
            throw new NonExistentEntity();
        }
    
        $entity = $this->fillEntity($entity, $dto);
        $this->fillEntityFile($entity, $dto);

        return $this->repository->update($entity);
    }
    
    public function listAll()
    {
        return $this->repository->listAll();
    }
    
    public function findByIds(array $articleIds)
    {
        return $this->repository->findByIds($articleIds);
    }
    
    /**
     * @param Article    $entity
     * @param ArticleDto $dto
     *
     * @return Article
     * @throws NonExistentEntity
     */
    private function fillEntity(Article $entity, ArticleDto $dto): Article
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
            ->setAuthor($dto->author)
            ->setIsActive($dto->isActive);
        
        return $entity;
    }
    
    /**
     * @param Article    $entity
     * @param ArticleDto $dto
     *
     * @return ArticleInteractor
     * @throws Exceptions\NoUploadFile
     */
    private function fillEntityFile(Article $entity, ArticleDto $dto): ArticleInteractor
    {
        if ($dto->fileFile instanceof File && $dto->fileFile->getUploadedFile() instanceof UploadedFile) {
            $entity->setFile(
                $this->fileUploader->upload(
                    $dto->fileFile,
                    '',
                    self::UPLOAD_DIRECTORY . '/' . $entity->getId()
                )
            );
        }
        
        return $this;
    }
}
<?php


namespace App\Domain\Backend\Interactor;


use App\Domain\Entity\Document\Backend\DocumentRepositoryInterface;
use App\Domain\Entity\Document\Backend\DTO\DocumentDto;
use App\Domain\Entity\Document\Document;
use App\Interactors\NonExistentEntity;

final class DocumentInteractor
{
    use DeleteInteractor;
    
    const UPLOAD_DIRECTORY = 'document';
    
    /**
     * @var DocumentRepositoryInterface
     */
    private $repository;
    
    /**
     * @var FileUploader
     */
    private $fileUploader;
    
    /**
     * DocumentInteractor constructor.
     *
     * @param DocumentRepositoryInterface $repository
     * @param FileUploader                $fileUploader
     */
    public function __construct(DocumentRepositoryInterface $repository, FileUploader $fileUploader)
    {
        $this->repository   = $repository;
        $this->fileUploader = $fileUploader;
    }
    
    public function list(int $page, int $perPage)
    {
        return $this->repository->list($page, $perPage);
    }
    
    /**
     * @param DocumentDto $dto
     *
     * @return Document
     * @throws NonExistentEntity
     * @throws Exceptions\NoUploadFile
     */
    public function create(DocumentDto $dto)
    {
        $entity = new Document();
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
        
        if (!$entity instanceof Document) {
            throw new NonExistentEntity();
        }
        
        $entity = $this->fillEntity($entity, $dto);
        $this->fillEntityFile($entity, $dto);
        
        return $this->repository->update($entity);
    }

    /**
     * @param Document    $entity
     * @param DocumentDto $dto
     *
     * @return Document
     * @throws NonExistentEntity
     */
    private function fillEntity(Document $entity, DocumentDto $dto): Document
    {
        $entity
            ->setName($dto->name)
            ->setIsActive($dto->isActive);
        
        return $entity;
    }
    
    /**
     * @param Document    $entity
     * @param DocumentDto $dto
     *
     * @return DocumentInteractor
     * @throws Exceptions\NoUploadFile
     */
    private function fillEntityFile(Document $entity, DocumentDto $dto): DocumentInteractor
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
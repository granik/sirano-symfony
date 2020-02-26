<?php


namespace App\Domain\Backend\Interactor;


use App\Domain\Entity\Specialty\AdditionalSpecialty;
use App\Domain\Entity\Specialty\AdditionalSpecialtyRepositoryInterface;
use App\Domain\Entity\Specialty\Backend\DTO\SpecialtyDto;
use App\Interactors\NonExistentEntity;

final class AdditionalSpecialtyInteractor
{
    use DeleteInteractor;
    
    /**
     * @var AdditionalSpecialtyRepositoryInterface
     */
    private $repository;
    
    /**
     * AdditionalSpecialtyInteractor constructor.
     *
     * @param AdditionalSpecialtyRepositoryInterface $repository
     */
    public function __construct(AdditionalSpecialtyRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
    
    public function list(int $page, int $perPage, array $criteria = [])
    {
        return $this->repository->list($page, $perPage, $criteria);
    }
    
    public function create(SpecialtyDto $dto)
    {
        $entity = new AdditionalSpecialty();
        $entity = $this->fillEntity($entity, $dto);
        
        $this->repository->store($entity);
        
        return $entity;
    }
    
    public function find($id)
    {
        return $this->repository->find($id);
    }
    
    public function findIdByName($name)
    {
        $specialty = $this->repository->findByName($name);
        
        if (!$specialty instanceof AdditionalSpecialty) {
            return null;
        }
        
        return $specialty->getId();
    }
    
    /**
     * @param SpecialtyDto $dto
     *
     * @return mixed
     * @throws NonExistentEntity
     */
    public function update(SpecialtyDto $dto)
    {
        $entity = $this->find($dto->id);
        
        if (!$entity instanceof AdditionalSpecialty) {
            throw new NonExistentEntity();
        }
        
        $entity = $this->fillEntity($entity, $dto);
        
        return $this->repository->update($entity);
    }
    
    public function getNameById($id)
    {
        if (empty($id)) {
            return '';
        }
        
        $specialty = $this->find($id);
        
        if (!$specialty instanceof AdditionalSpecialty) {
            return '';
        }
        
        return $specialty->getName();
    }
    
    private function fillEntity(AdditionalSpecialty $entity, SpecialtyDto $dto): AdditionalSpecialty
    {
        $entity->setName($dto->name);
        
        return $entity;
    }
}
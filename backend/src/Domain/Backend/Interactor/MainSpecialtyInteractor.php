<?php


namespace App\Domain\Backend\Interactor;


use App\Domain\Entity\Specialty\Backend\DTO\SpecialtyDto;
use App\Domain\Entity\Specialty\MainSpecialty;
use App\Domain\Entity\Specialty\MainSpecialtyRepositoryInterface;
use App\Interactors\NonExistentEntity;

final class MainSpecialtyInteractor
{
    use DeleteInteractor;
    
    /**
     * @var MainSpecialtyRepositoryInterface
     */
    private $repository;
    
    /**
     * MainSpecialtyInteractor constructor.
     *
     * @param MainSpecialtyRepositoryInterface $repository
     */
    public function __construct(MainSpecialtyRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
    
    public function list(int $page, int $perPage, array $criteria = [])
    {
        return $this->repository->list($page, $perPage, $criteria);
    }
    
    public function create(SpecialtyDto $dto)
    {
        $entity = new MainSpecialty();
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
        
        if (!$specialty instanceof MainSpecialty) {
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
    
        if (!$entity instanceof MainSpecialty) {
            throw new NonExistentEntity();
        }
    
        $entity = $this->fillEntity($entity, $dto);

        return $this->repository->update($entity);
    }
    
    public function getNameById($id)
    {
        $specialty = $this->find($id);
    
        if (!$specialty instanceof MainSpecialty) {
            throw new NonExistentEntity();
        }
        
        return $specialty->getName();
    }
    
    private function fillEntity(MainSpecialty $entity, SpecialtyDto $dto): MainSpecialty
    {
        $entity->setName($dto->name);
        
        return $entity;
    }
}
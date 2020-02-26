<?php

namespace App\Domain\Interactor;


use App\Domain\Backend\Interactor\DeleteInteractor;
use App\Domain\Entity\City;
use App\Domain\Entity\CityRepositoryInterface;
use App\Interactors\NonExistentEntity;

final class CityInteractor
{
    use DeleteInteractor;
    
    /**
     * @var CityRepositoryInterface
     */
    private $repository;
    
    /**
     * CityInteractor constructor.
     *
     * @param CityRepositoryInterface $repository
     */
    public function __construct(CityRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function list(int $page, int $perPage)
    {
        return $this->repository->list($page, $perPage);
    }
    
    public function find($id): ?City
    {
        return $this->repository->find($id);
    }
    
    /**
     * @param CityDto $cityDto
     *
     * @return mixed
     * @throws NonExistentEntity
     */
    public function update(CityDto $cityDto)
    {
        $city = $this->find($cityDto->id);
        
        if (!$city instanceof City) {
            throw new NonExistentEntity();
        }
        
        $city->setName($cityDto->name);
        
        return $this->repository->update($city);
    }
    
    public function create(CityDto $cityDto): City
    {
        $city = new City();
        
        $city->setName($cityDto->name);
        
        $this->repository->store($city);
        
        return $city;
    }

    public function listAll()
    {
        return $this->repository->listAll();
    }
}
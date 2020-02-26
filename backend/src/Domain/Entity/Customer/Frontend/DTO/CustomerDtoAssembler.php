<?php


namespace App\Domain\Entity\Customer\Frontend\DTO;


use App\Domain\Entity\Customer\Customer;
use App\Domain\Entity\Customer\Interactor\CustomerCityNameInteractor;
use App\DTO\DtoAssembler;

final class CustomerDtoAssembler extends DtoAssembler
{
    /**
     * @var CustomerCityNameInteractor
     */
    private $customerCityNameInteractor;
    
    /**
     * CustomerDtoAssembler constructor.
     *
     * @param CustomerCityNameInteractor $customerCityNameInteractor
     */
    public function __construct(CustomerCityNameInteractor $customerCityNameInteractor)
    {
        $this->customerCityNameInteractor = $customerCityNameInteractor;
    }
    
    protected function createDto()
    {
        return new CustomerDto();
    }
    
    /**
     * @param CustomerDto $dto
     * @param Customer    $entity
     */
    protected function fill($dto, $entity)
    {
        $dto->name = $entity->getLastname() . ' ' . $entity->getName();
        if (!empty($entity->getMiddlename())) {
            $dto->name .= ' ' . $entity->getMiddlename();
        }
        
        $dto->cityName = $this->customerCityNameInteractor->getCityName($entity);
    }
}
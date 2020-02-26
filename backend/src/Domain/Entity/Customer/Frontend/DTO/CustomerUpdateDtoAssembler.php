<?php


namespace App\Domain\Entity\Customer\Frontend\DTO;


use App\Domain\Backend\Interactor\File;
use App\Domain\Entity\Customer\Customer;
use App\Domain\Entity\Customer\Interactor\CustomerCityNameInteractor;
use App\DTO\DtoAssembler;

final class CustomerUpdateDtoAssembler extends DtoAssembler
{
    /**
     * @var CustomerCityNameInteractor
     */
    private $customerCityNameInteractor;
    
    /**
     * CustomerUpdateDtoAssembler constructor.
     *
     * @param CustomerCityNameInteractor $customerCityNameInteractor
     */
    public function __construct(CustomerCityNameInteractor $customerCityNameInteractor)
    {
        $this->customerCityNameInteractor = $customerCityNameInteractor;
    }
    
    protected function createDto()
    {
        return new CustomerUpdateDto();
    }
    
    /**
     * @param CustomerUpdateDto $dto
     * @param Customer          $entity
     */
    protected function fill($dto, $entity)
    {
        $dto->name                  = $entity->getName();
        $dto->middlename            = $entity->getMiddlename();
        $dto->lastname              = $entity->getLastname();
        $dto->email                 = $entity->getEmail();
        $dto->phone                 = $entity->getPhone();
        $dto->directionId           = $entity->getDirectionId();
        $dto->avatar                = $entity->getAvatar();
        $dto->avatarFile            = empty($entity->getAvatar()) ? null : (new File())->setFilePath($entity->getAvatar());
        $dto->mainSpecialtyId       = $entity->getMainSpecialtyId();
        $dto->additionalSpecialtyId = $entity->getAdditionalSpecialtyId();
        
        $city              = $this->customerCityNameInteractor->findById($entity->getCityId());
        $dto->cityName     = $city->getName();
        $dto->kladrId      = $city->getKladrId();
        $dto->country      = $city->getCountry();
        $dto->fullCityName = $city->getFullName();
    }
}
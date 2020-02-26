<?php

namespace App\DTO;


use App\Domain\Entity\Customer\Backend\DTO\CustomerDto;
use App\Domain\Entity\Customer\Customer;
use App\Domain\Entity\Customer\Interactor\CustomerCityNameInteractor;
use App\Domain\Interactor\User\User;
use App\Domain\Interactor\UserInteractor;

class CustomerDtoAssembler extends DtoAssembler
{
    /**
     * @var UserInteractor
     */
    private $userInteractor;
    /**
     * @var CustomerCityNameInteractor
     */
    private $customerCityNameInteractor;
    
    /**
     * CustomerDtoAssembler constructor.
     *
     * @param UserInteractor             $userInteractor
     * @param CustomerCityNameInteractor $customerCityNameInteractor
     */
    public function __construct(UserInteractor $userInteractor, CustomerCityNameInteractor $customerCityNameInteractor)
    {
        $this->userInteractor             = $userInteractor;
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
        $dto->id                    = $entity->getId();
        $dto->name                  = $entity->getName();
        $dto->middlename            = $entity->getMiddlename();
        $dto->lastname              = $entity->getLastname();
        $dto->email                 = $entity->getEmail();
        $dto->phone                 = $entity->getPhone();
        $dto->mainSpecialtyId       = $entity->getMainSpecialtyId();
        $dto->additionalSpecialtyId = $entity->getAdditionalSpecialtyId();
        $dto->directionId           = $entity->getDirectionId();
        
        $city              = $this->customerCityNameInteractor->findById($entity->getCityId());
        $dto->country      = $city->getCountry();
        $dto->cityName     = $city->getName();
        $dto->fullCityName = $city->getFullName();
        $dto->kladrId      = $city->getKladrId();
        
        $user = $this->userInteractor->findByCustomer($entity);
        
        if ($user instanceof User) {
            $dto->admin           = $user->isAdmin();
            $dto->registeredAt    = $user->getRegisteredAt()->format('d.m.Y');
            $dto->sendingDateTime = $user->getSendingDateTime() instanceof \DateTime
                ? $user->getSendingDateTime()->format('d.m.Y')
                : '';
            $dto->addedFrom       = $user->getAddedFromName();
            $dto->isActive        = $user->isActive() ? 'Активирован' : 'Не активирован';
            $dto->sendingCounter  = $user->getSendingCounter();
        }
    }
}
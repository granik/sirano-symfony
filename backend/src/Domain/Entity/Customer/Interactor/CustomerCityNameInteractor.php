<?php


namespace App\Domain\Entity\Customer\Interactor;


use App\Domain\Entity\Customer\Customer;
use App\Domain\Entity\Customer\CustomerCity;
use App\Domain\Entity\Customer\CustomerCityRepositoryInterface;
use Doctrine\ORM\EntityNotFoundException;

final class CustomerCityNameInteractor
{
    /**
     * @var CustomerCityRepositoryInterface
     */
    private $cityRepository;
    
    /**
     * CustomerCityNameInteractor constructor.
     *
     * @param CustomerCityRepositoryInterface $cityRepository
     */
    public function __construct(CustomerCityRepositoryInterface $cityRepository)
    {
        $this->cityRepository = $cityRepository;
    }
    
    /**
     * @param Customer $customer
     *
     * @return string
     * @throws EntityNotFoundException
     */
    public function getCityName(Customer $customer)
    {
        $cityId = $customer->getCityId();
        $city   = $this->findById($cityId);
        
        if (!$city instanceof CustomerCity) {
            throw new EntityNotFoundException();
        }
        
        return $city->getName();
    }
    
    /**
     * @param Customer $customer
     *
     * @return string
     * @throws EntityNotFoundException
     */
    public function getFullCityName(Customer $customer)
    {
        $cityId = $customer->getCityId();
        $city   = $this->findById($cityId);
        
        if (!$city instanceof CustomerCity) {
            throw new EntityNotFoundException();
        }
        
        return "{$city->getCountry()}, {$city->getFullName()}";
    }
    
    public function findById($cityId)
    {
        return $this->cityRepository->find($cityId);
    }
}
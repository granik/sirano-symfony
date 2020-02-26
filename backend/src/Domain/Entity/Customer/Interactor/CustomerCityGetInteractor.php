<?php


namespace App\Domain\Entity\Customer\Interactor;


use App\Domain\Entity\Customer\Backend\CityParserInterface;
use App\Domain\Entity\Customer\Backend\DTO\CustomerCityDto;
use App\Domain\Entity\Customer\CustomerCity;
use App\Domain\Entity\Customer\CustomerCityRepositoryInterface;

final class CustomerCityGetInteractor
{
    /**
     * @var CustomerCityRepositoryInterface
     */
    private $customerCityRepository;
    /**
     * @var CityParserInterface
     */
    private $cityParser;
    
    /**
     * CustomerCityGetInteractor constructor.
     *
     * @param CustomerCityRepositoryInterface $customerCityRepository
     * @param CityParserInterface             $cityParser
     */
    public function __construct(
        CustomerCityRepositoryInterface $customerCityRepository,
        CityParserInterface $cityParser
    ) {
        $this->customerCityRepository = $customerCityRepository;
        $this->cityParser             = $cityParser;
    }
    
    /**
     * @param string      $country
     * @param string      $name
     * @param string      $fullName
     * @param string|null $kladrId
     *
     * @return CustomerCity
     */
    public function getCity(string $country, string $name, string $fullName, ?string $kladrId): CustomerCity
    {
        if (!empty($kladrId)) {
            $city = $this->customerCityRepository->findByKladrId($kladrId);
        } else {
            $city = $this->customerCityRepository->finByParams($country, $name, $fullName);
        }
        
        if ($city instanceof CustomerCity) {
            return $city;
        }
        
        $city = new CustomerCity($country, $name, $fullName, $kladrId);
        $this->customerCityRepository->store($city);
        
        return $city;
    }
    
    /**
     * @param string $name
     *
     * @return CustomerCityDto
     * @throws CityNotFoundException
     */
    public function getCityFromString(string $name): CustomerCityDto
    {
        $cityDto = $this->cityParser->getCityData($name);
        
        if (!$cityDto instanceof CustomerCityDto) {
            throw new CityNotFoundException();
        }
        
        return $cityDto;
    }
}
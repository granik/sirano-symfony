<?php


namespace App\Domain\Entity\Customer;


interface CustomerCityRepositoryInterface
{
    public function find(int $cityId): ?CustomerCity;
    
    public function finByParams(string $country, string $name, string $fullName);
    
    public function store(CustomerCity $city);
    
    public function findByKladrId(string $kladrId);
}
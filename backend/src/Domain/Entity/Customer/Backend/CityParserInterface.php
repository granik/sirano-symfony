<?php


namespace App\Domain\Entity\Customer\Backend;


use App\Domain\Entity\Customer\Backend\DTO\CustomerCityDto;

interface CityParserInterface
{
    public function getCityData(string $query): ?CustomerCityDto;
}
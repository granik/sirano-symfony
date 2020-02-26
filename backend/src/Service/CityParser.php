<?php


namespace App\Service;


use App\Domain\Entity\Customer\Backend\CityParserInterface;
use App\Domain\Entity\Customer\Backend\DTO\CustomerCityDto;
use App\Service\Dadata\SuggestClient;

final class CityParser implements CityParserInterface
{
    /**
     * @var SuggestClient
     */
    private $suggestClient;
    
    /**
     * CityParser constructor.
     *
     * @param SuggestClient $suggestClient
     */
    public function __construct(SuggestClient $suggestClient)
    {
        $this->suggestClient = $suggestClient;
    }
    
    public function getCityData(string $query): ?CustomerCityDto
    {
        $response = $this->suggestClient->suggest(
            'address',
            [
                'query'      => $query,
                'from_bound' => ['value' => 'city'],
                'to_bound'   => ['value' => 'settlement'],
            ]);
        
        if (!isset($response['suggestions'])) {
            return null;
        }
        
        $suggestions = array_filter($response['suggestions'], function ($value) {
            return $value['data']['city_district'] === null && $value['data']['fias_level'] !== '65';
        });
        
        $data = reset($suggestions);
        
        if ($data['data']['settlement']) {
            $name = $data['data']['settlement_with_type'];
        } else {
            if ($data['data']['city_type'] !== 'г') {
                $name = $data['data']['city_with_type'];
            } else {
                $name = $data['data']['city'];
            }
        }
        
        $fullName = '';
        
        if ($data['data']['region_type'] !== 'г') {
            $fullName = $data['data']['region'] . ' ' . $data['data']['region_type_full'] . ', ';
        }
        
        if ($data['data']['area']) {
            $fullName = $fullName . $data['data']['area'] . ' ' . $data['data']['area_type_full'] . ', ';
        }
        
        if ($data['data']['city_district']) {
            $fullName = $fullName . $data['data']['city_district'] . ' ' . $data['data']['city_district_type_full'] . ', ';
        }
        
        $fullName .= $name;
        
        $cityDto           = new CustomerCityDto();
        $cityDto->kladrId  = $data['data']['kladr_id'];
        $cityDto->country  = $data['data']['country'];
        $cityDto->name     = $name;
        $cityDto->fullName = $fullName;
        
        return $cityDto;
    }
}
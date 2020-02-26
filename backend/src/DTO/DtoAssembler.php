<?php

namespace App\DTO;


use DateTime;

abstract class DtoAssembler
{
    const MONTHS = [
        '01' => 'января',
        '02' => 'февраля',
        '03' => 'марта',
        '04' => 'апреля',
        '05' => 'мая',
        '06' => 'июня',
        '07' => 'июля',
        '08' => 'августа',
        '09' => 'сентября',
        '10' => 'октября',
        '11' => 'ноября',
        '12' => 'декабря',
    ];

    abstract protected function createDto();
    abstract protected function fill($dto, $entity);

    public function assembleList($list)
    {
        $dtoList = [];

        foreach ($list as $entityItem) {
            $dtoList[] = $this->assemble($entityItem);
        }

        return $dtoList;
    }

    public function assemble($entity)
    {
        $dto = $this->createDto();
        $this->fill($dto, $entity);

        return $dto;
    }

    protected static function getRussianDate(DateTime $dateTime)
    {
        $day   = $dateTime->format('j');
        $month = self::MONTHS[$dateTime->format('m')];
        $year  = $dateTime->format('Y');

        return "$day $month $year";
    }
}
<?php


namespace App\Domain\Entity\Conference\Frontend;


interface ConferenceSeriesRepositoryInterface
{
    public function list(int $limit, $direction);
}
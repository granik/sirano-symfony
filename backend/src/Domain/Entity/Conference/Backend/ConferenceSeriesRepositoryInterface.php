<?php


namespace App\Domain\Entity\Conference\Backend;


use App\Domain\Entity\Conference\ConferenceSeries;

interface ConferenceSeriesRepositoryInterface
{
    public function list(int $page, int $perPage);
    
    public function store(ConferenceSeries $conferenceSeries);
    
    public function find($id);
    
    public function update(ConferenceSeries $conferenceSeries);
    
    public function delete($entity);
    
    public function listAll();
}
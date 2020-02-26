<?php

namespace App\Domain\Entity\Conference\Backend;


use App\Domain\Entity\Conference\Conference;

interface ConferenceRepositoryInterface
{
    public function list(int $page, int $perPage, array $criteria);
    
    public function store(Conference $conference);
    
    public function find($id);
    
    public function update(Conference $conference);
    
    public function delete($entity);
}
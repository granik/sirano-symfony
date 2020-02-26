<?php

namespace App\Webinar\Backend;


use App\Webinar\Webinar;

interface WebinarRepositoryInterface
{
    public function list(int $page, int $perPage, array $criteria);
    
    public function find($id): ?Webinar;
    
    public function update(Webinar $webinar);
    
    public function store(Webinar $webinar);
    
    public function delete($entity);
}
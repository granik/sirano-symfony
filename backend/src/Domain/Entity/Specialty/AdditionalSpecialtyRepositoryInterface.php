<?php


namespace App\Domain\Entity\Specialty;


interface AdditionalSpecialtyRepositoryInterface
{
    public function list(int $page, int $perPage, array $criteria);
    
    public function store(AdditionalSpecialty $entity);
    
    public function find($id);
    
    public function findByName($name);
    
    public function update(AdditionalSpecialty $entity);
    
    public function delete($entity);
    
    public function customerFormlist();
}
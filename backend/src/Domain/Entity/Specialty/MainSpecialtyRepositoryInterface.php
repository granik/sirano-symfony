<?php


namespace App\Domain\Entity\Specialty;


interface MainSpecialtyRepositoryInterface
{
    public function list(int $page, int $perPage, array $criteria);
    
    public function store(MainSpecialty $entity);
    
    public function find($id);
    
    public function findByName($name);
    
    public function update(MainSpecialty $entity);
    
    public function delete($entity);
    
    public function customerFormlist();
}
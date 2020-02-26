<?php


namespace App\Domain\Entity\Document\Backend;


use App\Domain\Entity\Document\Document;

interface DocumentRepositoryInterface
{
    public function list(int $page, int $perPage);
    
    public function store(Document $entity);
    
    public function update(Document $entity);
    
    public function find($id);
    
    public function delete($entity);
}
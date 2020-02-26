<?php


namespace App\Domain\Entity\PresidiumMember\Backend;


use App\Domain\Entity\PresidiumMember\PresidiumMember;

interface PresidiumMemberRepositoryInterface
{
    public function list(int $page, int $perPage);
    
    public function store(PresidiumMember $entity);
    
    public function find($id);
    
    public function update(PresidiumMember $entity);
    
    public function delete($entity);
}
<?php

namespace App\Domain\Entity\Direction;


interface DirectionRepositoryInterface
{
    public function list(int $page, int $perPage);
    
    public function store(Direction $direction): Direction;
    
    public function update(Direction $direction);
    
    public function find($id): ?Direction;

    public function activeList();
    
    public function delete($entity);
    
    public function mainPageList($limit);
}
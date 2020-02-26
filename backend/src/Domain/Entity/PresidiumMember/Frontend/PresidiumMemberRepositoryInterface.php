<?php


namespace App\Domain\Entity\PresidiumMember\Frontend;


interface PresidiumMemberRepositoryInterface
{
    public function list(int $page, int $perPage);
}
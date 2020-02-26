<?php


namespace App\Domain\Entity\Customer\Frontend;


interface CustomerRepositoryInterface
{
    public function list(int $page, int $perPage, ?string $query);
}
<?php


namespace App\Domain\Entity\Document\Frontend;


interface DocumentRepositoryInterface
{
    public function list(int $page, int $perPage);
}
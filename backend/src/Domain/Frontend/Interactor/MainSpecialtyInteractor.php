<?php


namespace App\Domain\Frontend\Interactor;


use App\Domain\Entity\Specialty\MainSpecialtyRepositoryInterface;

final class MainSpecialtyInteractor
{
    /**
     * @var MainSpecialtyRepositoryInterface
     */
    private $repository;
    
    /**
     * MainSpecialtyInteractor constructor.
     *
     * @param MainSpecialtyRepositoryInterface $repository
     */
    public function __construct(MainSpecialtyRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
    
    public function list()
    {
        return $this->repository->customerFormlist();
    }
}
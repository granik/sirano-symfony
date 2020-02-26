<?php


namespace App\Domain\Frontend\Interactor;


use App\Domain\Entity\Specialty\AdditionalSpecialtyRepositoryInterface;

final class AdditionalSpecialtyInteractor
{
    /**
     * @var AdditionalSpecialtyRepositoryInterface
     */
    private $repository;
    
    /**
     * AdditionalSpecialtyInteractor constructor.
     *
     * @param AdditionalSpecialtyRepositoryInterface $repository
     */
    public function __construct(AdditionalSpecialtyRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
    
    public function list()
    {
        return $this->repository->customerFormlist();
    }
}
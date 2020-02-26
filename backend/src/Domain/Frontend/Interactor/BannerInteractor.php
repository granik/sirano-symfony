<?php


namespace App\Domain\Frontend\Interactor;


use App\Domain\Entity\Banner\Frontend\BannerRepositoryInterface;

final class BannerInteractor
{
    /**
     * @var BannerRepositoryInterface
     */
    private $repository;
    
    /**
     * BannerInteractor constructor.
     *
     * @param BannerRepositoryInterface $repository
     */
    public function __construct(BannerRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
    
    public function list()
    {
        return $this->repository->list();
    }
}
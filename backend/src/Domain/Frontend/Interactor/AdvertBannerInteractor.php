<?php


namespace App\Domain\Frontend\Interactor;


use App\Domain\Entity\AdvertBanner\Frontend\AdvertBannerRepositoryInterface;

final class AdvertBannerInteractor
{
    /**
     * @var AdvertBannerRepositoryInterface
     */
    private $repository;
    
    /**
     * AdvertBannerInteractor constructor.
     *
     * @param AdvertBannerRepositoryInterface $repository
     */
    public function __construct(AdvertBannerRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
    
    public function list()
    {
        return $this->repository->list();
    }
}
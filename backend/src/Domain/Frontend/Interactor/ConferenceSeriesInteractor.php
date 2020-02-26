<?php


namespace App\Domain\Frontend\Interactor;


use App\Domain\Entity\Conference\Frontend\ConferenceSeriesRepositoryInterface;

final class ConferenceSeriesInteractor
{
    /**
     * @var ConferenceSeriesRepositoryInterface
     */
    private $repository;
    
    /**
     * ConferenceSeriesInteractor constructor.
     *
     * @param ConferenceSeriesRepositoryInterface $repository
     */
    public function __construct(ConferenceSeriesRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
    
    public function list(int $limit, $direction)
    {
        return $this->repository->list($limit, $direction);
    }
}
<?php


namespace App\Domain\Backend\Interactor;


use App\Domain\Entity\Conference\Backend\ConferenceSeriesRepositoryInterface;
use App\Domain\Entity\Conference\Backend\DTO\ConferenceSeriesDto;
use App\Domain\Entity\Conference\ConferenceSeries;
use App\Domain\Entity\Direction\Direction;
use App\Interactors\NonExistentEntity;

final class ConferenceSeriesInteractor
{
    use DeleteInteractor;
    
    /**
     * @var ConferenceSeriesRepositoryInterface
     */
    private $repository;
    /**
     * @var DirectionInteractor
     */
    private $directionInteractor;
    
    /**
     * ConferenceSeriesInteractor constructor.
     *
     * @param ConferenceSeriesRepositoryInterface $repository
     * @param DirectionInteractor                 $directionInteractor
     */
    public function __construct(
        ConferenceSeriesRepositoryInterface $repository,
        DirectionInteractor $directionInteractor
    ) {
        $this->repository          = $repository;
        $this->directionInteractor = $directionInteractor;
    }
    
    public function list(int $page, int $perPage)
    {
        return $this->repository->list($page, $perPage);
    }
    
    /**
     * @param ConferenceSeriesDto $conferenceSeriesDto
     *
     * @return ConferenceSeries
     * @throws NonExistentEntity
     */
    public function create(ConferenceSeriesDto $conferenceSeriesDto)
    {
        $conferenceSeries = new ConferenceSeries();
        
        $conferenceSeries = $this->fillEntity($conferenceSeries, $conferenceSeriesDto);
        
        $this->repository->store($conferenceSeries);
        
        return $conferenceSeries;
    }
    
    /**
     * @param ConferenceSeriesDto $conferenceSeriesDto
     *
     * @return mixed
     * @throws NonExistentEntity
     */
    public function update(ConferenceSeriesDto $conferenceSeriesDto)
    {
        $conferenceSeries = $this->find($conferenceSeriesDto->id);
        
        if (!$conferenceSeries instanceof ConferenceSeries) {
            throw new NonExistentEntity();
        }
        
        $conferenceSeries = $this->fillEntity($conferenceSeries, $conferenceSeriesDto);
        
        return $this->repository->update($conferenceSeries);
    }
    
    public function find($id)
    {
        return $this->repository->find($id);
    }
    
    public function listAll()
    {
        return $this->repository->listAll();
    }
    
    /**
     * @param ConferenceSeries    $conferenceSeries
     * @param ConferenceSeriesDto $conferenceSeriesDto
     *
     * @return ConferenceSeries
     * @throws NonExistentEntity
     */
    private function fillEntity(
        ConferenceSeries $conferenceSeries,
        ConferenceSeriesDto $conferenceSeriesDto
    ): ConferenceSeries {
        $direction = $this->directionInteractor->find($conferenceSeriesDto->direction);
        
        if (!$direction instanceof Direction) {
            throw new NonExistentEntity();
        }
        
        $conferenceSeries
            ->setName($conferenceSeriesDto->name)
            ->setDirection($direction);
        
        return $conferenceSeries;
    }
}
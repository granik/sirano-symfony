<?php


namespace App\Domain\Entity\Conference\Frontend\DTO;


use App\Domain\Entity\Conference\ConferenceSeries;
use App\Domain\Entity\Conference\DTO\ConferenceDtoAssembler;
use App\DTO\DtoAssembler;

final class ConferenceSeriesDtoAssembler extends DtoAssembler
{
    /**
     * @var string
     */
    private $fileUrlPrefix;
    /**
     * @var ConferenceDtoAssembler
     */
    private $conferenceDtoAssembler;
    
    /**
     * ConferenceSeriesDtoAssembler constructor.
     *
     * @param ConferenceDtoAssembler $conferenceDtoAssembler
     * @param string                 $fileUrlPrefix
     */
    public function __construct(ConferenceDtoAssembler $conferenceDtoAssembler, string $fileUrlPrefix)
    {
        $this->conferenceDtoAssembler = $conferenceDtoAssembler;
        $this->fileUrlPrefix          = $fileUrlPrefix;
    }
    
    protected function createDto()
    {
        return new ConferenceSeriesDto();
    }
    
    /**
     * @param ConferenceSeriesDto $dto
     * @param ConferenceSeries    $entity
     */
    protected function fill($dto, $entity)
    {
        $dto->name          = $entity->getName();
        $dto->directionName = $entity->getDirection()->getName();
        
        $image      = $entity->getDirection()->getImage();
        $dto->image = empty($image) ? '' : $this->fileUrlPrefix . '/' . $image;
        
        $dto->conferences = $this->conferenceDtoAssembler->assembleList($entity->getConferences());
    }
}
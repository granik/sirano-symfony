<?php

namespace App\Domain\Entity\Conference\DTO;


use App\Domain\Entity\Conference\Conference;
use App\Domain\Entity\Conference\ConferenceSeries;
use App\Domain\Frontend\Interactor\ConferenceFrontendInteractor;
use App\DTO\ConferenceProgramDtoAssembler;
use App\DTO\DtoAssembler;
use App\Security\SymfonyUser;
use Symfony\Component\Security\Core\Security;

final class ConferenceDtoAssembler extends DtoAssembler
{
    /**
     * @var ConferenceProgramDtoAssembler
     */
    private $programDtoAssembler;
    /**
     * @var Security
     */
    private $security;
    /**
     * @var ConferenceFrontendInteractor
     */
    private $conferenceFrontendInteractor;
    
    /**
     * ConferenceDtoAssembler constructor.
     *
     * @param ConferenceProgramDtoAssembler $programDtoAssembler
     * @param ConferenceFrontendInteractor  $conferenceFrontendInteractor
     * @param Security                      $security
     */
    public function __construct(
        ConferenceProgramDtoAssembler $programDtoAssembler,
        ConferenceFrontendInteractor $conferenceFrontendInteractor,
        Security $security
    ) {
        $this->programDtoAssembler          = $programDtoAssembler;
        $this->security                     = $security;
        $this->conferenceFrontendInteractor = $conferenceFrontendInteractor;
    }
    
    protected function createDto()
    {
        return new ConferenceDto();
    }
    
    /**
     * @param ConferenceDto $dto
     * @param Conference    $entity
     */
    protected function fill($dto, $entity)
    {
        $dto->id            = $entity->getId();
        $dto->name          = $entity->getName();
        $dto->description   = $entity->getDescription();
        $dto->startDateTime = $entity->getStartDateTime();
        $dto->startDate     = self::getRussianDate($entity->getStartDateTime());
        $dto->startTime     = $entity->getStartDateTime()->format('H:i');
        $dto->endDateTime   = $entity->getEndDateTime();
        $dto->score         = $entity->getScore();
        $dto->isActive      = $entity->isActive();
        $dto->direction     = $entity->getDirection()->getId();
        $dto->directionName = $entity->getDirection()->getName();
        $dto->address       = $entity->getAddress();
        $dto->city          = $entity->getCity()->getId();
        $dto->cityName      = $entity->getCity()->getName();
        $dto->isArchive     = $entity->isArchive();
        $dto->series        = $entity->getSeries() instanceof ConferenceSeries ? $entity->getSeries()->getId() : null;
        
        $dto->programs = $this->programDtoAssembler->assembleList($entity->getPrograms());
        
        $user = $this->security->getUser();
        
        if ($user instanceof SymfonyUser) {
            $dto->isSubscribed = $this->conferenceFrontendInteractor->isUserSubscribed($entity, $user->getUser());
        } else {
            $dto->isSubscribed = false;
        }
    }
}
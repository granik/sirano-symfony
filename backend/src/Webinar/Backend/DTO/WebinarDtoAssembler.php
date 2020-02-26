<?php

namespace App\Webinar\Backend\DTO;


use App\DTO\DtoAssembler;
use App\Webinar\DTO\WebinarDto;
use App\Webinar\Frontend\Interactor\WebinarInteractor;
use App\Webinar\Webinar;
use Symfony\Component\Security\Core\Security;

final class WebinarDtoAssembler extends DtoAssembler
{
    /**
     * @var WebinarInteractor
     */
    private $webinarInteractor;
    /**
     * @var Security
     */
    private $security;
    
    /**
     * WebinarDtoAssembler constructor.
     *
     * @param WebinarInteractor $webinarInteractor
     * @param Security          $security
     */
    public function __construct(WebinarInteractor $webinarInteractor, Security $security)
    {
        $this->webinarInteractor = $webinarInteractor;
        $this->security          = $security;
    }
    
    protected function createDto()
    {
        return new WebinarDto();
    }
    
    /**
     * @param WebinarDto $dto
     * @param Webinar    $entity
     */
    protected function fill($dto, $entity)
    {
        $dto->id                = $entity->getId();
        $dto->name              = $entity->getName();
        $dto->description       = $entity->getDescription();
        $dto->youtubeCode       = $entity->getYoutubeCode();
        $dto->startDatetime     = $entity->getStartDatetime();
        $dto->jsStartDatetime   = $entity->getStartDatetime()->format('U000');
        $dto->startDate         = self::getRussianDate($entity->getStartDatetime());
        $dto->startTime         = $entity->getStartDatetime()->format('H:i');
        $dto->endDatetime       = $entity->getEndDatetime();
        $dto->subject           = $entity->getSubject();
        $dto->score             = $entity->getScore();
        $dto->confirmationTime1 = $entity->getConfirmationTime1();
        $dto->confirmationTime2 = $entity->getConfirmationTime2();
        $dto->confirmationTime3 = $entity->getConfirmationTime3();
        $dto->email             = $entity->getEmail();
        $dto->isActive          = $entity->isActive();
        $dto->direction         = $entity->getDirection()->getId();
        $dto->directionName     = $entity->getDirection()->getName();
        $dto->isComplete        = $entity->isComplete();
        $dto->isStarted         = $entity->isStarted();
        $dto->isNotStarted      = $entity->isNotStarted();
    }
}
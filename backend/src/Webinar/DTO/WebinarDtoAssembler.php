<?php

namespace App\Webinar\DTO;


use App\Domain\Frontend\Interactor\Exceptions\UserIsNotCustomer;
use App\DTO\DtoAssembler;
use App\Security\SymfonyUser;
use App\Webinar\Frontend\Interactor\WebinarInteractor;
use App\Webinar\Webinar;
use DateTime;
use DateTimeZone;
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
     *
     * @throws UserIsNotCustomer
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
        
        $user = $this->security->getUser();
        
        if ($user instanceof SymfonyUser) {
            $dto->isSubscribed = $this->webinarInteractor->isUserSubscribed($entity, $user->getUser());
        } else {
            $dto->isSubscribed = false;
        }
        
        $utcTimezone = new DateTimeZone('UTC');
        
        $dto->jsEndDatetime       = (clone $entity->getEndDatetime())->setTimezone($utcTimezone)->format('U000');
        $dto->jsConfirmationTime1 = (clone $entity->getConfirmationTime1())->setTimezone($utcTimezone)->format('U000');
        $dto->jsConfirmationTime2 = (clone $entity->getConfirmationTime2())->setTimezone($utcTimezone)->format('U000');
        $dto->jsConfirmationTime3 = $entity->getConfirmationTime3() instanceof DateTime
            ? (clone $entity->getConfirmationTime3())->setTimezone($utcTimezone)->format('U000')
            : 0;
    }
}
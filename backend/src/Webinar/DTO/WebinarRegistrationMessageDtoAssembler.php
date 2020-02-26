<?php


namespace App\Webinar\DTO;


use App\Domain\Service\UrlGeneratorInterface;
use App\DTO\DtoAssembler;
use App\Webinar\Webinar;
use DateTime;
use DateTimeZone;

final class WebinarRegistrationMessageDtoAssembler extends DtoAssembler
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;
    
    /**
     * WebinarRegistrationMessageDtoAssembler constructor.
     *
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }
    
    protected function createDto()
    {
        return new WebinarRegistrationMessage();
    }
    
    /**
     * @param WebinarRegistrationMessage $dto
     * @param Webinar                    $entity
     *
     * @return void
     * @throws \Exception
     */
    protected function fill($dto, $entity)
    {
        $dto->name        = $entity->getName();
        $dto->subject     = $entity->getSubject();
        $dto->description = $entity->getDescription();
        $dto->direction   = $entity->getDirection()->getName();
        $dto->link        = $this->urlGenerator->urlForWebinar($entity);
        
        $startDatetime = $entity->getStartDatetime();
        $dto->date     = self::getRussianDate($startDatetime);
        $dto->time     = $startDatetime->format('H:i');
        
        $dto->dtstamp = (new DateTime())->format('Ymd\\THis\\Z');
        
        $moscowTimezone = new DateTimeZone('Europe/Moscow');
        $utcTimezone    = new DateTimeZone('UTC');
        
        $startDatetimeString = $startDatetime->format('Y-m-d H:i');
        $startDatetimeTz     = new DateTime($startDatetimeString, $moscowTimezone);
        $startDatetimeTz->setTimezone($utcTimezone);
        
        $endDatetimeString = $entity->getEndDatetime()->format('Y-m-d H:i');
        $endDatetimeTz     = new DateTime($endDatetimeString, $moscowTimezone);
        $endDatetimeTz->setTimezone($utcTimezone);
        
        $dto->dtstart = $startDatetimeTz->format('Ymd\\THis\\Z');
        $dto->dtend   = $endDatetimeTz->format('Ymd\\THis\\Z');
    }
}
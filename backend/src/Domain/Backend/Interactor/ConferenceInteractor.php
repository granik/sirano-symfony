<?php

namespace App\Domain\Backend\Interactor;


use App\Domain\Entity\City;
use App\Domain\Entity\Conference\Backend\ConferenceRepositoryInterface;
use App\Domain\Entity\Conference\Backend\DTO\ConferenceSubscriberDto;
use App\Domain\Entity\Conference\Conference;
use App\Domain\Entity\Conference\ConferenceProgram;
use App\Domain\Entity\Conference\ConferenceProgramRepositoryInterface;
use App\Domain\Entity\Conference\ConferenceSeries;
use App\Domain\Entity\Conference\ConferenceSubscriber;
use App\Domain\Entity\Conference\ConferenceSubscriberRepositoryInterface;
use App\Domain\Entity\Conference\DTO\ConferenceDto;
use App\Domain\Entity\Conference\DTO\ConferenceProgramDto;
use App\Domain\Entity\Customer\Customer;
use App\Domain\Entity\Customer\Interactor\CustomerCityNameInteractor;
use App\Domain\Entity\Direction\Direction;
use App\Domain\Interactor\CityInteractor;
use App\Interactors\NonExistentEntity;

final class ConferenceInteractor
{
    use DeleteInteractor;
    
    /**
     * @var ConferenceRepositoryInterface
     */
    private $repository;
    /**
     * @var DirectionInteractor
     */
    private $directionInteractor;
    /**
     * @var CityInteractor
     */
    private $cityInteractor;
    /**
     * @var ConferenceProgramRepositoryInterface
     */
    private $conferenceProgramRepository;
    /**
     * @var ConferenceSubscriberRepositoryInterface
     */
    private $conferenceSubscriberRepository;
    /**
     * @var TableWriterInterface
     */
    private $tableWriter;
    /**
     * @var CustomerInteractor
     */
    private $customerInteractor;
    /**
     * @var ConferenceSeriesInteractor
     */
    private $conferenceSeriesInteractor;
    /**
     * @var CustomerCityNameInteractor
     */
    private $customerCityNameInteractor;
    /**
     * @var MainSpecialtyInteractor
     */
    private $mainSpecialtyInteractor;
    /**
     * @var AdditionalSpecialtyInteractor
     */
    private $additionalSpecialtyInteractor;
    
    /**
     * ConferenceInteractor constructor.
     *
     * @param ConferenceRepositoryInterface           $repository
     * @param DirectionInteractor                     $directionInteractor
     * @param CityInteractor                          $cityInteractor
     * @param CustomerInteractor                      $customerInteractor
     * @param ConferenceSeriesInteractor              $conferenceSeriesInteractor
     * @param CustomerCityNameInteractor              $customerCityNameInteractor
     * @param MainSpecialtyInteractor                 $mainSpecialtyInteractor
     * @param AdditionalSpecialtyInteractor           $additionalSpecialtyInteractor
     * @param ConferenceProgramRepositoryInterface    $conferenceProgramRepository
     * @param ConferenceSubscriberRepositoryInterface $conferenceSubscriberRepository
     * @param TableWriterInterface                    $tableWriter
     */
    public function __construct(
        ConferenceRepositoryInterface $repository,
        DirectionInteractor $directionInteractor,
        CityInteractor $cityInteractor,
        CustomerInteractor $customerInteractor,
        ConferenceSeriesInteractor $conferenceSeriesInteractor,
        CustomerCityNameInteractor $customerCityNameInteractor,
        MainSpecialtyInteractor $mainSpecialtyInteractor,
        AdditionalSpecialtyInteractor $additionalSpecialtyInteractor,
        ConferenceProgramRepositoryInterface $conferenceProgramRepository,
        ConferenceSubscriberRepositoryInterface $conferenceSubscriberRepository,
        TableWriterInterface $tableWriter
    ) {
        $this->repository                     = $repository;
        $this->directionInteractor            = $directionInteractor;
        $this->cityInteractor                 = $cityInteractor;
        $this->conferenceProgramRepository    = $conferenceProgramRepository;
        $this->conferenceSubscriberRepository = $conferenceSubscriberRepository;
        $this->tableWriter                    = $tableWriter;
        $this->customerInteractor             = $customerInteractor;
        $this->conferenceSeriesInteractor     = $conferenceSeriesInteractor;
        $this->customerCityNameInteractor     = $customerCityNameInteractor;
        $this->mainSpecialtyInteractor        = $mainSpecialtyInteractor;
        $this->additionalSpecialtyInteractor  = $additionalSpecialtyInteractor;
    }
    
    public function list(int $page, int $perPage, array $criteria)
    {
        return $this->repository->list($page, $perPage, $criteria);
    }
    
    /**
     * @param ConferenceDto $conferenceDto
     *
     * @return Conference
     * @throws NonExistentEntity
     */
    public function create(ConferenceDto $conferenceDto)
    {
        $conference = new Conference();
        
        $conference = $this->fillEntity($conference, $conferenceDto);
        
        $this->repository->store($conference);
        
        $this->updatePrograms($conference, $conferenceDto->programs);
        
        return $conference;
    }
    
    public function find($id)
    {
        return $this->repository->find($id);
    }
    
    /**
     * @param ConferenceDto $conferenceDto
     *
     * @return mixed
     * @throws NonExistentEntity
     */
    public function update(ConferenceDto $conferenceDto)
    {
        $conference = $this->find($conferenceDto->id);
        
        if (!$conference instanceof Conference) {
            throw new NonExistentEntity();
        }
        
        $conference = $this->fillEntity($conference, $conferenceDto);
        
        $this->updatePrograms($conference, $conferenceDto->programs);
        
        return $this->repository->update($conference);
    }
    
    /**
     * @param int        $id
     * @param int[]|null $customerIds
     *
     * @throws NonExistentEntity
     */
    public function updateSubscribersVisits(int $id, $customerIds)
    {
        $conference = $this->find($id);
        
        if (!$conference instanceof Conference) {
            throw new NonExistentEntity();
        }
        
        if (!is_array($customerIds)) {
            $customerIds = [];
        }
        
        $this->conferenceSubscriberRepository->updateVisits($conference, $customerIds);
    }
    
    /**
     * @param $id
     *
     * @return mixed
     * @throws NonExistentEntity
     */
    public function saveSubscribers($id)
    {
        $conference = $this->find($id);
        
        if (!$conference instanceof Conference) {
            throw new NonExistentEntity();
        }
        
        $report   = [];
        $report[] = [
            'Фамилия',
            'Имя',
            'Отчество',
            'Населенный пункт',
            'Специальность',
            'Ученая степень',
            'Посещение',
            'Телефон',
            'Email',
        ];
        
        foreach ($conference->getSubscribers() as $subscriber) {
            $report[] = [
                $subscriber->getCustomer()->getLastname(),
                $subscriber->getCustomer()->getName(),
                $subscriber->getCustomer()->getMiddlename(),
                $this->customerCityNameInteractor->getFullCityName($subscriber->getCustomer()),
                $this->mainSpecialtyInteractor->getNameById($subscriber->getCustomer()->getMainSpecialtyId()),
                $this->additionalSpecialtyInteractor->getNameById($subscriber->getCustomer()->getAdditionalSpecialtyId()),
                $subscriber->isVisit() ? 'Посетил' : 'Не посетил',
                $subscriber->getCustomer()->getPhone(),
                $subscriber->getCustomer()->getEmail(),
            ];
        }
        
        return $this->tableWriter->write($report);
    }
    
    /**
     * @param Conference                $conference
     * @param ConferenceSubscriberDto[] $subscribers
     *
     * @throws \Exception
     */
    public function loadSubscribers(Conference $conference, array $subscribers)
    {
        foreach ($subscribers as $subscriberItem) {
            $customer = $this->customerInteractor->findByEmail($subscriberItem->email);
            
            if (!$customer instanceof Customer) {
                $customer = $this->customerInteractor->createFromSubscriber($subscriberItem);
                
                if (!$customer instanceof Customer) {
                    continue;
                }
            }
            
            $subscriber = $this->conferenceSubscriberRepository->findByConferenceAndCustomer($conference, $customer);
            
            if ($subscriber instanceof ConferenceSubscriber) {
                $subscriber->setVisit(true);
                $this->conferenceSubscriberRepository->update($subscriber);
                
                continue;
            }
            
            $this->conferenceSubscriberRepository->store(
                (new ConferenceSubscriber())
                    ->setConference($conference)
                    ->setCustomer($customer)
                    ->setVisit(true)
            );
        }
    }
    
    /**
     * @param Conference    $conference
     * @param ConferenceDto $conferenceDto
     *
     * @return Conference
     * @throws NonExistentEntity
     */
    private function fillEntity(Conference $conference, ConferenceDto $conferenceDto): Conference
    {
        $direction = $this->directionInteractor->find($conferenceDto->direction);
        
        if (!$direction instanceof Direction) {
            throw new NonExistentEntity();
        }
        
        $city = $this->cityInteractor->find($conferenceDto->city);
        
        if (!$city instanceof City) {
            throw new NonExistentEntity();
        }
        
        $series = null;
        if ($conferenceDto->series !== null) {
            $series = $this->conferenceSeriesInteractor->find($conferenceDto->series);
            
            if (!$series instanceof ConferenceSeries) {
                throw new NonExistentEntity();
            }
        }
        
        $conference
            ->setName($conferenceDto->name)
            ->setIsActive($conferenceDto->isActive)
            ->setDescription($conferenceDto->description)
            ->setStartDatetime($conferenceDto->startDateTime)
            ->setEndDatetime($conferenceDto->endDateTime)
            ->setScore($conferenceDto->score)
            ->setDirection($direction)
            ->setCity($city)
            ->setAddress($conferenceDto->address)
            ->setSeries($series);
        
        return $conference;
    }
    
    /**
     * @param Conference             $conference
     * @param ConferenceProgramDto[] $programsDto
     */
    private function updatePrograms(Conference $conference, array $programsDto): void
    {
        $this->conferenceProgramRepository->deleteConferencePrograms($conference);
        
        if (empty($programsDto)) {
            return;
        }
        
        $programs = [];
        foreach ($programsDto as $programDto) {
            if (
                empty($programDto->fromTime)
                && empty($programDto->tillTime)
                && empty($programDto->subject)
                && empty($programDto->lecturers)
            ) {
                continue;
            }
            
            $programs[] = (new ConferenceProgram())
                ->setFromTime($programDto->fromTime)
                ->setTillTime($programDto->tillTime)
                ->setSubject($programDto->subject)
                ->setLecturers($programDto->lecturers);
        }
        
        $this->conferenceProgramRepository->addConferencePrograms($conference, $programs);
    }
}
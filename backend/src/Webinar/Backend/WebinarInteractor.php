<?php

namespace App\Webinar\Backend;


use App\Domain\Backend\Interactor\AdditionalSpecialtyInteractor;
use App\Domain\Backend\Interactor\DeleteInteractor;
use App\Domain\Backend\Interactor\DirectionInteractor;
use App\Domain\Backend\Interactor\File;
use App\Domain\Backend\Interactor\FileUploader;
use App\Domain\Backend\Interactor\MainSpecialtyInteractor;
use App\Domain\Backend\Interactor\TableWriterInterface;
use App\Domain\Backend\Interactor\UploadedFile;
use App\Domain\Entity\Customer\Interactor\CustomerCityNameInteractor;
use App\Domain\Entity\Direction\Direction;
use App\Interactors\NonExistentEntity;
use App\Webinar\DTO\WebinarDto;
use App\Webinar\DTO\WebinarReportDto;
use App\Webinar\Webinar;
use App\Webinar\WebinarReport;
use App\Webinar\WebinarReportRepositoryInterface;
use DateTime;

final class WebinarInteractor
{
    use DeleteInteractor;
    
    const UPLOAD_DIRECTORY = 'webinar';
    
    private $repository;
    /**
     * @var DirectionInteractor
     */
    private $directionInteractor;
    /**
     * @var WebinarReportRepositoryInterface
     */
    private $webinarReportRepository;
    /**
     * @var FileUploader
     */
    private $fileUploader;
    /**
     * @var TableWriterInterface
     */
    private $tableWriter;
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
     * WebinarInteractor constructor.
     *
     * @param WebinarRepositoryInterface       $repository
     * @param DirectionInteractor              $directionInteractor
     * @param CustomerCityNameInteractor       $customerCityNameInteractor
     * @param MainSpecialtyInteractor          $mainSpecialtyInteractor
     * @param AdditionalSpecialtyInteractor    $additionalSpecialtyInteractor
     * @param WebinarReportRepositoryInterface $webinarReportRepository
     * @param FileUploader                     $fileUploader
     * @param TableWriterInterface             $tableWriter
     */
    public function __construct(
        WebinarRepositoryInterface $repository,
        DirectionInteractor $directionInteractor,
        CustomerCityNameInteractor $customerCityNameInteractor,
        MainSpecialtyInteractor $mainSpecialtyInteractor,
        AdditionalSpecialtyInteractor $additionalSpecialtyInteractor,
        WebinarReportRepositoryInterface $webinarReportRepository,
        FileUploader $fileUploader,
        TableWriterInterface $tableWriter
    ) {
        $this->repository                    = $repository;
        $this->directionInteractor           = $directionInteractor;
        $this->webinarReportRepository       = $webinarReportRepository;
        $this->fileUploader                  = $fileUploader;
        $this->tableWriter                   = $tableWriter;
        $this->customerCityNameInteractor    = $customerCityNameInteractor;
        $this->mainSpecialtyInteractor       = $mainSpecialtyInteractor;
        $this->additionalSpecialtyInteractor = $additionalSpecialtyInteractor;
    }
    
    public function list(int $page, int $perPage, array $criteria)
    {
        return $this->repository->list($page, $perPage, $criteria);
    }
    
    public function find($id)
    {
        return $this->repository->find($id);
    }
    
    /**
     * @param WebinarDto $webinarDto
     *
     * @return mixed
     * @throws NonExistentEntity
     */
    public function update(WebinarDto $webinarDto)
    {
        $webinar = $this->find($webinarDto->id);
        
        if (!$webinar instanceof Webinar) {
            throw new NonExistentEntity();
        }
        
        $webinar = $this->fillEntity($webinar, $webinarDto);
        
        return $this->repository->update($webinar);
    }
    
    /**
     * @param WebinarDto $webinarDto
     *
     * @return Webinar
     * @throws NonExistentEntity
     */
    public function create(WebinarDto $webinarDto)
    {
        $webinar = new Webinar();
        
        $webinar = $this->fillEntity($webinar, $webinarDto);
        
        $this->repository->store($webinar);
        
        return $webinar;
    }
    
    public function updateReport(Webinar $webinar, WebinarReportDto $dto)
    {
        $entity = $webinar->getReport();
        
        if ($entity instanceof WebinarReport) {
            $entity
                ->setSubtitle($dto->subtitle)
                ->setYoutubeCode($dto->youtubeCode)
                ->setDescription($dto->description);
            
            $this->webinarReportRepository->update($entity);
        } else {
            $entity = (new WebinarReport())
                ->setWebinar($webinar)
                ->setSubtitle($dto->subtitle)
                ->setYoutubeCode($dto->youtubeCode)
                ->setDescription($dto->description);
            
            $this->webinarReportRepository->store($entity);
        }
        
        if (
            $dto->announceImageFile instanceof File
            && $dto->announceImageFile->getUploadedFile() instanceof UploadedFile
        ) {
            $entity->setAnnounceImage(
                $this->fileUploader->upload(
                    $dto->announceImageFile,
                    'announce',
                    self::UPLOAD_DIRECTORY . '/' . $entity->getId()
                )
            );
        }
        
        if (
            $dto->imageFile instanceof File
            && $dto->imageFile->getUploadedFile() instanceof UploadedFile
        ) {
            $entity->setImage(
                $this->fileUploader->upload(
                    $dto->imageFile,
                    'news',
                    self::UPLOAD_DIRECTORY . '/' . $entity->getId()
                )
            );
        }
        
        $this->webinarReportRepository->update($entity);
    }
    
    /**
     * @param $id
     *
     * @return WebinarReport
     * @throws NonExistentEntity
     */
    public function findReport($id): WebinarReport
    {
        $webinar = $this->find($id);
        
        if (!$webinar instanceof Webinar) {
            throw new NonExistentEntity();
        }
        
        $report = $webinar->getReport();
        
        if (!$report instanceof WebinarReport) {
            $report = (new WebinarReport())->setWebinar($webinar);
        }
        
        return $report;
    }
    
    /**
     * @param $id
     *
     * @throws NonExistentEntity
     */
    public function saveSubscribers($id)
    {
        $webinar = $this->find($id);
        
        if (!$webinar instanceof Webinar) {
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
            'Кол-во отметок',
        ];
        
        foreach ($webinar->getSubscribers() as $subscriber) {
            $report[] = [
                $subscriber->getCustomer()->getLastname(),
                $subscriber->getCustomer()->getName(),
                $subscriber->getCustomer()->getMiddlename(),
                $this->customerCityNameInteractor->getFullCityName($subscriber->getCustomer()),
                $this->mainSpecialtyInteractor->getNameById($subscriber->getCustomer()->getMainSpecialtyId()),
                $this->additionalSpecialtyInteractor->getNameById($subscriber->getCustomer()->getAdditionalSpecialtyId()),
                $subscriber->getConfirmNumber() >= 2 ? 'Посетил' : 'Не посетил',
                $subscriber->getCustomer()->getPhone(),
                $subscriber->getCustomer()->getEmail(),
                $subscriber->getConfirmNumber(),
            ];
        }
        
        return $this->tableWriter->write($report);
    }
    
    /**
     * @param Webinar    $webinar
     * @param WebinarDto $dto
     *
     * @return Webinar
     * @throws NonExistentEntity
     * @throws \Exception
     */
    private function fillEntity(Webinar $webinar, WebinarDto $dto): Webinar
    {
        $direction = $this->directionInteractor->find($dto->direction);
        
        if (!$direction instanceof Direction) {
            throw new NonExistentEntity();
        }
        
        $webinar
            ->setName($dto->name)
            ->setIsActive($dto->isActive)
            ->setSubject($dto->subject)
            ->setDescription($dto->description)
            ->setStartDatetime($dto->startDatetime)
            ->setEndDatetime($dto->endDatetime)
            ->setScore($dto->score)
            ->setConfirmationTime1($this->makeTime($dto->startDatetime, $dto->confirmationTime1))
            ->setConfirmationTime2($this->makeTime($dto->startDatetime, $dto->confirmationTime2))
            ->setConfirmationTime3(
                $dto->confirmationTime3 === null
                    ? null
                    : $this->makeTime($dto->startDatetime, $dto->confirmationTime3)
            )
            ->setYoutubeCode($dto->youtubeCode)
            ->setEmail($dto->email)
            ->setDirection($direction);
        
        return $webinar;
    }
    
    /**
     * @param DateTime $date
     * @param DateTime $time
     *
     * @return DateTime
     * @throws \Exception
     */
    private function makeTime(DateTime $date, DateTime $time): DateTime
    {
        return new DateTime($date->format('Y-m-d') . ' ' . $time->format('H:i'));
    }
}
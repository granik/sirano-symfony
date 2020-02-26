<?php

namespace App\Webinar\Frontend\Interactor;


use App\Domain\Backend\Interactor\CustomerInteractor;
use App\Domain\Backend\Interactor\DirectionInteractor;
use App\Domain\Entity\Customer\Customer;
use App\Domain\Entity\Direction\Direction;
use App\Domain\Frontend\Interactor\Exceptions\UserAlreadySubscribed;
use App\Domain\Frontend\Interactor\Exceptions\UserAlreadyUnsubscribed;
use App\Domain\Frontend\Interactor\Exceptions\UserIsNotCustomer;
use App\Domain\Frontend\Interactor\Exceptions\UserNotSubscribed;
use App\Domain\Interactor\User\User;
use App\Interactors\MailerInterface;
use App\Interactors\NonExistentEntity;
use App\Webinar\DTO\WebinarDto;
use App\Webinar\DTO\WebinarMessage;
use App\Webinar\DTO\WebinarRegistrationMessageDtoAssembler;
use App\Webinar\Frontend\WebinarRepositoryInterface;
use App\Webinar\Webinar;
use App\Webinar\WebinarSubscriber;
use App\Webinar\WebinarSubscriberInteractor;
use DateTime;

final class WebinarInteractor
{
    const MODIFICATORS        = [
        'week'  => '1 week',
        'month' => '1 month',
        'year'  => '1 year',
    ];
    const DEFAULT_MODIFICATOR = 'month';
    const MODIFICATOR_ALL     = 'all';
    
    /**
     * @var WebinarRepositoryInterface
     */
    private $webinarRepository;
    
    /**
     * @var DirectionInteractor
     */
    private $directionInteractor;
    
    /**
     * @var WebinarSubscriberInteractor
     */
    private $subscriberInteractor;
    
    /**
     * @var CustomerInteractor
     */
    private $customerInteractor;
    /**
     * @var MailerInterface
     */
    private $mailer;
    /**
     * @var WebinarRegistrationMessageDtoAssembler
     */
    private $registrationMessageDtoAssembler;
    
    /**
     * WebinarInteractor constructor.
     *
     * @param WebinarRepositoryInterface             $webinarRepository
     * @param DirectionInteractor                    $directionInteractor
     * @param WebinarSubscriberInteractor            $subscriberInteractor
     * @param CustomerInteractor                     $customerInteractor
     * @param MailerInterface                        $mailer
     * @param WebinarRegistrationMessageDtoAssembler $registrationMessageDtoAssembler
     */
    public function __construct(
        WebinarRepositoryInterface $webinarRepository,
        DirectionInteractor $directionInteractor,
        WebinarSubscriberInteractor $subscriberInteractor,
        CustomerInteractor $customerInteractor,
        MailerInterface $mailer,
        WebinarRegistrationMessageDtoAssembler $registrationMessageDtoAssembler
    ) {
        $this->webinarRepository               = $webinarRepository;
        $this->directionInteractor             = $directionInteractor;
        $this->subscriberInteractor            = $subscriberInteractor;
        $this->customerInteractor              = $customerInteractor;
        $this->mailer                          = $mailer;
        $this->registrationMessageDtoAssembler = $registrationMessageDtoAssembler;
    }
    
    public function listAll()
    {
        return $this->webinarRepository->listAll();
    }
    
    public function list(int $page, int $perPage, $direction, string $period = '')
    {
        $tillDate = $this->getTillDate($period);
        
        return $this->webinarRepository->list($page, $perPage, $tillDate, $direction);
    }
    
    public function archive(int $page, int $perPage, $direction, string $period)
    {
        $fromDate = $this->getFromDate($period);
        
        return $this->webinarRepository->archive($page, $perPage, $fromDate, $direction);
    }
    
    public function find($id)
    {
        return $this->webinarRepository->find($id);
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
        
        $webinar = $this->getWebinar($webinar, $webinarDto);
        
        return $this->webinarRepository->update($webinar);
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
        
        $webinar = $this->getWebinar($webinar, $webinarDto);
        
        $this->webinarRepository->store($webinar);
        
        return $webinar;
    }
    
    /**
     * @param Webinar $webinar
     * @param User    $user
     *
     * @return mixed
     * @throws NonExistentEntity
     * @throws UserIsNotCustomer
     * @throws UserNotSubscribed
     */
    public function confirmView(Webinar $webinar, User $user)
    {
        if (!$this->isUserSubscribed($webinar, $user)) {
            throw new UserNotSubscribed();
        }
        
        $customer = $this->customerInteractor->getCustomer($user);
        
        return $this->subscriberInteractor->confirmView($webinar, $customer);
    }
    
    /**
     * @param Webinar $webinar
     * @param User    $user
     *
     * @return void
     * @throws UserAlreadySubscribed
     * @throws UserIsNotCustomer
     */
    public function subscribe(Webinar $webinar, User $user)
    {
        if ($this->isUserSubscribed($webinar, $user)) {
            throw new UserAlreadySubscribed();
        }
        
        $customer = $this->customerInteractor->getCustomer($user);
        $this->subscriberInteractor->create($webinar, $customer);
        
        $message = $this->registrationMessageDtoAssembler->assemble($webinar);
        
        $this->mailer->sendRegistrationForWebinarEmail($customer->getEmail(), $message);
    }
    
    /**
     * @param Webinar $webinar
     * @param User    $user
     *
     * @return WebinarSubscriber
     * @throws UserAlreadyUnsubscribed
     * @throws UserIsNotCustomer
     */
    public function unsubscribe(Webinar $webinar, User $user)
    {
        if (!$this->isUserSubscribed($webinar, $user)) {
            throw new UserAlreadyUnsubscribed();
        }
        
        $customer = $this->customerInteractor->getCustomer($user);
        
        return $this->subscriberInteractor->delete($webinar, $customer);
    }
    
    /**
     * @param Webinar $webinar
     * @param User    $user
     *
     * @return bool
     * @throws UserIsNotCustomer
     */
    public function isUserSubscribed(Webinar $webinar, User $user)
    {
        try {
            $customer = $this->customerInteractor->getCustomer($user);
        } catch (UserIsNotCustomer $e) {
            return false;
        }
        
        return $this->subscriberInteractor->find($webinar, $customer) instanceof WebinarSubscriber;
    }
    
    /**
     * @param User $user
     *
     * @return mixed
     * @throws UserIsNotCustomer
     */
    public function getDashboardWebinars(User $user)
    {
        $customer = $this->customerInteractor->getCustomer($user);
        
        return $this->webinarRepository->dashboard($customer);
    }
    
    /**
     * @param User   $user
     * @param int    $page
     * @param int    $perPage
     * @param string $period
     *
     * @return mixed
     * @throws UserIsNotCustomer
     */
    public function getProfileWebinars(User $user, int $page, int $perPage, string $period = '')
    {
        $customer = $this->customerInteractor->getCustomer($user);
        $tillDate = $this->getTillDate($period);
        
        return $this->webinarRepository->getProfileWebinars($customer, $page, $perPage, $tillDate);
    }
    
    public function randomArchive(Webinar $webinar)
    {
        return $this->webinarRepository->randomArchive($webinar);
    }
    
    public function getCustomerScore(Customer $customer)
    {
        return $this->webinarRepository->getCustomerScore($customer);
    }
    
    public function sendMessage(Webinar $entity, User $user, string $text)
    {
        $message            = new WebinarMessage();
        $message->to        = $entity->getEmail();
        $message->date      = $entity->getStartDatetime()->format('d.m.Y');
        $message->name      = $entity->getName();
        $message->subject   = $entity->getSubject();
        $message->direction = $entity->getDirection()->getName();
        $message->text      = $text;
        
        $customer = $this->customerInteractor->getCustomer($user);
        
        $message->subscriberName  = $customer->getLastname() . ' ' . $customer->getName() . ' ' . $customer->getMiddlename();
        $message->subscriberEmail = $customer->getEmail();
        
        $this->mailer->sendWebinarMessage($message);
    }
    
    public function getMaxScore()
    {
        return $this->webinarRepository->getMaxScore();
    }
    
    /**
     * @param Webinar    $webinar
     * @param WebinarDto $webinarDto
     *
     * @return Webinar
     * @throws NonExistentEntity
     */
    private function getWebinar(Webinar $webinar, WebinarDto $webinarDto): Webinar
    {
        $direction = $this->directionInteractor->find($webinarDto->direction);
        
        if (!$direction instanceof Direction) {
            throw new NonExistentEntity();
        }
        
        $webinar
            ->setName($webinarDto->name)
            ->setIsActive($webinarDto->isActive)
            ->setSubject($webinarDto->subject)
            ->setDescription($webinarDto->description)
            ->setStartDatetime($webinarDto->startDatetime)
            ->setEndDatetime($webinarDto->endDatetime)
            ->setScore($webinarDto->score)
            ->setConfirmationTime1($webinarDto->confirmationTime1)
            ->setConfirmationTime2($webinarDto->confirmationTime2)
            ->setConfirmationTime3($webinarDto->confirmationTime3)
            ->setYoutubeCode($webinarDto->youtubeCode)
            ->setEmail($webinarDto->email)
            ->setDirection($direction);
        
        return $webinar;
    }
    
    private function getTillDate(string $period)
    {
        $modificator = self::MODIFICATORS[self::DEFAULT_MODIFICATOR];
        
        if (isset(self::MODIFICATORS[$period])) {
            $modificator = self::MODIFICATORS[$period];
        }
        
        return new DateTime("+$modificator");
    }
    
    private function getFromDate(string $period): ?DateTime
    {
        if ($period !== self::MODIFICATOR_ALL && !isset(self::MODIFICATORS[$period])) {
            throw new \InvalidArgumentException('Unknown period');
        }
        
        if ($period === self::MODIFICATOR_ALL) {
            return null;
        }
        
        $modificator = self::MODIFICATORS[$period];
        
        return new DateTime("-$modificator");
    }
}
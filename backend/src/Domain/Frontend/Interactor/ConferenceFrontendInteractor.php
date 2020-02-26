<?php

namespace App\Domain\Frontend\Interactor;


use App\Domain\Backend\Interactor\CustomerInteractor;
use App\Domain\Entity\Conference\Conference;
use App\Domain\Entity\Conference\ConferenceSubscriber;
use App\Domain\Entity\Conference\Frontend\ConferenceRepositoryInterface;
use App\Domain\Entity\Customer\Customer;
use App\Domain\Entity\Direction\Direction;
use App\Domain\Frontend\Interactor\Exceptions\UserAlreadySubscribed;
use App\Domain\Frontend\Interactor\Exceptions\UserAlreadyUnsubscribed;
use App\Domain\Frontend\Interactor\Exceptions\UserIsNotCustomer;
use App\Domain\Interactor\User\User;
use App\Interactors\NonExistentEntity;
use DateTime;

final class ConferenceFrontendInteractor
{
    const MODIFICATORS        = [
        'week'  => '1 week',
        'month' => '1 month',
        'year'  => '1 year',
    ];
    const DEFAULT_MODIFICATOR = 'month';
    const MODIFICATOR_ALL     = 'all';
    
    /**
     * @var ConferenceRepositoryInterface
     */
    private $repository;
    /**
     * @var CustomerInteractor
     */
    private $customerInteractor;
    /**
     * @var ConferenceSubscriberInteractor
     */
    private $subscriberInteractor;
    
    /**
     * ConferenceInteractor constructor.
     *
     * @param ConferenceRepositoryInterface  $repository
     * @param CustomerInteractor             $customerInteractor
     * @param ConferenceSubscriberInteractor $subscriberInteractor
     */
    public function __construct(
        ConferenceRepositoryInterface $repository,
        CustomerInteractor $customerInteractor,
        ConferenceSubscriberInteractor $subscriberInteractor
    ) {
        $this->repository           = $repository;
        $this->customerInteractor   = $customerInteractor;
        $this->subscriberInteractor = $subscriberInteractor;
    }
    
    public function list(int $page, int $perPage, $direction, string $period = '')
    {
        $tillDate = $this->getTillDate($period);
        
        return $this->repository->list($page, $perPage, $tillDate, $direction);
    }
    
    public function archive(int $page, int $perPage, $direction, string $period)
    {
        $fromDate = $this->getFromDate($period);
        
        return $this->repository->archive($page, $perPage, $fromDate, $direction);
    }
    
    public function find($id)
    {
        return $this->repository->find($id);
    }
    
    /**
     * @param Conference $conference
     * @param User       $user
     *
     * @return mixed
     * @throws UserAlreadySubscribed
     * @throws UserIsNotCustomer
     */
    public function subscribe(Conference $conference, User $user)
    {
        if ($this->isUserSubscribed($conference, $user)) {
            throw new UserAlreadySubscribed();
        }
        
        $customer = $this->customerInteractor->getCustomer($user);
        
        return $this->subscriberInteractor->create($conference, $customer);
    }
    
    /**
     * @param Conference $conference
     * @param User       $user
     *
     * @return mixed
     * @throws UserAlreadyUnsubscribed
     * @throws UserIsNotCustomer
     * @throws NonExistentEntity
     */
    public function unsubscribe(Conference $conference, User $user)
    {
        if (!$this->isUserSubscribed($conference, $user)) {
            throw new UserAlreadyUnsubscribed();
        }
        
        $customer = $this->customerInteractor->getCustomer($user);
        
        return $this->subscriberInteractor->delete($conference, $customer);
    }
    
    public function isUserSubscribed(Conference $conference, User $user)
    {
        try {
            $customer = $this->customerInteractor->getCustomer($user);
        } catch (UserIsNotCustomer $e) {
            return false;
        }
        
        return $this->subscriberInteractor->find($conference, $customer) instanceof ConferenceSubscriber;
    }
    
    /**
     * @param User $user
     *
     * @return mixed
     * @throws UserIsNotCustomer
     */
    public function getDashboardConferences(User $user)
    {
        $customer = $this->customerInteractor->getCustomer($user);
        
        return $this->repository->dashboard($customer);
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
    public function getProfileConferences(User $user, int $page, int $perPage, string $period = '')
    {
        $customer = $this->customerInteractor->getCustomer($user);
        $tillDate = $this->getTillDate($period);
        
        return $this->repository->getProfileConferences($customer, $page, $perPage, $tillDate);
    }
    
    public function getCustomerScore(Customer $customer)
    {
        return $this->repository->getCustomerScore($customer);
    }
    
    public function listComingSoon(int $limit, ?Direction $direction)
    {
        return $this->repository->listComingSoon($limit, $direction);
    }
    
    public function getMaxScore()
    {
        return $this->repository->getMaxScore();
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
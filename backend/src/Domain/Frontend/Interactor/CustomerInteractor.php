<?php


namespace App\Domain\Frontend\Interactor;


use App\Domain\Entity\Customer\Customer;
use App\Domain\Entity\Customer\Frontend\CustomerRepositoryInterface;
use App\Webinar\Frontend\Interactor\WebinarInteractor;

final class CustomerInteractor
{
    /**
     * @var CustomerRepositoryInterface
     */
    private $repository;
    /**
     * @var WebinarInteractor
     */
    private $webinarInteractor;
    /**
     * @var ModuleInteractor
     */
    private $moduleInteractor;
    /**
     * @var ConferenceFrontendInteractor
     */
    private $conferenceInteractor;
    
    /**
     * CustomerInteractor constructor.
     *
     * @param CustomerRepositoryInterface  $repository
     * @param WebinarInteractor            $webinarInteractor
     * @param ModuleInteractor             $moduleInteractor
     * @param ConferenceFrontendInteractor $conferenceInteractor
     */
    public function __construct(
        CustomerRepositoryInterface $repository,
        WebinarInteractor $webinarInteractor,
        ModuleInteractor $moduleInteractor,
        ConferenceFrontendInteractor $conferenceInteractor
    ) {
        $this->repository           = $repository;
        $this->webinarInteractor    = $webinarInteractor;
        $this->moduleInteractor     = $moduleInteractor;
        $this->conferenceInteractor = $conferenceInteractor;
    }
    
    public function list(int $page, int $perPage, ?string $query)
    {
        return $this->repository->list($page, $perPage, $query);
    }
    
    /**
     * @param Customer $customer
     *
     * @return int
     */
    public function getOnlineScore(Customer $customer): int
    {
        $score = 0;
        
        $score += $this->getWebinarScore($customer);
        $score += $this->getModuleScore($customer);
        
        return $score;
    }
    
    /**
     * @param Customer $customer
     *
     * @return int
     */
    public function getOnlinePercentScore(Customer $customer): int
    {
        $maxScore = 0;
        
        $maxScore += $this->getWebinarMaxScore();
        $maxScore += $this->getModuleMaxScore();
        
        return (int)round(100 * $this->getOnlineScore($customer) / $maxScore);
    }
    
    /**
     * @param Customer $customer
     *
     * @return int
     */
    public function getOfflineScore(Customer $customer): int
    {
        $score = 0;
        
        $score += $this->getConferenceScore($customer);
        
        return $score;
    }
    
    /**
     * @param Customer $customer
     *
     * @return int
     */
    public function getOfflinePercentScore(Customer $customer): int
    {
        $maxScore = 0;
        
        $maxScore += $this->getConferenceMaxScore();
        
        return (int)round(100 * $this->getOfflineScore($customer) / $maxScore);
    }
    
    /**
     * @param Customer $customer
     *
     * @return int
     */
    public function getWebinarScore(Customer $customer): int
    {
        return (int)$this->webinarInteractor->getCustomerScore($customer);
    }
    
    /**
     * @param Customer $customer
     *
     * @return int
     */
    public function getModuleScore(Customer $customer): int
    {
        return (int)$this->moduleInteractor->getCustomerScore($customer);
    }
    
    /**
     * @param Customer $customer
     *
     * @return int
     */
    public function getConferenceScore(Customer $customer): int
    {
        return (int)$this->conferenceInteractor->getCustomerScore($customer);
    }
    
    /**
     * @return int
     */
    private function getModuleMaxScore(): int
    {
        return (int)$this->moduleInteractor->getMaxScore();
    }
    
    /**
     * @return int
     */
    private function getWebinarMaxScore(): int
    {
        return (int)$this->webinarInteractor->getMaxScore();
    }
    
    /**
     * @return int
     */
    private function getConferenceMaxScore(): int
    {
        return (int)$this->conferenceInteractor->getMaxScore();
    }
}
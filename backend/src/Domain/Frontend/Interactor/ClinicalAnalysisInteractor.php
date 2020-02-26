<?php


namespace App\Domain\Frontend\Interactor;


use App\Domain\Backend\Interactor\CustomerInteractor;
use App\Domain\Entity\ClinicalAnalysis\ClinicalAnalysis;
use App\Domain\Entity\ClinicalAnalysis\Frontend\ClinicalAnalysisRepositoryInterface;
use App\Domain\Entity\ClinicalAnalysis\Frontend\DTO\ClinicalAnalysisMessage;
use App\Domain\Entity\Module\Module;
use App\Domain\Interactor\User\User;
use App\Interactors\MailerInterface;

final class ClinicalAnalysisInteractor
{
    /**
     * @var ClinicalAnalysisRepositoryInterface
     */
    private $repository;
    /**
     * @var MailerInterface
     */
    private $mailer;
    /**
     * @var CustomerInteractor
     */
    private $customerInteractor;
    
    /**
     * ClinicalAnalysisInteractor constructor.
     *
     * @param ClinicalAnalysisRepositoryInterface $repository
     * @param CustomerInteractor                  $customerInteractor
     * @param MailerInterface                     $mailer
     */
    public function __construct(
        ClinicalAnalysisRepositoryInterface $repository,
        CustomerInteractor $customerInteractor,
        MailerInterface $mailer
    ) {
        $this->repository         = $repository;
        $this->mailer             = $mailer;
        $this->customerInteractor = $customerInteractor;
    }
    
    public function list(int $page, int $perPage, $direction, $category)
    {
        return $this->repository->list($page, $perPage, $direction, $category);
    }
    
    public function find($id)
    {
        return $this->repository->find($id);
    }
    
    public function sendMessage(ClinicalAnalysis $entity, User $user, string $text)
    {
        $message            = new ClinicalAnalysisMessage();
        $message->to        = $entity->getLecturerEmail();
        $message->name      = $entity->getName();
        $message->direction = $entity->getDirection()->getName();
        $message->text      = $text;
        
        $customer = $this->customerInteractor->getCustomer($user);
        
        $message->subscriberName  = $customer->getLastname() . ' ' . $customer->getName() . ' ' . $customer->getMiddlename();
        $message->subscriberEmail = $customer->getEmail();
        
        $this->mailer->sendClinicalAnalysisMessage($message);
    }
    
    public function sendCompanyMessage(ClinicalAnalysis $entity, User $user, string $text)
    {
        $message            = new ClinicalAnalysisMessage();
        $message->to        = $entity->getLecturerEmail();
        $message->name      = $entity->getName();
        $message->direction = $entity->getDirection()->getName();
        $message->text      = $text;
    
        $customer = $this->customerInteractor->getCustomer($user);
    
        $message->subscriberName  = $customer->getLastname() . ' ' . $customer->getName() . ' ' . $customer->getMiddlename();
        $message->subscriberEmail = $customer->getEmail();
    
        $this->mailer->sendClinicalAnalysisCompanyMessage($message);
    }
    
    public function findByModule(Module $module)
    {
        return $this->repository->findByModule($module);
    }
}
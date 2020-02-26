<?php


namespace App\Webinar\Frontend\Interactor;


use App\Interactors\MailerInterface;
use App\Webinar\DTO\WebinarRegistrationMessageDtoAssembler;
use App\Webinar\Frontend\WebinarRepositoryInterface;
use App\Webinar\Webinar;
use Psr\Log\LoggerInterface;

final class WebinarReminderInteractor
{
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var MailerInterface
     */
    private $mailer;
    /**
     * @var WebinarRepositoryInterface
     */
    private $webinarRepository;
    /**
     * @var WebinarRegistrationMessageDtoAssembler
     */
    private $registrationMessageDtoAssembler;
    
    /**
     * WebinarReminderInteractor constructor.
     *
     * @param WebinarRepositoryInterface             $webinarRepository
     * @param MailerInterface                        $mailer
     * @param WebinarRegistrationMessageDtoAssembler $registrationMessageDtoAssembler
     * @param LoggerInterface                        $logger
     */
    public function __construct(
        WebinarRepositoryInterface $webinarRepository,
        MailerInterface $mailer,
        WebinarRegistrationMessageDtoAssembler $registrationMessageDtoAssembler,
        LoggerInterface $logger
    ) {
        $this->logger                          = $logger;
        $this->mailer                          = $mailer;
        $this->webinarRepository               = $webinarRepository;
        $this->registrationMessageDtoAssembler = $registrationMessageDtoAssembler;
    }
    
    public function remindAboutWebinars(): void
    {
        $webinars = $this->webinarRepository->getInADayWebinars(new \DateTime());
        
        foreach ($webinars as $webinar) {
            $this->remindAboutWebinar($webinar);
        }
    }
    
    /**
     * @param Webinar $webinar
     */
    private function remindAboutWebinar(Webinar $webinar): void
    {
        $subscribers = $webinar->getSubscribers();
        
        foreach ($subscribers as $subscriber) {
            $customer = $subscriber->getCustomer();
            $email    = $customer->getEmail();
            $message  = $this->registrationMessageDtoAssembler->assemble($webinar);
            
            try {
                $this->logger->debug("Sending to {$email}");
                
                $this->mailer->sendRemindAboutWebinarEmail($email, $message);
            } catch (\Exception $exception) {
                $this->logger->error($exception->getMessage());
            }
        }
    }
}
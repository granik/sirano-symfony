<?php


namespace App\Command;


use App\Webinar\Frontend\Interactor\WebinarReminderInteractor;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Routing\RouterInterface;

final class SendWebinarReminder extends Command implements LoggerAwareInterface
{
    const LOCK_FILE = 'sirano_send_webinar_reminder_lock_file.txt';
    
    protected static $defaultName = 'app:send-webinar-reminder';
    
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var WebinarReminderInteractor
     */
    private $webinarReminderInteractor;
    
    /**
     * SendConfirmEmails constructor.
     *
     * @param WebinarReminderInteractor $webinarReminderInteractor
     * @param RouterInterface           $router
     */
    public function __construct(WebinarReminderInteractor $webinarReminderInteractor, RouterInterface $router)
    {
        parent::__construct();
        
        $this->router                    = $router;
        $this->webinarReminderInteractor = $webinarReminderInteractor;
    }
    
    /**
     * Sets a logger instance on the object.
     *
     * @param LoggerInterface $logger
     *
     * @return void
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fp = fopen(sys_get_temp_dir() . DIRECTORY_SEPARATOR . self::LOCK_FILE, 'wb+');
        if (flock($fp, LOCK_EX | LOCK_NB)) {
            $context = $this->router->getContext();
            $context->setHost(getenv('HOST'));
            $context->setScheme(getenv('SCHEMA'));
            
            $this->webinarReminderInteractor->remindAboutWebinars();
            flock($fp, LOCK_UN);
        } else {
            $this->logger->error("Couldn't get the lock");
        }
        
        fclose($fp);
    }
}
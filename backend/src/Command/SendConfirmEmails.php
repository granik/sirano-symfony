<?php


namespace App\Command;


use App\Domain\Interactor\UserInteractor;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Routing\RouterInterface;

final class SendConfirmEmails extends Command implements LoggerAwareInterface
{
    const LOCK_FILE = 'sirano_send_confirm_emails_lock_file.txt';
    
    protected static $defaultName = 'app:send-confirm-emails';
    
    /**
     * @var UserInteractor
     */
    private $userInteractor;
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var LoggerInterface
     */
    private $logger;
    
    /**
     * SendConfirmEmails constructor.
     *
     * @param UserInteractor  $userInteractor
     * @param RouterInterface $router
     */
    public function __construct(UserInteractor $userInteractor, RouterInterface $router)
    {
        parent::__construct();
        
        $this->userInteractor = $userInteractor;
        $this->router         = $router;
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
            
            $this->userInteractor->handleNotConfirmed(getenv('CONFIRM_EMAILS_LIMIT'));
            flock($fp, LOCK_UN);
        } else {
            $this->logger->error("Couldn't get the lock");
        }
        
        fclose($fp);
    }
}
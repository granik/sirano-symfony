<?php

namespace App\Domain\Interactor;


use App\Domain\Entity\Customer\Customer;
use App\Domain\Entity\Direction\Direction;
use App\Domain\Frontend\Interactor\FilterDirectionInterface;
use App\Domain\Interactor\User\DTO\UserDto;
use App\Domain\Interactor\User\DTO\UserPasswordUpdateDto;
use App\Domain\Interactor\User\User;
use App\Domain\Interactor\User\UserRepositoryInterface;
use App\Domain\Service\UserUtils;
use App\Interactors\MailerInterface;
use App\Interactors\UserPasswordEncoderInterface;
use Psr\Log\LoggerInterface;

final class UserInteractor
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var MailerInterface
     */
    private $mailer;
    /**
     * @var FilterDirectionInterface
     */
    private $filterDirection;
    /**
     * @var SettingsInterface
     */
    private $settings;
    /**
     * @var LoggerInterface
     */
    private $logger;
    
    /**
     * UserInteractor constructor.
     *
     * @param UserRepositoryInterface      $userRepository
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param MailerInterface              $mailer
     * @param FilterDirectionInterface     $filterDirection
     * @param SettingsInterface            $settings
     * @param LoggerInterface              $logger
     */
    public function __construct(
        UserRepositoryInterface $userRepository,
        UserPasswordEncoderInterface $passwordEncoder,
        MailerInterface $mailer,
        FilterDirectionInterface $filterDirection,
        SettingsInterface $settings,
        LoggerInterface $logger
    ) {
        $this->userRepository  = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
        $this->mailer          = $mailer;
        $this->filterDirection = $filterDirection;
        $this->settings        = $settings;
        $this->logger          = $logger;
    }
    
    public function create(UserDto $userDto)
    {
        if ($this->checkIfUserExists($userDto->login)) {
            return false;
        }
        
        $user = new User();
        $user
            ->setLogin($userDto->login)
            ->setPassword($this->passwordEncoder->encodePassword($userDto->password))
            ->setIsAdmin($userDto->isAdmin)
            ->setAddedFrom($userDto->addedFrom)
            ->setIsActive($userDto->isActive)
            ->setRegisteredAt(new \DateTime());
        
        if ($userDto->customerId) {
            $activationCode = UserUtils::makeActivationCode($userDto->login, $userDto->password);
            $user
                ->setCustomerId($userDto->customerId)
                ->setActivationCode($activationCode)
                ->setSendingDateTime(new \DateTime());
            
            if ($userDto->addedFrom === User::ADDED_FROM_SITE) {
                $this->mailer->sendConfirmationEmail($user);
            } elseif ($userDto->addedFrom === User::ADDED_FROM_FILE) {
                $this->mailer->sendConfirmationFromFileEmail($userDto->login, $activationCode, $userDto->password);
            }
        }
        
        $this->userRepository->store($user);
        
        return true;
    }
    
    public function checkIfUserExists($login)
    {
        return $this->userRepository->findAnyByLogin($login) instanceof User;
    }
    
    public function activateUser($code): array
    {
        $notifications = [];
        
        $user = $this->userRepository->findByCode($code);
        
        if (!$user instanceof User) {
            $notifications[] = 'Указанный код не найден';
            
            return $notifications;
        }
        
        if ($user->getActivationDate() !== null) {
            $notifications[] = 'Пользователь уже активен';
            
            return $notifications;
        }
        
        $user
            ->setIsAdmin(false)
            ->setIsActive(true)
            ->setActivationDate(new \DateTime());
        
        $this->userRepository->update($user);
        
        return $notifications;
    }
    
    public function selectDirection(Direction $direction)
    {
        $this->filterDirection->save($direction);
    }
    
    public function dropDirection()
    {
        $this->filterDirection->clear();
    }
    
    public function getSelectedDirection()
    {
        return $this->filterDirection->getSelectedDirection();
    }
    
    /**
     * @param $email
     *
     * @return array
     * @throws \Exception
     */
    public function restoreUserPassword($email)
    {
        $notifications = [];
        
        $user = $this->userRepository->findByLogin($email);
        
        if (!$user instanceof User) {
            $notifications[] = 'Пользователь с таким email не найден';
            
            return $notifications;
        }
        
        $password = UserUtils::getPassword();
        $user->setPassword($this->passwordEncoder->encodePassword($password));
        $this->userRepository->update($user);
        
        $this->mailer->sendRestoreUserPasswordEmail($user, $password);
        
        return $notifications;
    }
    
    public function findAnyByLogin(string $login)
    {
        return $this->userRepository->findAnyByLogin($login);
    }
    
    public function updateEntity(User $user)
    {
        $this->userRepository->update($user);
    }
    
    public function updatePassword(User $user, UserPasswordUpdateDto $passwordUpdateDto)
    {
        $notifications = [];
        
        if (!$this->passwordEncoder->isPasswordValid($user, $passwordUpdateDto->password)) {
            $notifications[] = 'Неправильный пароль';
            
            return $notifications;
        }
        
        $user->setPassword($this->passwordEncoder->encodePassword($passwordUpdateDto->password));
        $this->userRepository->update($user);
        
        return $notifications;
    }
    
    public function updatePasswordWithoutCheck(User $user, string $password)
    {
        $user->setPassword($this->passwordEncoder->encodePassword($password));
        $this->userRepository->update($user);
    }
    
    public function findByCustomer(Customer $customer): ?User
    {
        return $this->userRepository->findByCustomer($customer);
    }
    
    public function delete(User $user)
    {
        return $this->userRepository->delete($user);
    }
    
    /**
     * @param int $limit
     *
     * @throws \Exception
     */
    public function handleNotConfirmed(int $limit)
    {
        $this->logger->info('Sending started');
        
        $sendDateTime = (new \DateTime())->modify('-' . $this->settings->getHoursToConfirm() . ' hours');
        
        $this->logger->info('...till '.$sendDateTime->format('d-m-Y H:i'));
        
        foreach ($this->userRepository->getNotConfirmedUsers($sendDateTime, $this->settings->getMaxTries(), $limit) as $user) {
            $password       = UserUtils::getPassword();
            $activationCode = UserUtils::makeActivationCode($user->getLogin(), $password);
            
            $user->setActivationCode($activationCode)->setPassword($this->passwordEncoder->encodePassword($password));
            
            try {
                if ($user->getAddedFrom() === User::ADDED_FROM_SITE) {
                    $this->logger->debug('Sending to '.$user->getLogin().' (site)');
        
                    $this->mailer->sendRepeatedConfirmationEmailFromSite($user->getLogin(), $activationCode, $password);
                } else {
                    $this->logger->debug('Sending to '.$user->getLogin().' (file)');
        
                    $this->mailer->sendRepeatedConfirmationEmailFromFile($user->getLogin(), $activationCode, $password);
                }
            } catch (\Exception $exception) {
                $this->logger->error($exception->getMessage());
            }
            
            $user->incSendingCounter()->setSendingDateTime(new \DateTime());
            
            $this->updateEntity($user);
        }
        
        $this->logger->info('Sending ended');
    }
}
<?php

namespace App\Command;


use App\Domain\Interactor\User\DTO\UserDto;
use App\Domain\Interactor\User\User;
use App\Domain\Interactor\UserInteractor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class CreateUserCommand extends Command
{
    protected static $defaultName = 'app:create-user';

    /**
     * @var UserInteractor
     */
    private $userInteractor;

    /**
     * CreateUserCommand constructor.
     */
    public function __construct(UserInteractor $userInteractor)
    {
        parent::__construct();

        $this->userInteractor = $userInteractor;
    }

    protected function configure()
    {
        $this
            ->setDescription('Создать нового пользователя.')
            ->setHelp('Эта команда позволяет создавать нового пользователя...')
            ->addArgument('name', InputArgument::REQUIRED, 'Логин пользователя.')
            ->addArgument('password', InputArgument::REQUIRED, 'Пароль пользователя.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'User Creator',
            '=============',
            '',
        ]);

        $login = $input->getArgument('name');

        if ($this->userInteractor->checkIfUserExists($login)) {
            $output->writeln('<error>Пользователь с таким логином уже существует.</error>');
            exit;
        }

        $dto            = new UserDto();
        $dto->login     = $login;
        $dto->password  = $input->getArgument('password');
        $dto->isAdmin   = true;
        $dto->isActive  = true;
        $dto->addedFrom = User::ADDED_FROM_CONSOLE;

        $this->userInteractor->create($dto);
    }
}
<?php

namespace App\Interfaces;



use App\Domain\Interactor\User\User;
use App\Security\SymfonyUser;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserPasswordEncoder implements \App\Interactors\UserPasswordEncoderInterface
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function encodePassword($password)
    {
        $user = new SymfonyUser(new User());

        return $this->passwordEncoder->encodePassword($user, $password);
    }
    
    public function isPasswordValid($user, $password)
    {
        return $this->passwordEncoder->isPasswordValid(new SymfonyUser($user), $password);
    }
}
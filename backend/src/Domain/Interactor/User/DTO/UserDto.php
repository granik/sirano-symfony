<?php

namespace App\Domain\Interactor\User\DTO;


class UserDto
{
    public $login;
    public $password;
    public $isAdmin;
    public $customerId;
    public $activationCode;
    public $addedFrom;
    public $isActive = false;
}
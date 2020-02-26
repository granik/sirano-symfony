<?php

namespace App\Interactors;


interface UserPasswordEncoderInterface
{
    public function encodePassword($password);
    
    public function isPasswordValid($user, $password);
}
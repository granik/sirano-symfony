<?php


namespace App\Domain\Service;


use Exception;

final class UserUtils
{
    /**
     * @param string $login
     * @param string $password
     *
     * @return string
     */
    public static function makeActivationCode(string $login, string $password): string
    {
        return md5($password) . md5($login) . time();
    }
    
    /**
     * @return string
     *
     * @throws Exception
     */
    public static function getPassword(): string
    {
        $password = random_int(11111, 99999);
        
        return (string)$password;
    }
}
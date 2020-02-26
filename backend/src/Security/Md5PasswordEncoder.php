<?php


namespace App\Security;


use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

final class Md5PasswordEncoder implements PasswordEncoderInterface
{
    /**
     * Encodes the raw password.
     *
     * @param string $raw  The password to encode
     * @param string $salt The salt
     *
     * @return string The encoded password
     *
     * @throws BadCredentialsException   If the raw password is invalid, e.g. excessively long
     * @throws \InvalidArgumentException If the salt is invalid
     */
    public function encodePassword($raw, $salt)
    {
        return md5($raw);
    }
    
    /**
     * Checks a raw password against an encoded password.
     *
     * @param string $encoded An encoded password
     * @param string $raw     A raw password
     * @param string $salt    The salt
     *
     * @return bool true if the password is valid, false otherwise
     *
     * @throws \InvalidArgumentException If the salt is invalid
     */
    public function isPasswordValid($encoded, $raw, $salt)
    {
        return md5($raw) === $encoded;
    }
}
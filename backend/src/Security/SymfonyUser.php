<?php

namespace App\Security;


use App\Domain\Interactor\User\User;
use Symfony\Component\Security\Core\Encoder\EncoderAwareInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class SymfonyUser implements UserInterface, EncoderAwareInterface
{
    private $user;
    
    /**
     * SymfonyUser constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
    
    /**
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return ['ROLE_USER'];
     *     }
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        $roles = [];
        
        if (!empty($this->user->getCustomerId())) {
            $roles[] = 'ROLE_USER';
        }
        
        if ($this->user->isAdmin()) {
            $roles[] = 'ROLE_ADMIN';
        }
        
        return $roles;
    }
    
    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string The password
     */
    public function getPassword()
    {
        return $this->user->getPassword();
    }
    
    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }
    
    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->user->getLogin();
    }
    
    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
    
    /**
     * @return User \App\Domain\Interactor\User\User
     */
    public function getUser(): User
    {
        return $this->user;
    }
    
    /**
     * Gets the name of the encoder used to encode the password.
     *
     * If the method returns null, the standard way to retrieve the encoder
     * will be used instead.
     *
     * @return string
     */
    public function getEncoderName()
    {
        if ($this->user instanceof User && strlen($this->user->getPassword()) === 32) {
            return 'old_user';
        }
        
        return 'new_user';
    }
}
<?php

namespace App\Domain\Interactor\User;


use DateTime;

class User
{
    const ADDED_FROM_SITE    = 1;
    const ADDED_FROM_FILE    = 2;
    const ADDED_FROM_CONSOLE = 6;
    
    const ADDED_FROM_NAME = [
        self::ADDED_FROM_SITE    => 'Сайт',
        self::ADDED_FROM_FILE    => 'Файл',
        self::ADDED_FROM_CONSOLE => 'Консоль',
    ];
    
    /** @var int */
    private $id;
    
    /** @var string */
    private $login;
    
    /** @var string */
    private $password;
    
    /** @var boolean */
    private $isAdmin;
    
    /** @var int */
    private $customerId;
    
    /** @var string */
    private $activationCode;
    
    /** @var DateTime */
    private $activationDate;
    
    /** @var int */
    private $addedFrom;
    
    /** @var boolean */
    private $isActive;
    
    /** @var DateTime */
    private $registeredAt;
    
    /** @var int */
    private $sendingCounter = 0;
    
    /** @var DateTime|null */
    private $sendingDateTime;
    
    /**
     * @return DateTime
     */
    public function getActivationDate(): ?DateTime
    {
        return $this->activationDate;
    }
    
    /**
     * @param DateTime $activationDate
     *
     * @return User
     */
    public function setActivationDate(DateTime $activationDate): User
    {
        $this->activationDate = $activationDate;
        return $this;
    }
    
    /**
     * @return int
     */
    public function getAddedFrom(): int
    {
        return $this->addedFrom;
    }
    
    /**
     * @return string
     */
    public function getAddedFromName(): string
    {
        if (!isset(self::ADDED_FROM_NAME[$this->getAddedFrom()])) {
            return self::ADDED_FROM_NAME[self::ADDED_FROM_SITE];
        }
        
        return self::ADDED_FROM_NAME[$this->getAddedFrom()];
    }
    
    /**
     * @param int $addedFrom
     *
     * @return User
     */
    public function setAddedFrom(int $addedFrom): User
    {
        $this->addedFrom = $addedFrom;
        return $this;
    }
    
    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }
    
    /**
     * @param bool $isActive
     *
     * @return User
     */
    public function setIsActive(bool $isActive): User
    {
        $this->isActive = $isActive;
        return $this;
    }
    
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
    
    /**
     * @param int $id
     *
     * @return User
     */
    public function setId(int $id): User
    {
        $this->id = $id;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }
    
    /**
     * @param string $login
     *
     * @return User
     */
    public function setLogin(string $login): User
    {
        $this->login = $login;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }
    
    /**
     * @param string $password
     *
     * @return User
     */
    public function setPassword(string $password): User
    {
        $this->password = $password;
        return $this;
    }
    
    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->isAdmin;
    }
    
    /**
     * @param bool $isAdmin
     *
     * @return User
     */
    public function setIsAdmin(bool $isAdmin): User
    {
        $this->isAdmin = $isAdmin;
        return $this;
    }
    
    /**
     * @return int
     */
    public function getCustomerId(): ?int
    {
        return $this->customerId;
    }
    
    /**
     * @param int $customerId
     *
     * @return User
     */
    public function setCustomerId(int $customerId): User
    {
        $this->customerId = $customerId;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getActivationCode(): ?string
    {
        return $this->activationCode;
    }
    
    /**
     * @param string $activationCode
     *
     * @return User
     */
    public function setActivationCode(string $activationCode): User
    {
        $this->activationCode = $activationCode;
        return $this;
    }
    
    /**
     * @return DateTime
     */
    public function getRegisteredAt(): DateTime
    {
        return $this->registeredAt;
    }
    
    /**
     * @param DateTime $registeredAt
     *
     * @return User
     */
    public function setRegisteredAt(DateTime $registeredAt): User
    {
        $this->registeredAt = $registeredAt;
        return $this;
    }
    
    /**
     * @return int
     */
    public function getSendingCounter(): int
    {
        return $this->sendingCounter;
    }
    
    /**
     * @param int $sendingCounter
     *
     * @return User
     */
    public function setSendingCounter(int $sendingCounter): User
    {
        $this->sendingCounter = $sendingCounter;
        return $this;
    }
    
    /**
     * @return DateTime|null
     */
    public function getSendingDateTime(): ?DateTime
    {
        return $this->sendingDateTime;
    }
    
    /**
     * @param DateTime|null $sendingDateTime
     *
     * @return User
     */
    public function setSendingDateTime(?DateTime $sendingDateTime): User
    {
        $this->sendingDateTime = $sendingDateTime;
        return $this;
    }
    
    /**
     * @return User
     */
    public function incSendingCounter()
    {
        $this->sendingCounter++;
        
        return $this;
    }
}
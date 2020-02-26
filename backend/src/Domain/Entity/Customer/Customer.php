<?php

namespace App\Domain\Entity\Customer;


class Customer
{
    /**
     * @var int
     */
    private $id;
    
    /**
     * @var string
     */
    private $name;
    
    /**
     * @var string|null
     */
    private $middlename;
    
    /**
     * @var string
     */
    private $lastname;
    
    /**
     * @var string
     */
    private $phone;
    
    /**
     * @var string
     */
    private $email;
    
    /**
     * @var int|null
     */
    private $directionId;
    
    /**
     * @var string|null
     */
    private $avatar;
    
    /**
     * @var string
     */
    private $cityName;
    
    /**
     * @var int
     */
    private $cityId;
    
    /**
     * @var string
     */
    private $mainSpecialtyId;
    
    /**
     * @var int|null
     */
    private $additionalSpecialtyId;
    
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
     * @return Customer
     */
    public function setId(int $id): Customer
    {
        $this->id = $id;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
    
    /**
     * @param string $name
     *
     * @return Customer
     */
    public function setName(string $name): Customer
    {
        $this->name = $name;
        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getMiddlename(): ?string
    {
        return $this->middlename;
    }
    
    /**
     * @param string|null $middlename
     *
     * @return Customer
     */
    public function setMiddlename(?string $middlename): Customer
    {
        $this->middlename = $middlename;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getLastname(): string
    {
        return $this->lastname;
    }
    
    /**
     * @param string $lastname
     *
     * @return Customer
     */
    public function setLastname(string $lastname): Customer
    {
        $this->lastname = $lastname;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }
    
    /**
     * @param string $phone
     *
     * @return Customer
     */
    public function setPhone(string $phone): Customer
    {
        $this->phone = $phone;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }
    
    /**
     * @param string $email
     *
     * @return Customer
     */
    public function setEmail(string $email): Customer
    {
        $this->email = $email;
        return $this;
    }
    
    /**
     * @return int|null
     */
    public function getDirectionId(): ?int
    {
        return $this->directionId;
    }
    
    /**
     * @param int $directionId |null
     *
     * @return Customer
     */
    public function setDirectionId(?int $directionId): Customer
    {
        $this->directionId = $directionId;
        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getAvatar(): ?string
    {
        return $this->avatar;
    }
    
    /**
     * @param string|null $avatar
     *
     * @return Customer
     */
    public function setAvatar(?string $avatar): Customer
    {
        $this->avatar = $avatar;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getCityName(): string
    {
        return $this->cityName;
    }
    
    /**
     * @param string $cityName
     *
     * @return Customer
     */
    public function setCityName(string $cityName): Customer
    {
        $this->cityName = $cityName;
        return $this;
    }
    
    /**
     * @return int
     */
    public function getCityId(): int
    {
        return $this->cityId;
    }
    
    /**
     * @param int $cityId
     *
     * @return Customer
     */
    public function setCityId(int $cityId): Customer
    {
        $this->cityId = $cityId;
        
        return $this;
    }
    
    public function getMainSpecialtyId(): int
    {
        return $this->mainSpecialtyId;
    }
    
    /**
     * @param int $mainSpecialtyId
     *
     * @return Customer
     */
    public function setMainSpecialtyId(int $mainSpecialtyId): Customer
    {
        $this->mainSpecialtyId = $mainSpecialtyId;
        
        return $this;
    }
    
    /**
     * @return int|null
     */
    public function getAdditionalSpecialtyId(): ?int
    {
        return $this->additionalSpecialtyId;
    }
    
    /**
     * @param int|null $additionalSpecialtyId
     *
     * @return Customer
     */
    public function setAdditionalSpecialtyId(?int $additionalSpecialtyId): Customer
    {
        $this->additionalSpecialtyId = $additionalSpecialtyId;
        return $this;
    }
}
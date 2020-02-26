<?php


namespace App\Domain\Entity\PresidiumMember;


class PresidiumMember
{
    /** @var int */
    private $id;
    
    /** @var string Имя */
    private $name;

    /** @var string|null Отчество */
    private $middlename;

    /** @var string Фамилия */
    private $lastname;
    
    /** @var string|null Фотография */
    private $image;
    
    /** @var string Описание */
    private $description;
    
    /** @var boolean */
    private $isActive;
    
    /** @var int */
    private $number;
    
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
     * @return PresidiumMember
     */
    public function setId(int $id): PresidiumMember
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
     * @return PresidiumMember
     */
    public function setName(string $name): PresidiumMember
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
     * @return PresidiumMember
     */
    public function setMiddlename(?string $middlename): PresidiumMember
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
     * @return PresidiumMember
     */
    public function setLastname(string $lastname): PresidiumMember
    {
        $this->lastname = $lastname;
        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getImage(): ?string
    {
        return $this->image;
    }
    
    /**
     * @param string $image
     *
     * @return PresidiumMember
     */
    public function setImage(string $image): PresidiumMember
    {
        $this->image = $image;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }
    
    /**
     * @param string $description
     *
     * @return PresidiumMember
     */
    public function setDescription(string $description): PresidiumMember
    {
        $this->description = $description;
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
     * @return PresidiumMember
     */
    public function setIsActive(bool $isActive): PresidiumMember
    {
        $this->isActive = $isActive;
        return $this;
    }
    
    /**
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }
    
    /**
     * @param int $number
     *
     * @return PresidiumMember
     */
    public function setNumber(int $number): PresidiumMember
    {
        $this->number = $number;
        return $this;
    }
}
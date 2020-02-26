<?php


namespace App\Domain\Entity\Customer;


class CustomerCity
{
    /**
     * @var int
     */
    private $id;
    
    /**
     * @var string|null
     */
    private $kladrId;
    
    /**
     * @var string
     */
    private $country;
    
    /**
     * @var string
     */
    private $name;
    
    /**
     * @var string
     */
    private $fullName;
    
    /**
     * CustomerCity constructor.
     *
     * @param string|null $kladrId
     * @param string      $country
     * @param string      $name
     * @param string      $fullName
     */
    public function __construct(string $country, string $name, string $fullName, ?string $kladrId)
    {
        $this->kladrId  = $kladrId;
        $this->country  = $country;
        $this->name     = $name;
        $this->fullName = $fullName;
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
     * @return CustomerCity
     */
    public function setId(int $id): CustomerCity
    {
        $this->id = $id;
        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getKladrId(): ?string
    {
        return $this->kladrId;
    }
    
    /**
     * @param string|null $kladrId
     *
     * @return CustomerCity
     */
    public function setKladrId(?string $kladrId): CustomerCity
    {
        $this->kladrId = $kladrId;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }
    
    /**
     * @param string $country
     *
     * @return CustomerCity
     */
    public function setCountry(string $country): CustomerCity
    {
        $this->country = $country;
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
     * @return CustomerCity
     */
    public function setName(string $name): CustomerCity
    {
        $this->name = $name;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->fullName;
    }
    
    /**
     * @param string $fullName
     *
     * @return CustomerCity
     */
    public function setFullName(string $fullName): CustomerCity
    {
        $this->fullName = $fullName;
        return $this;
    }
}
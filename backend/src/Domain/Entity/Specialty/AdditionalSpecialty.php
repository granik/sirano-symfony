<?php

namespace App\Domain\Entity\Specialty;


class AdditionalSpecialty
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
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
    
    /**
     * @param int $id
     *
     * @return AdditionalSpecialty
     */
    public function setId(int $id): AdditionalSpecialty
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
     * @return AdditionalSpecialty
     */
    public function setName(string $name): AdditionalSpecialty
    {
        $this->name = $name;
        return $this;
    }
}
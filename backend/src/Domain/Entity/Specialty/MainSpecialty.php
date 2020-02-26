<?php

namespace App\Domain\Entity\Specialty;


class MainSpecialty
{
    const RESEARCHER_ID = 18;
    
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
     * @return MainSpecialty
     */
    public function setId(int $id): MainSpecialty
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
     * @return MainSpecialty
     */
    public function setName(string $name): MainSpecialty
    {
        $this->name = $name;
        return $this;
    }
    
    public function isResearcher()
    {
        if ($this->id === null) {
            return false;
        }
        
        return $this->id === self::RESEARCHER_ID;
    }
}
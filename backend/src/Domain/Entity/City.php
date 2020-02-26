<?php

namespace App\Domain\Entity;


class City
{
    /** @var int */
    private $id;
    
    /** @var string */
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
     * @return City
     */
    public function setId(int $id): City
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
     * @return City
     */
    public function setName(string $name): City
    {
        $this->name = $name;
        return $this;
    }
}
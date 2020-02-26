<?php


namespace App\Domain\Entity\Direction;


class Category
{
    /** @var int */
    private $id;
    
    /** @var string */
    private $name;
    
    /** @var Direction */
    private $direction;
    
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
     * @return Category
     */
    public function setId(int $id): Category
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
     * @return Category
     */
    public function setName(string $name): Category
    {
        $this->name = $name;
        return $this;
    }
    
    /**
     * @return Direction
     */
    public function getDirection(): Direction
    {
        return $this->direction;
    }
    
    /**
     * @param Direction $direction
     *
     * @return Category
     */
    public function setDirection(Direction $direction): Category
    {
        $this->direction = $direction;
        return $this;
    }
}
<?php


namespace App\Domain\Entity\Conference;


use App\Domain\Entity\Direction\Direction;

class ConferenceSeries
{
    /** @var int */
    private $id;
    /** @var string */
    private $name;
    /** @var Direction */
    private $direction;
    /** @var Conference[] */
    private $conferences = [];
    
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
     * @return ConferenceSeries
     */
    public function setId(int $id): ConferenceSeries
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
     * @return ConferenceSeries
     */
    public function setName(string $name): ConferenceSeries
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
     * @return ConferenceSeries
     */
    public function setDirection(Direction $direction): ConferenceSeries
    {
        $this->direction = $direction;
        return $this;
    }
    
    /**
     * @return Conference[]
     */
    public function getConferences(): array
    {
        return $this->conferences;
    }
    
    /**
     * @param Conference[] $conferences
     *
     * @return ConferenceSeries
     */
    public function setConferences(array $conferences): ConferenceSeries
    {
        $this->conferences = $conferences;
        return $this;
    }
}
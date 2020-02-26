<?php

namespace App\Domain\Entity\Conference;


use App\Domain\Entity\City;
use App\Domain\Entity\Direction\Direction;
use DateTime;

class Conference
{
    /** @var int */
    private $id;
    /** @var string */
    private $name;
    /** @var Direction */
    private $direction;
    /** @var ConferenceSeries|null */
    private $series;
    /** @var City */
    private $city;
    /** @var string */
    private $address;
    /** @var DateTime */
    private $startDateTime;
    /** @var DateTime */
    private $endDateTime;
    /** @var int */
    private $score;
    /** @var int */
    private $onlineScore = 0;
    /** @var string|null */
    private $description;
    /** @var boolean */
    private $isActive;
    /** @var ConferenceProgram[] */
    private $programs = [];
    /** @var ConferenceSubscriber[] */
    private $subscribers = [];
    
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
     * @return Conference
     */
    public function setId(int $id): Conference
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
     * @return Conference
     */
    public function setName(string $name): Conference
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
     * @return Conference
     */
    public function setDirection(Direction $direction): Conference
    {
        $this->direction = $direction;
        return $this;
    }
    
    /**
     * @return City
     */
    public function getCity(): City
    {
        return $this->city;
    }
    
    /**
     * @param City $city
     *
     * @return Conference
     */
    public function setCity(City $city): Conference
    {
        $this->city = $city;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }
    
    /**
     * @param string $address
     *
     * @return Conference
     */
    public function setAddress(string $address): Conference
    {
        $this->address = $address;
        return $this;
    }
    
    /**
     * @return DateTime
     */
    public function getStartDateTime(): DateTime
    {
        return $this->startDateTime;
    }
    
    /**
     * @param DateTime $startDateTime
     *
     * @return Conference
     */
    public function setStartDateTime(DateTime $startDateTime): Conference
    {
        $this->startDateTime = $startDateTime;
        return $this;
    }
    
    /**
     * @return DateTime
     */
    public function getEndDateTime(): DateTime
    {
        return $this->endDateTime;
    }
    
    /**
     * @param DateTime $endDateTime
     *
     * @return Conference
     */
    public function setEndDateTime(DateTime $endDateTime): Conference
    {
        $this->endDateTime = $endDateTime;
        return $this;
    }
    
    /**
     * @return int
     */
    public function getScore(): int
    {
        return $this->score;
    }
    
    /**
     * @param int $score
     *
     * @return Conference
     */
    public function setScore(int $score): Conference
    {
        $this->score = $score;
        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }
    
    /**
     * @param string|null $description
     *
     * @return Conference
     */
    public function setDescription(?string $description): Conference
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
     * @return Conference
     */
    public function setIsActive(bool $isActive): Conference
    {
        $this->isActive = $isActive;
        return $this;
    }
    
    /**
     * @return ConferenceProgram[]
     */
    public function getPrograms(): array
    {
        return $this->programs;
    }
    
    /**
     * @param ConferenceProgram[] $programs
     *
     * @return Conference
     */
    public function setPrograms(array $programs): Conference
    {
        $this->programs = $programs;
        return $this;
    }
    
    /**
     * @return ConferenceSubscriber[]
     */
    public function getSubscribers(): array
    {
        return $this->subscribers;
    }
    
    /**
     * @param ConferenceSubscriber[] $subscribers
     *
     * @return Conference
     */
    public function setSubscribers(array $subscribers): Conference
    {
        $this->subscribers = $subscribers;
        return $this;
    }
    
    public function isArchive()
    {
        $time     = (new DateTime())->modify('-1 hour');
        $interval = $time->diff($this->endDateTime);
        
        return $interval ->invert === 1;
    }
    
    /**
     * @return ConferenceSeries|null
     */
    public function getSeries(): ?ConferenceSeries
    {
        return $this->series;
    }
    
    /**
     * @param ConferenceSeries|null $series
     *
     * @return Conference
     */
    public function setSeries(?ConferenceSeries $series): Conference
    {
        $this->series = $series;
        return $this;
    }
    
    /**
     * @return int
     */
    public function getOnlineScore(): int
    {
        return $this->onlineScore;
    }
    
    /**
     * @param int $onlineScore
     *
     * @return Conference
     */
    public function setOnlineScore(int $onlineScore): Conference
    {
        $this->onlineScore = $onlineScore;
        return $this;
    }
}
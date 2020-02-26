<?php

namespace App\Domain\Entity\Conference;


use DateTime;

class ConferenceProgram
{
    /** @var int */
    private $id;
    /** @var Conference */
    private $conference;
    /** @var DateTime|null */
    private $fromTime;
    /** @var DateTime|null */
    private $tillTime;
    /** @var string|null */
    private $subject;
    /** @var string|null */
    private $lecturers;
    
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
     * @return ConferenceProgram
     */
    public function setId(int $id): ConferenceProgram
    {
        $this->id = $id;
        return $this;
    }
    
    /**
     * @return DateTime|null
     */
    public function getFromTime(): ?DateTime
    {
        return $this->fromTime;
    }
    
    /**
     * @param DateTime|null $fromTime
     *
     * @return ConferenceProgram
     */
    public function setFromTime(?DateTime $fromTime): ConferenceProgram
    {
        $this->fromTime = $fromTime;
        return $this;
    }
    
    /**
     * @return DateTime|null
     */
    public function getTillTime(): ?DateTime
    {
        return $this->tillTime;
    }
    
    /**
     * @param DateTime|null $tillTime
     *
     * @return ConferenceProgram
     */
    public function setTillTime(?DateTime $tillTime): ConferenceProgram
    {
        $this->tillTime = $tillTime;
        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getSubject(): ?string
    {
        return $this->subject;
    }
    
    /**
     * @param string|null $subject
     *
     * @return ConferenceProgram
     */
    public function setSubject(?string $subject): ConferenceProgram
    {
        $this->subject = $subject;
        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getLecturers(): ?string
    {
        return $this->lecturers;
    }
    
    /**
     * @param string|null $lecturers
     *
     * @return ConferenceProgram
     */
    public function setLecturers(?string $lecturers): ConferenceProgram
    {
        $this->lecturers = $lecturers;
        return $this;
    }
    
    /**
     * @return Conference
     */
    public function getConference(): Conference
    {
        return $this->conference;
    }
    
    /**
     * @param Conference $conference
     *
     * @return ConferenceProgram
     */
    public function setConference(Conference $conference): ConferenceProgram
    {
        $this->conference = $conference;
        return $this;
    }
}
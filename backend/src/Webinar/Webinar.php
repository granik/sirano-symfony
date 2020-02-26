<?php

namespace App\Webinar;


use App\Domain\Entity\Direction\Direction;
use DateTime;

class Webinar
{
    /** @var int */
    private $id;
    /** @var string */
    private $name;
    /** @var string|null */
    private $subject;
    /** @var string|null */
    private $description;
    /** @var \DateTime */
    private $startDatetime;
    /** @var \DateTime */
    private $endDatetime;
    /** @var string */
    private $youtubeCode;
    /** @var Direction */
    private $direction;
    /** @var int */
    private $score;
    /** @var \DateTime */
    private $confirmationTime1;
    /** @var \DateTime */
    private $confirmationTime2;
    /** @var \DateTime|null */
    private $confirmationTime3;
    /** @var string */
    private $email;
    /** @var boolean */
    private $isActive;
    /** @var WebinarSubscriber[] */
    private $subscribers = [];
    /** @var WebinarReport|null */
    private $report;
    
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
     * @return Webinar
     */
    public function setId(int $id): Webinar
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
     * @return Webinar
     */
    public function setName(string $name): Webinar
    {
        $this->name = $name;
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
     * @return Webinar
     */
    public function setSubject(?string $subject): Webinar
    {
        $this->subject = $subject;
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
     * @return Webinar
     */
    public function setDescription(?string $description): Webinar
    {
        $this->description = $description;
        return $this;
    }
    
    /**
     * @return \DateTime
     */
    public function getStartDatetime(): \DateTime
    {
        return $this->startDatetime;
    }
    
    /**
     * @param \DateTime $startDatetime
     *
     * @return Webinar
     */
    public function setStartDatetime(\DateTime $startDatetime): Webinar
    {
        $this->startDatetime = $startDatetime;
        return $this;
    }
    
    /**
     * @return \DateTime
     */
    public function getEndDatetime(): \DateTime
    {
        return $this->endDatetime;
    }
    
    /**
     * @param \DateTime $endDatetime
     *
     * @return Webinar
     */
    public function setEndDatetime(\DateTime $endDatetime): Webinar
    {
        $this->endDatetime = $endDatetime;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getYoutubeCode(): string
    {
        return $this->youtubeCode;
    }
    
    /**
     * @param string $youtubeCode
     *
     * @return Webinar
     */
    public function setYoutubeCode(string $youtubeCode): Webinar
    {
        $this->youtubeCode = $youtubeCode;
        return $this;
    }
    
    /**
     * @return \App\Domain\Entity\Direction\Direction
     */
    public function getDirection(): Direction
    {
        return $this->direction;
    }
    
    /**
     * @param \App\Domain\Entity\Direction\Direction $direction
     *
     * @return Webinar
     */
    public function setDirection(Direction $direction): Webinar
    {
        $this->direction = $direction;
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
     * @return Webinar
     */
    public function setScore(int $score): Webinar
    {
        $this->score = $score;
        return $this;
    }
    
    /**
     * @return \DateTime
     */
    public function getConfirmationTime1(): \DateTime
    {
        return $this->confirmationTime1;
    }
    
    /**
     * @param \DateTime $confirmationTime1
     *
     * @return Webinar
     */
    public function setConfirmationTime1(\DateTime $confirmationTime1): Webinar
    {
        $this->confirmationTime1 = $confirmationTime1;
        return $this;
    }
    
    /**
     * @return \DateTime
     */
    public function getConfirmationTime2(): \DateTime
    {
        return $this->confirmationTime2;
    }
    
    /**
     * @param \DateTime $confirmationTime2
     *
     * @return Webinar
     */
    public function setConfirmationTime2(\DateTime $confirmationTime2): Webinar
    {
        $this->confirmationTime2 = $confirmationTime2;
        return $this;
    }
    
    /**
     * @return \DateTime|null
     */
    public function getConfirmationTime3(): ?\DateTime
    {
        return $this->confirmationTime3;
    }
    
    /**
     * @param \DateTime|null $confirmationTime3
     *
     * @return Webinar
     */
    public function setConfirmationTime3(?\DateTime $confirmationTime3): Webinar
    {
        $this->confirmationTime3 = $confirmationTime3;
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
     * @return Webinar
     */
    public function setEmail(string $email): Webinar
    {
        $this->email = $email;
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
     * @return Webinar
     */
    public function setIsActive(bool $isActive): Webinar
    {
        $this->isActive = $isActive;
        return $this;
    }
    
    /**
     * @return bool
     * @throws \Exception
     */
    public function isNotStarted(): bool
    {
        $now           = new DateTime();
        $startInterval = $now->diff($this->getStartDatetime());
        
        return $startInterval->invert === 0;
    }
    
    /**
     * @return bool
     * @throws \Exception
     */
    public function isStarted(): bool
    {
        $now           = new DateTime();
        $startInterval = $now->diff($this->getStartDatetime());
        $endInterval   = $now->diff($this->getEndDatetime());
        
        return $startInterval->invert === 1 && $endInterval->invert === 0;
    }
    
    /**
     * @return bool
     * @throws \Exception
     */
    public function isComplete(): bool
    {
        $now         = new DateTime();
        $endInterval = $now->diff($this->getEndDatetime());
        
        return $endInterval->invert === 1;
    }
    
    /**
     * @return WebinarReport|null
     */
    public function getReport(): ?WebinarReport
    {
        return $this->report;
    }
    
    /**
     * @param WebinarReport $report
     *
     * @return Webinar
     */
    public function setReport(WebinarReport $report): Webinar
    {
        $this->report = $report;
        return $this;
    }
    
    public function isArchive()
    {
        $time     = (new DateTime())->modify('-1 hour');
        $interval = $time->diff($this->endDatetime);
        
        return $interval ->invert === 1;
    }
    
    /**
     * @return WebinarSubscriber[]
     */
    public function getSubscribers()
    {
        return $this->subscribers;
    }
    
    /**
     * @param WebinarSubscriber[] $subscribers
     *
     * @return Webinar
     */
    public function setSubscribers(array $subscribers): Webinar
    {
        $this->subscribers = $subscribers;
        return $this;
    }
    
    public function getMaxConfirmNumber()
    {
        return $this->confirmationTime3 === null ? 2 : 3;
    }
}
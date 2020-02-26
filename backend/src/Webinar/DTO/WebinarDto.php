<?php

namespace App\Webinar\DTO;


final class WebinarDto
{
    /** @var int */
    public $id;
    /** @var string */
    public $name;
    /** @var string|null */
    public $subject;
    /** @var string|null */
    public $description;
    /** @var \DateTime */
    public $startDatetime;
    /** @var string */
    public $startDate;
    /** @var string */
    public $startTime;
    /** @var \DateTime */
    public $endDatetime;
    /** @var string */
    public $youtubeCode;
    /** @var int */
    public $direction;
    /** @var string */
    public $directionName;
    /** @var int */
    public $score;
    /** @var \DateTime */
    public $confirmationTime1;
    /** @var \DateTime */
    public $confirmationTime2;
    /** @var \DateTime|null */
    public $confirmationTime3;
    /** @var string */
    public $email;
    /** @var bool */
    public $isActive;
    /** @var bool */
    public $isComplete;
    /** @var bool */
    public $isStarted;
    /** @var bool */
    public $isNotStarted;
    /** @var string */
    public $jsStartDatetime;
    /** @var string */
    public $jsEndDatetime;
    /** @var string */
    public $jsConfirmationTime1;
    /** @var string */
    public $jsConfirmationTime2;
    /** @var string */
    public $jsConfirmationTime3;
    /** @var bool */
    public $isSubscribed;
}
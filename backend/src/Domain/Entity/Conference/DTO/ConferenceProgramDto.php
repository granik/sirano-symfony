<?php

namespace App\Domain\Entity\Conference\DTO;


use DateTime;

final class ConferenceProgramDto
{
    /** @var DateTime|null */
    public $fromTime;
    /** @var DateTime|null */
    public $tillTime;
    /** @var string|null */
    public $subject;
    /** @var string|null */
    public $lecturers;
    /**
     * @var string
     */
    public $fromTimeString;
    /**
     * @var string
     */
    public $tillTimeString;
}
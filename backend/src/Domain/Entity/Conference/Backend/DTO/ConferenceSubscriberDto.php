<?php


namespace App\Domain\Entity\Conference\Backend\DTO;


final class ConferenceSubscriberDto
{
    public $name;
    public $lastname;
    public $middlename;
    public $city;
    public $phone;
    public $email;
    /**
     * @var int|null
     */
    public $mainSpecialtyId;
    /**
     * @var int|null
     */
    public $additionalSpecialtyId;
}
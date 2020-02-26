<?php


namespace App\Domain\Interactor;


interface SettingsInterface
{
    public function getMaxTries(): int;
    
    public function getHoursToConfirm(): int;
    
    public function setMaxTries($value);
    
    public function setHoursToConfirm($value);
}
<?php

namespace App\Tests\Domain\Entity\Conference;

use App\Domain\Entity\Conference\Conference;
use PHPUnit\Framework\TestCase;

class ConferenceTest extends TestCase
{
    public function testIsArchive()
    {
        $conference = new Conference();
        $conference
            ->setEndDatetime((new \DateTime())->modify('-2 hours'));
        
        $this->assertTrue($conference->isArchive());
        
        $conference
            ->setEndDatetime((new \DateTime())->modify('-1 hours'));
        
        $this->assertTrue($conference->isArchive());
        
        $conference
            ->setEndDatetime((new \DateTime())->modify('-30 minutes'));
        
        $this->assertFalse($conference->isArchive());
        
        $conference
            ->setEndDatetime(new \DateTime());
        
        $this->assertFalse($conference->isArchive());
        
        $conference
            ->setEndDatetime((new \DateTime())->modify('+1 hours'));
        
        $this->assertFalse($conference->isArchive());
    }
}

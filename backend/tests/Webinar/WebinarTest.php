<?php

namespace App\Tests\Webinar;


use App\Webinar\Webinar;
use PHPUnit\Framework\TestCase;

class WebinarTest extends TestCase
{
    public function testIsNotStarted()
    {
        $webinar = new Webinar();
        $webinar
            ->setStartDatetime((new \DateTime())->modify('-2 hours'))
            ->setEndDatetime((new \DateTime())->modify('-1 hours'));

        $this->assertFalse($webinar->isNotStarted());

        $webinar
            ->setStartDatetime((new \DateTime())->modify('-1 hours'))
            ->setEndDatetime((new \DateTime())->modify('+1 hours'));

        $this->assertFalse($webinar->isNotStarted());

        $webinar
            ->setStartDatetime((new \DateTime())->modify('+1 hours'))
            ->setEndDatetime((new \DateTime())->modify('+2 hours'));

        $this->assertTrue($webinar->isNotStarted());
    }

    public function testIsStarted()
    {
        $webinar = new Webinar();
        $webinar
            ->setStartDatetime((new \DateTime())->modify('-2 hours'))
            ->setEndDatetime((new \DateTime())->modify('-1 hours'));

        $this->assertFalse($webinar->isStarted());

        $webinar
            ->setStartDatetime((new \DateTime())->modify('-1 hours'))
            ->setEndDatetime((new \DateTime())->modify('+1 hours'));

        $this->assertTrue($webinar->isStarted());

        $webinar
            ->setStartDatetime((new \DateTime())->modify('+1 hours'))
            ->setEndDatetime((new \DateTime())->modify('+2 hours'));

        $this->assertFalse($webinar->isStarted());
    }

    public function testIsComplete()
    {
        $webinar = new Webinar();
        $webinar
            ->setStartDatetime((new \DateTime())->modify('-2 hours'))
            ->setEndDatetime((new \DateTime())->modify('-1 hours'));

        $this->assertTrue($webinar->isComplete());

        $webinar
            ->setStartDatetime((new \DateTime())->modify('-1 hours'))
            ->setEndDatetime((new \DateTime())->modify('+1 hours'));

        $this->assertFalse($webinar->isComplete());

        $webinar
            ->setStartDatetime((new \DateTime())->modify('+1 hours'))
            ->setEndDatetime((new \DateTime())->modify('+2 hours'));

        $this->assertFalse($webinar->isComplete());
    }

    public function testIsArchive()
    {
        $webinar = new Webinar();
        $webinar
            ->setEndDatetime((new \DateTime())->modify('-2 hours'));

        $this->assertTrue($webinar->isArchive());

        $webinar
            ->setEndDatetime((new \DateTime())->modify('-1 hours'));

        $this->assertTrue($webinar->isArchive());

        $webinar
            ->setEndDatetime((new \DateTime())->modify('-30 minutes'));

        $this->assertFalse($webinar->isArchive());

        $webinar
            ->setEndDatetime(new \DateTime());

        $this->assertFalse($webinar->isArchive());

        $webinar
            ->setEndDatetime((new \DateTime())->modify('+1 hours'));

        $this->assertFalse($webinar->isArchive());
    }
}
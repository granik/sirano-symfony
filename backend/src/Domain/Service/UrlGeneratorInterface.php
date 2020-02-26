<?php


namespace App\Domain\Service;


use App\Webinar\Webinar;

interface UrlGeneratorInterface
{
    public function urlForWebinar(Webinar $webinar);
}
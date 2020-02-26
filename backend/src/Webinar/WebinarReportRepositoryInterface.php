<?php

namespace App\Webinar;


interface WebinarReportRepositoryInterface
{
    public function store(WebinarReport $report);
    
    public function update(WebinarReport $report);
}
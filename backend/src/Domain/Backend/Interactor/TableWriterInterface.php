<?php


namespace App\Domain\Backend\Interactor;


interface TableWriterInterface
{
    public function write(array $report);
}
<?php


namespace App\Domain\Frontend\Interactor;


interface CounterInterface
{
    public function getTodayViews();
    
    public function getAllViews();
}
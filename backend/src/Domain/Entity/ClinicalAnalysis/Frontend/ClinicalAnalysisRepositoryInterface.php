<?php


namespace App\Domain\Entity\ClinicalAnalysis\Frontend;


use App\Domain\Entity\ClinicalAnalysis\ClinicalAnalysis;
use App\Domain\Entity\Module\Module;

interface ClinicalAnalysisRepositoryInterface
{
    public function list(int $page, int $perPage, $direction, $category);

    public function find($id);
    
    public function findByModule(Module $module): ?ClinicalAnalysis;
}
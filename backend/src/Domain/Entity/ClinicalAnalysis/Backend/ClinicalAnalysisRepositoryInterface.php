<?php


namespace App\Domain\Entity\ClinicalAnalysis\Backend;


use App\Domain\Entity\ClinicalAnalysis\ClinicalAnalysisSlide;
use App\Domain\Entity\ClinicalAnalysis\ClinicalAnalysis;

interface ClinicalAnalysisRepositoryInterface
{
    public function list(int $page, int $perPage, array $criteria);
    
    public function store(ClinicalAnalysis $entity);
    
    public function find($id);
    
    public function update(ClinicalAnalysis $entity);
    
    public function storeSlide(ClinicalAnalysisSlide $slide);
    
    public function delete($entity);
    
    public function deleteSlidesByIds(array $deleteIds);
}
<?php

namespace App\Domain\Entity\ClinicalAnalysis;


class ClinicalAnalysisSlide
{
    /** @var int */
    private $id;
    
    /** @var ClinicalAnalysis */
    private $clinicalAnalysis;
    
    /** @var string Заголовок */
    private $name;
    
    /** @var string|null */
    private $image;
    
    /** @var int Порядковый номер */
    private $number;
    
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
    
    /**
     * @param int $id
     *
     * @return ClinicalAnalysisSlide
     */
    public function setId(int $id): ClinicalAnalysisSlide
    {
        $this->id = $id;
        return $this;
    }
    
    /**
     * @return ClinicalAnalysis
     */
    public function getClinicalAnalysis(): ClinicalAnalysis
    {
        return $this->clinicalAnalysis;
    }
    
    /**
     * @param ClinicalAnalysis $clinicalAnalysis
     *
     * @return ClinicalAnalysisSlide
     */
    public function setClinicalAnalysis(ClinicalAnalysis $clinicalAnalysis): ClinicalAnalysisSlide
    {
        $this->clinicalAnalysis = $clinicalAnalysis;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
    
    /**
     * @param string $name
     *
     * @return ClinicalAnalysisSlide
     */
    public function setName(string $name): ClinicalAnalysisSlide
    {
        $this->name = $name;
        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getImage(): ?string
    {
        return $this->image;
    }
    
    /**
     * @param string $image
     *
     * @return ClinicalAnalysisSlide
     */
    public function setImage(string $image): ClinicalAnalysisSlide
    {
        $this->image = $image;
        return $this;
    }
    
    /**
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }
    
    /**
     * @param int $number
     *
     * @return ClinicalAnalysisSlide
     */
    public function setNumber(int $number): ClinicalAnalysisSlide
    {
        $this->number = $number;
        return $this;
    }
}
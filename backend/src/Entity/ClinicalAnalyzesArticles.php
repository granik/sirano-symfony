<?php


namespace App\Entity;


use App\Domain\Entity\Article\Article;
use App\Domain\Entity\ClinicalAnalysis\ClinicalAnalysis;

class ClinicalAnalyzesArticles
{
    /** @var ClinicalAnalysis */
    private $clinicalAnalysis;
    
    /** @var Article */
    private $article;
    
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
     * @return ClinicalAnalyzesArticles
     */
    public function setClinicalAnalysis(ClinicalAnalysis $clinicalAnalysis): ClinicalAnalyzesArticles
    {
        $this->clinicalAnalysis = $clinicalAnalysis;
        return $this;
    }
    
    /**
     * @return Article
     */
    public function getArticle(): Article
    {
        return $this->article;
    }
    
    /**
     * @param Article $article
     *
     * @return ClinicalAnalyzesArticles
     */
    public function setArticle(Article $article): ClinicalAnalyzesArticles
    {
        $this->article = $article;
        return $this;
    }
}
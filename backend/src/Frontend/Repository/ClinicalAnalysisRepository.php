<?php


namespace App\Frontend\Repository;


use App\Domain\Entity\ClinicalAnalysis\ClinicalAnalysis;
use App\Domain\Entity\ClinicalAnalysis\ClinicalAnalysisSlide;
use App\Domain\Entity\ClinicalAnalysis\Frontend\ClinicalAnalysisRepositoryInterface;
use App\Domain\Entity\Direction\Category;
use App\Domain\Entity\Direction\Direction;
use App\Domain\Entity\Module\Module;
use App\Entity\ClinicalAnalyzesArticles;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

final class ClinicalAnalysisRepository implements ClinicalAnalysisRepositoryInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    
    /**
     * @var ObjectRepository
     */
    private $objectRepository;
    
    /**
     * @var ObjectRepository
     */
    private $slideRepository;
    
    /**
     * @var ObjectRepository
     */
    private $articleRepository;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager     = $entityManager;
        $this->objectRepository  = $this->entityManager->getRepository(ClinicalAnalysis::class);
        $this->slideRepository   = $this->entityManager->getRepository(ClinicalAnalysisSlide::class);
        $this->articleRepository = $this->entityManager->getRepository(ClinicalAnalyzesArticles::class);
    }
    
    public function list(int $page, int $perPage, $direction, $category)
    {
        $where = '';
        
        if ($direction instanceof Direction) {
            $where = ' AND c.direction = :direction ';
        }
    
        if ($category instanceof Category) {
            $where .= ' AND c.category = :category ';
        }
    
        $dql   = 'SELECT c FROM '
            . ClinicalAnalysis::class
            . ' c WHERE c.isActive = TRUE '
            . $where
            . ' ORDER BY c.number';
        $query = $this->entityManager->createQuery($dql)
            ->setFirstResult(($page - 1) * $perPage)
            ->setMaxResults($perPage);
        
        if ($direction instanceof Direction) {
            $query->setParameter('direction', $direction);
        }
    
        if ($category instanceof Category) {
            $query->setParameter('category', $category);
        }
        
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        
        return $paginator;
    }
    
    public function find($id): ?ClinicalAnalysis
    {
        $entity = $this->objectRepository->find($id);
        
        if ($entity instanceof ClinicalAnalysis) {
            $this->hydrate($entity);
        }
        
        return $entity;
    }
    
    private function hydrate(ClinicalAnalysis $clinicalAnalysis)
    {
        $slides = $this->slideRepository->findBy(['clinicalAnalysis' => $clinicalAnalysis], ['number' => 'asc']);
        $clinicalAnalysis->setSlides($slides);
        
        $clinicalAnalyzesArticles = $this->articleRepository->findBy(['clinicalAnalysis' => $clinicalAnalysis]);
        $articles                 = [];
        foreach ($clinicalAnalyzesArticles as $clinicalAnalyzesArticle) {
            $articles[] = $clinicalAnalyzesArticle->getArticle();
        }
        
        $clinicalAnalysis->setArticles($articles);
    }
    
    public function findByModule(Module $module): ?ClinicalAnalysis
    {
        return $this->objectRepository->findOneBy(['module' => $module]);
    }
}
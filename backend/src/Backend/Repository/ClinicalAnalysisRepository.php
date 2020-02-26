<?php


namespace App\Backend\Repository;


use App\Domain\Entity\ClinicalAnalysis\Backend\ClinicalAnalysisRepositoryInterface;
use App\Domain\Entity\ClinicalAnalysis\ClinicalAnalysis;
use App\Domain\Entity\ClinicalAnalysis\ClinicalAnalysisSlide;
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
    
    public function list(int $page, int $perPage, array $criteria)
    {
        $whereClauses = [];
    
        if (isset($criteria['number']) && $criteria['number'] !== null) {
            $whereClauses[] = 'c.number = :number';
        }
    
        if (isset($criteria['name']) && $criteria['name'] !== null) {
            $whereClauses[] = 'c.name LIKE :name';
        }
    
        $where = '';
        if (!empty($whereClauses)) {
            $where = ' WHERE ' . implode(' AND ', $whereClauses);
        }
    
        $dql   = 'SELECT c FROM ' . ClinicalAnalysis::class . ' c ' . $where . ' ORDER BY c.number';
        $query = $this->entityManager->createQuery($dql)
            ->setFirstResult(($page - 1) * $perPage)
            ->setMaxResults($perPage);
    
        if (isset($criteria['number']) && $criteria['number'] !== null) {
            $query->setParameter('number', $criteria['number']);
        }
    
        if (isset($criteria['name']) && $criteria['name'] !== null) {
            $query->setParameter('name', "%{$criteria['name']}%");
        }
        
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        
        return $paginator;
    }
    
    public function store(ClinicalAnalysis $entity)
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        
        $this->storeClinicalAnalysisArticles($entity);
        
        return $entity;
    }
    
    public function find($id)
    {
        $entity = $this->objectRepository->find($id);
        
        if ($entity instanceof ClinicalAnalysis) {
            $this->hydrate($entity);
        }
        
        return $entity;
    }
    
    public function update(ClinicalAnalysis $entity)
    {
        $this->entityManager
            ->createQuery('DELETE ' . ClinicalAnalyzesArticles::class . ' c WHERE c.clinicalAnalysis = :clinicalAnalysis')
            ->setParameter('clinicalAnalysis', $entity)
            ->getResult();
        
        $this->storeClinicalAnalysisArticles($entity);
        
        return $entity;
    }
    
    public function storeSlide(ClinicalAnalysisSlide $slide)
    {
        $this->entityManager->persist($slide);
        $this->entityManager->flush();
    }
    
    public function delete($entity)
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }
    
    public function deleteSlidesByIds(array $deleteIds)
    {
        $this->entityManager
            ->createQuery('DELETE ' . ClinicalAnalysisSlide::class . ' c WHERE c.id IN (:ids)')
            ->setParameter('ids', $deleteIds)
            ->getResult();
    }
    
    private function storeClinicalAnalysisArticles(ClinicalAnalysis $entity)
    {
        foreach ($entity->getArticles() as $article) {
            $entityArticle = (new ClinicalAnalyzesArticles())
                ->setClinicalAnalysis($entity)
                ->setArticle($article);
            $this->entityManager->persist($entityArticle);
        }
        
        $this->entityManager->flush();
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
}
<?php


namespace App\Frontend\Repository;


use App\Domain\Entity\Article\Article;
use App\Domain\Entity\Article\Frontend\ArticleRepositoryInterface;
use App\Domain\Entity\Direction\Category;
use App\Domain\Entity\Direction\Direction;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

final class ArticleRepository implements ArticleRepositoryInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    
    /**
     * @var ObjectRepository
     */
    private $objectRepository;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager    = $entityManager;
        $this->objectRepository = $this->entityManager->getRepository(Article::class);
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
            . Article::class
            . ' c WHERE c.isActive = TRUE '
            . $where;
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
}
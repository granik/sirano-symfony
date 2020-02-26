<?php


namespace App\Frontend\Repository;


use App\Domain\Entity\Direction\Direction;
use App\Domain\Entity\News\Frontend\NewsRepositoryInterface;
use App\Domain\Entity\News\News;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

final class NewsRepository implements NewsRepositoryInterface
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
        $this->entityManager    = $entityManager;
        $this->objectRepository = $this->entityManager->getRepository(News::class);
    }
    
    public function list(int $page, int $perPage)
    {
        $dql   = 'SELECT c FROM ' . News::class . ' c WHERE c.isActive = TRUE ORDER BY c.createdAt DESC';
        $query = $this->entityManager->createQuery($dql)
            ->setFirstResult(($page - 1) * $perPage)
            ->setMaxResults($perPage);
        
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        
        return $paginator;
    }
    
    public function find($id): ?News
    {
        $entity = $this->objectRepository->find($id);
        
        return $entity;
    }
    
    public function randomNews(News $news)
    {
        $resultSetMapping = new ResultSetMappingBuilder($this->entityManager);
        $resultSetMapping->addRootEntityFromClassMetadata(News::class, 'c');
        
        $query = $this->entityManager->createNativeQuery(<<<SQL
SELECT
    `c`.`id`,
    `c`.`direction_id`,
    `c`.`name`,
    `c`.`created_at`,
    `c`.`announce_image`,
    `c`.`image`,
    `c`.`text`,
    `c`.`is_active`
FROM
    `news` `c`
WHERE
    `is_active` IS TRUE
    AND `c`.`id` != :id
ORDER BY
    RAND()
LIMIT
    1
SQL
            , $resultSetMapping);
        $query->setParameter('id', $news->getId());
        
        return $query->getOneOrNullResult();
    }
    
    public function mainPage(int $limit, $direction)
    {
        $criteria = ['isActive' => true];
        
        if ($direction instanceof Direction) {
            $criteria['direction'] = $direction;
        }
        
        return $this->objectRepository->findBy($criteria, ['createdAt' => 'desc'], $limit);
    }
}
<?php


namespace App\Backend\Repository;


use App\Domain\Entity\Specialty\MainSpecialty;
use App\Domain\Entity\Specialty\MainSpecialtyRepositoryInterface;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

final class MainSpecialtyRepository implements MainSpecialtyRepositoryInterface
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
        $this->objectRepository = $this->entityManager->getRepository(MainSpecialty::class);
    }
    
    public function list(int $page, int $perPage, array $criteria)
    {
        $dql   = 'SELECT c FROM ' . MainSpecialty::class . ' c ORDER BY c.name';
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
    
    public function store(MainSpecialty $entity)
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }
    
    public function find($id)
    {
        return $this->objectRepository->find($id);
    }
    
    public function update(MainSpecialty $entity)
    {
        $this->entityManager->flush();
    }
    
    public function delete($entity)
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }
    
    public function customerFormlist()
    {
        $resultSetMapping = new ResultSetMappingBuilder($this->entityManager);
        $resultSetMapping->addRootEntityFromClassMetadata(MainSpecialty::class, 'c');
        
        $query = $this->entityManager->createNativeQuery(<<<SQL
SELECT
    `c`.`id`,
    `c`.`name`
FROM
    `main_specialty` `c`
ORDER BY
    `name` = 'Другое',
    `name` ASC
SQL
            , $resultSetMapping);
        
        return $query->getResult();
    }
    
    public function findByName($name)
    {
        return $this->objectRepository->findOneBy(['name' => $name]);
    }
}
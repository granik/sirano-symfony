<?php

namespace App\Backend\Repository;


use App\Domain\Entity\Module\Backend\ModuleTestRepositoryInterface;
use App\Domain\Entity\Module\ModuleTest;
use App\Domain\Entity\Module\ModuleTestQuestion;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\UnitOfWork;
use Exception;

final class ModuleTestRepository implements ModuleTestRepositoryInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;
    
    /** @var ObjectRepository */
    private $objectRepository;
    /**
     * @var ObjectRepository
     */
    private $questionRepository;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager      = $entityManager;
        $this->objectRepository   = $this->entityManager->getRepository(ModuleTest::class);
        $this->questionRepository = $this->entityManager->getRepository(ModuleTestQuestion::class);
    }
    
    public function list(int $page, int $perPage)
    {
        $dql   = 'SELECT c FROM ' . ModuleTest::class . ' c';
        $query = $this->entityManager->createQuery($dql)
            ->setFirstResult(($page - 1) * $perPage)
            ->setMaxResults($perPage);
        
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        
        return $paginator;
    }
    
    public function store(ModuleTest $entity)
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        
        return $entity;
    }
    
    public function find($id)
    {
        $entity = $this->objectRepository->find($id);
        
        if ($entity instanceof ModuleTest) {
            $this->hydrate($entity);
        }
        
        return $entity;
    }
    
    public function update(ModuleTest $entity)
    {
        $questionIds = [];
        
        foreach ($entity->getQuestions() as $question) {
            if ($this->entityManager->getUnitOfWork()->getEntityState($question) === UnitOfWork::STATE_NEW) {
                $this->entityManager->persist($question->setTest($entity));
            } else {
                $questionIds[] = $question->getId();
            }
        }
        
        $query = $this->entityManager->createQuery('DELETE ' . ModuleTestQuestion::class . ' c WHERE c.test = :test AND c.id NOT IN (:ids)');
        $query
            ->setParameter('test', $entity)
            ->setParameter('ids', $questionIds)
            ->getResult();
        
        $this->entityManager->flush();
        
        return $entity;
    }
    
    /**
     * @param ModuleTest $entity
     *
     * @throws Exception
     */
    private function hydrate(ModuleTest $entity)
    {
        $questions = $this->questionRepository->findBy(['test' => $entity]);
        
        if (count($questions) < ModuleTest::QUESTIONS_NUMBER) {
            $needQuestions = ModuleTest::QUESTIONS_NUMBER - count($questions);
            
            for ($i = 1; $i <= $needQuestions; $i++) {
                $questions[] = new ModuleTestQuestion();
            }
        }
        
        $errors = $entity->setQuestions($questions);
        
        if (is_array($errors)) {
            throw new Exception(implode("\n", $errors));
        }
    }
}
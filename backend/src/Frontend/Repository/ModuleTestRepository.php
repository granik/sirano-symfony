<?php

namespace App\Frontend\Repository;


use App\Domain\Entity\Module\Frontend\ModuleTestRepositoryInterface;
use App\Domain\Entity\Module\ModuleTest;
use App\Domain\Entity\Module\ModuleTestQuestion;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
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
    
    /**
     * @param $id
     *
     * @return ModuleTest|null
     * @throws Exception
     */
    public function find($id)
    {
        $entity = $this->objectRepository->find($id);
        
        if ($entity instanceof ModuleTest) {
            $this->hydrate($entity);
        }
        
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
        $errors    = $entity->setQuestions($questions);
        
        if (is_array($errors)) {
            throw new Exception(implode("\n", $errors));
        }
    }
}
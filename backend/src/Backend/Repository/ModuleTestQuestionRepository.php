<?php

namespace App\Backend\Repository;


use App\Domain\Entity\Module\Backend\ModuleTestQuestionRepositoryInterface;
use App\Domain\Entity\Module\ModuleTestQuestion;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;

final class ModuleTestQuestionRepository implements ModuleTestQuestionRepositoryInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;
    
    /** @var ObjectRepository */
    private $objectRepository;
    /**
     * @var ObjectRepository
     */
    private $slideRepository;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager    = $entityManager;
        $this->objectRepository = $this->entityManager->getRepository(ModuleTestQuestion::class);
    }
    
    public function find($id): ?ModuleTestQuestion
    {
        return $this->objectRepository->find($id);
    }
}
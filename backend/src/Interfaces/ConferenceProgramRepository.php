<?php

namespace App\Interfaces;


use App\Domain\Entity\Conference\Conference;
use App\Domain\Entity\Conference\ConferenceProgram;
use App\Domain\Entity\Conference\ConferenceProgramRepositoryInterface;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;

final class ConferenceProgramRepository implements ConferenceProgramRepositoryInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;
    
    /** @var ObjectRepository */
    private $objectRepository;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager    = $entityManager;
        $this->objectRepository = $this->entityManager->getRepository(ConferenceProgram::class);
    }
    
    public function deleteConferencePrograms(Conference $conference)
    {
        $programs = $this->objectRepository->findBy(['conference' => $conference]);
        
        foreach ($programs as $program) {
            $this->entityManager->remove($program);
        }
        
        $this->entityManager->flush();
    }
    
    public function addConferencePrograms(Conference $conference, array $programs)
    {
        /** @var ConferenceProgram $program */
        foreach ($programs as $program) {
            $this->entityManager->persist($program->setConference($conference));
        }
        
        $this->entityManager->flush();
    }
}
<?php

namespace App\Domain\Entity\Module\Frontend\DTO;


use App\Domain\Entity\Module\ModuleTest;
use App\Domain\Frontend\Interactor\ModuleTestInteractor;
use App\DTO\DtoAssembler;
use App\Security\SymfonyUser;
use Symfony\Component\Security\Core\Security;

final class ModuleTestDtoAssembler extends DtoAssembler
{
    /**
     * @var ModuleTestQuestionAssembler
     */
    private $questionAssembler;
    /**
     * @var ModuleTestInteractor
     */
    private $testInteractor;
    /**
     * @var Security
     */
    private $security;
    
    /**
     * ModuleTestDtoAssembler constructor.
     *
     * @param ModuleTestQuestionAssembler $questionAssembler
     * @param ModuleTestInteractor        $testInteractor
     * @param Security                    $security
     */
    public function __construct(
        ModuleTestQuestionAssembler $questionAssembler,
        ModuleTestInteractor $testInteractor,
        Security $security
    ) {
        $this->questionAssembler = $questionAssembler;
        $this->testInteractor    = $testInteractor;
        $this->security          = $security;
    }
    
    protected function createDto()
    {
        return new ModuleTestDto();
    }
    
    /**
     * @param ModuleTestDto $dto
     * @param ModuleTest    $entity
     */
    protected function fill($dto, $entity)
    {
        $dto->id         = $entity->getId();
        $dto->name       = $entity->getName();
        $dto->questions  = $this->questionAssembler->assembleList($entity->getQuestions());
        $dto->moduleId   = $entity->getModule()->getId();
        $dto->moduleName = $entity->getModule()->getName();
        
        $symfonyUser = $this->security->getUser();
        if ($symfonyUser instanceof SymfonyUser) {
            $user        = $symfonyUser->getUser();
            $dto->passed = $this->testInteractor->isTested($entity, $user);
        }
    }
}
<?php

namespace App\Domain\Entity\Module\Backend\DTO;


use App\Domain\Entity\Module\ModuleTest;
use App\DTO\DtoAssembler;

final class ModuleTestDtoAssembler extends DtoAssembler
{
    /**
     * @var ModuleTestQuestionAssembler
     */
    private $questionAssembler;
    
    /**
     * ModuleTestDtoAssembler constructor.
     *
     * @param ModuleTestQuestionAssembler $questionAssembler
     */
    public function __construct(ModuleTestQuestionAssembler $questionAssembler)
    {
        $this->questionAssembler = $questionAssembler;
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
        if ($entity->getModule()->getTest() instanceof ModuleTest) {
            $dto->id        = $entity->getId();
            $dto->name      = $entity->getName();
            $dto->questions = $this->questionAssembler->assembleList($entity->getQuestions());
        } else {
            for ($i = 1; $i <= ModuleTest::QUESTIONS_NUMBER; $i++) {
                $dto->questions[] = new ModuleTestQuestionDto();
            }
        }
    }
}
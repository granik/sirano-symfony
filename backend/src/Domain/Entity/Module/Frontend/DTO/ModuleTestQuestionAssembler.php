<?php

namespace App\Domain\Entity\Module\Frontend\DTO;


use App\Domain\Entity\Module\ModuleTestQuestion;
use App\DTO\DtoAssembler;

final class ModuleTestQuestionAssembler extends DtoAssembler
{
    protected function createDto()
    {
        return new ModuleTestQuestionDto();
    }
    
    /**
     * @param ModuleTestQuestionDto $dto
     * @param ModuleTestQuestion    $entity
     */
    protected function fill($dto, $entity)
    {
        $dto->id          = $entity->getId();
        $dto->question    = $entity->getQuestion();
        $dto->answer1     = $entity->getAnswer1();
        $dto->answer2     = $entity->getAnswer2();
        $dto->answer3     = $entity->getAnswer3();
        $dto->answer4     = $entity->getAnswer4();
        $dto->rightAnswer = $entity->getRightAnswer();
    }
}
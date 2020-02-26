<?php

namespace App\DTO;


use App\Domain\Entity\Conference\ConferenceProgram;
use App\Domain\Entity\Conference\DTO\ConferenceProgramDto;

final class ConferenceProgramDtoAssembler extends DtoAssembler
{
    protected function createDto()
    {
        return new ConferenceProgramDto();
    }
    
    /**
     * @param \App\Domain\Entity\Conference\DTO\ConferenceProgramDto $dto
     * @param ConferenceProgram                                      $entity
     */
    protected function fill($dto, $entity)
    {
        $dto->fromTime       = $entity->getFromTime();
        $dto->tillTime       = $entity->getTillTime();
        $dto->fromTimeString = $entity->getFromTime() === null ? '' : $entity->getFromTime()->format('H:i');
        $dto->tillTimeString = $entity->getTillTime() === null ? '' : $entity->getTillTime()->format('H:i');
        $dto->subject        = $entity->getSubject();
        $dto->lecturers      = $entity->getLecturers();
    }
}
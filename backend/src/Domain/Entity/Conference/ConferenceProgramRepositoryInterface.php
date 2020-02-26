<?php

namespace App\Domain\Entity\Conference;


interface ConferenceProgramRepositoryInterface
{
    public function deleteConferencePrograms(Conference $conference);
    
    public function addConferencePrograms(Conference $conference, array $programs);
}
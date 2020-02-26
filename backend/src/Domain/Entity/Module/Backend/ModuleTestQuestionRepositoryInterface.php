<?php

namespace App\Domain\Entity\Module\Backend;


use App\Domain\Entity\Module\ModuleTestQuestion;

interface ModuleTestQuestionRepositoryInterface
{
    public function find($id): ?ModuleTestQuestion;
}
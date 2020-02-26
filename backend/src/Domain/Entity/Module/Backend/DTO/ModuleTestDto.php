<?php

namespace App\Domain\Entity\Module\Backend\DTO;


final class ModuleTestDto
{
    /** @var int */
    public $id;
    
    /** @var string Название */
    public $name;
    
    /** @var array Список вопросов */
    public $questions = [];
}
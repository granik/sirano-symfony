<?php

namespace App\Domain\Entity\Module\Frontend\DTO;


final class ModuleTestDto
{
    /** @var string Название */
    public $name;
    
    /** @var array Список вопросов */
    public $questions = [];
    
    /**
     * @var int
     */
    public $moduleId;
    
    /**
     * @var string
     */
    public $moduleName;
    
    /** @var bool */
    public $passed = false;
}
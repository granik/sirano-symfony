<?php

namespace App\Domain\Entity\Module\Frontend\DTO;


final class ModuleTestQuestionDto
{
    /** @var int */
    public $id;
    
    /** @var string Текст вопроса */
    public $question;
    
    /** @var string Ответ А */
    public $answer1;
    
    /** @var string Ответ B */
    public $answer2;
    
    /** @var string Ответ C */
    public $answer3;
    
    /** @var string Ответ D */
    public $answer4;
    
    /** @var int Правильный ответ */
    public $rightAnswer;
}
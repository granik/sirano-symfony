<?php

namespace App\Domain\Entity\Module;


class ModuleTest
{
    const QUESTIONS_NUMBER = 10;
    
    /** @var int */
    private $id;
    
    /** @var Module */
    private $module;
    
    /** @var string Название */
    private $name;
    
    /** @var ModuleTestQuestion[] Список вопросов */
    private $questions = [];
    
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
    
    /**
     * @param int $id
     *
     * @return ModuleTest
     */
    public function setId(int $id): ModuleTest
    {
        $this->id = $id;
        return $this;
    }
    
    /**
     * @return Module
     */
    public function getModule(): Module
    {
        return $this->module;
    }
    
    /**
     * @param Module $module
     *
     * @return ModuleTest
     */
    public function setModule(Module $module): ModuleTest
    {
        $this->module = $module;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
    
    /**
     * @param string $name
     *
     * @return ModuleTest
     */
    public function setName(string $name): ModuleTest
    {
        $this->name = $name;
        return $this;
    }
    
    /**
     * @return ModuleTestQuestion[]
     */
    public function getQuestions(): array
    {
        return $this->questions;
    }
    
    /**
     * @param array $questions
     *
     * @return array|null
     */
    public function setQuestions(array $questions)
    {
        if (count($questions) !== self::QUESTIONS_NUMBER) {
            return ['Test can only contain '.self::QUESTIONS_NUMBER.' questions'];
        }
    
        $this->questions = $questions;
        
        return null;
    }
    
    public function addQuestion(ModuleTestQuestion $question)
    {
        if (count($this->questions) === self::QUESTIONS_NUMBER) {
            return ['Test can only contain '.self::QUESTIONS_NUMBER.' questions'];
        }
        
        $this->questions[] = $question;
        
        return null;
    }
}
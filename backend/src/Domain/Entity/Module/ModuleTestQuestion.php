<?php

namespace App\Domain\Entity\Module;


class ModuleTestQuestion
{
    const ANSWERS_NUMBER = 4;
    
    /** @var int */
    private $id;
    
    /** @var ModuleTest|null */
    private $test;
    
    /** @var string Текст вопроса */
    private $question;
    
    /** @var string Ответ А */
    private $answer1;
    
    /** @var string Ответ B */
    private $answer2;
    
    /** @var string Ответ C */
    private $answer3;
    
    /** @var string Ответ D */
    private $answer4;
    
    /** @var int Правильный ответ */
    private $rightAnswer;
    
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
     * @return ModuleTestQuestion
     */
    public function setId(int $id): ModuleTestQuestion
    {
        $this->id = $id;
        return $this;
    }
    
    /**
     * @return ModuleTest|null
     */
    public function getTest(): ?ModuleTest
    {
        return $this->test;
    }
    
    /**
     * @param ModuleTest $test
     *
     * @return ModuleTestQuestion
     */
    public function setTest(ModuleTest $test): ModuleTestQuestion
    {
        $this->test = $test;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getQuestion(): string
    {
        return $this->question;
    }
    
    /**
     * @param string $question
     *
     * @return ModuleTestQuestion
     */
    public function setQuestion(string $question): ModuleTestQuestion
    {
        $this->question = $question;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getAnswer1(): string
    {
        return $this->answer1;
    }
    
    /**
     * @param string $answer1
     *
     * @return ModuleTestQuestion
     */
    public function setAnswer1(string $answer1): ModuleTestQuestion
    {
        $this->answer1 = $answer1;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getAnswer2(): string
    {
        return $this->answer2;
    }
    
    /**
     * @param string $answer2
     *
     * @return ModuleTestQuestion
     */
    public function setAnswer2(string $answer2): ModuleTestQuestion
    {
        $this->answer2 = $answer2;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getAnswer3(): string
    {
        return $this->answer3;
    }
    
    /**
     * @param string $answer3
     *
     * @return ModuleTestQuestion
     */
    public function setAnswer3(string $answer3): ModuleTestQuestion
    {
        $this->answer3 = $answer3;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getAnswer4(): string
    {
        return $this->answer4;
    }
    
    /**
     * @param string $answer4
     *
     * @return ModuleTestQuestion
     */
    public function setAnswer4(string $answer4): ModuleTestQuestion
    {
        $this->answer4 = $answer4;
        return $this;
    }
    
    /**
     * @return int
     */
    public function getRightAnswer(): int
    {
        return $this->rightAnswer;
    }
    
    /**
     * @param int $rightAnswer
     *
     * @return array|null
     */
    public function setRightAnswer(int $rightAnswer)
    {
        if ($rightAnswer < 1 || $rightAnswer > self::ANSWERS_NUMBER) {
            return ['Right answer may be greater than or equal to 1 and less than or equal to '.self::ANSWERS_NUMBER];
        }
        
        $this->rightAnswer = $rightAnswer;
        
        return null;
    }
}
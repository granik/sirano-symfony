<?php

namespace App\Domain\Backend\Interactor;


use App\Domain\Entity\Module\Backend\DTO\ModuleTestDto;
use App\Domain\Entity\Module\Backend\DTO\ModuleTestQuestionDto;
use App\Domain\Entity\Module\Backend\ModuleTestQuestionRepositoryInterface;
use App\Domain\Entity\Module\Backend\ModuleTestRepositoryInterface;
use App\Domain\Entity\Module\Module;
use App\Domain\Entity\Module\ModuleTest;
use App\Domain\Entity\Module\ModuleTestQuestion;
use App\Interactors\NonExistentEntity;

final class ModuleTestInteractor
{
    /**
     * @var ModuleTestRepositoryInterface
     */
    private $repository;
    /**
     * @var ModuleTestQuestionRepositoryInterface
     */
    private $questionRepository;
    
    /**
     * ModuleTestInteractor constructor.
     *
     * @param ModuleTestRepositoryInterface         $repository
     * @param ModuleTestQuestionRepositoryInterface $questionRepository
     */
    public function __construct(
        ModuleTestRepositoryInterface $repository,
        ModuleTestQuestionRepositoryInterface $questionRepository
    ) {
        $this->repository         = $repository;
        $this->questionRepository = $questionRepository;
    }
    
    public function list(int $page, int $perPage)
    {
        return $this->repository->list($page, $perPage);
    }
    
    public function find($id)
    {
        return $this->repository->find($id);
    }
    
    /**
     * @param Module        $module
     * @param ModuleTestDto $dto
     *
     * @return mixed
     * @throws NonExistentEntity
     */
    public function update(Module $module, ModuleTestDto $dto)
    {
        if ($module->getTest() instanceof ModuleTest) {
            $entity = $module->getTest();
            $entity->setName($dto->name);
        } else {
            $entity = (new ModuleTest())->setModule($module);
            $entity->setName($dto->name);
            
            $this->repository->store($entity);
        }

        $errors = $entity->setQuestions($this->getQuestions($dto->questions));
        
        if (!empty($errors)) {
            return $errors;
        }
        
        $this->repository->update($entity);
        
        return null;
    }
    
    /**
     * @param ModuleTestQuestionDto[] $questionDtos
     *
     * @return array|null
     */
    private function getQuestions(array $questionDtos)
    {
        error_log(var_export($questionDtos, true));
        $questions = [];
        
        foreach ($questionDtos as $questionDto) {
            $question = $this->getQuestion($questionDto->id);
            $question
                ->setQuestion($questionDto->question)
                ->setAnswer1($questionDto->answer1)
                ->setAnswer2($questionDto->answer2)
                ->setAnswer3($questionDto->answer3)
                ->setAnswer4($questionDto->answer4)
                ->setRightAnswer($questionDto->rightAnswer);
            
            $questions[] = $question;
        }
        
        return $questions;
    }
    
    /**
     * @param $id
     *
     * @return ModuleTestQuestion
     */
    private function getQuestion($id): ModuleTestQuestion
    {
        $question = null;
        
        if ($id !== null) {
            $question = $this->questionRepository->find($id);
        }
        
        if (!$question instanceof ModuleTestQuestion) {
            $question = new ModuleTestQuestion();
        }
        
        return $question;
    }
}
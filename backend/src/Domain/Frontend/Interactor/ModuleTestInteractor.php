<?php

namespace App\Domain\Frontend\Interactor;


use App\Domain\Backend\Interactor\CustomerInteractor;
use App\Domain\Entity\Module\Frontend\ModuleTestRepositoryInterface;
use App\Domain\Entity\Module\Frontend\ModuleTestResultRepositoryInterface;
use App\Domain\Entity\Module\ModuleTest;
use App\Domain\Entity\Module\ModuleTestResult;
use App\Domain\Frontend\Interactor\Exceptions\TestResultAlreadyExists;
use App\Domain\Interactor\User\User;

final class ModuleTestInteractor
{
    /**
     * @var ModuleTestRepositoryInterface
     */
    private $repository;
    /**
     * @var CustomerInteractor
     */
    private $customerInteractor;
    /**
     * @var ModuleTestResultRepositoryInterface
     */
    private $resultRepository;
    
    /**
     * ModuleTestInteractor constructor.
     *
     * @param ModuleTestRepositoryInterface       $repository
     * @param ModuleTestResultRepositoryInterface $resultRepository
     * @param CustomerInteractor                  $customerInteractor
     */
    public function __construct(
        ModuleTestRepositoryInterface $repository,
        ModuleTestResultRepositoryInterface $resultRepository,
        CustomerInteractor $customerInteractor
    ) {
        $this->repository         = $repository;
        $this->customerInteractor = $customerInteractor;
        $this->resultRepository   = $resultRepository;
    }
    
    public function find($id)
    {
        return $this->repository->find($id);
    }
    
    /**
     * @param ModuleTest $moduleTest
     * @param int        $correctAnswerNumber
     * @param User       $user
     *
     * @throws Exceptions\UserIsNotCustomer
     * @throws TestResultAlreadyExists
     */
    public function checkTest(ModuleTest $moduleTest, int $correctAnswerNumber, User $user)
    {
        if ($this->isTested($moduleTest, $user)) {
            throw new TestResultAlreadyExists();
        }
        
        $customer = $this->customerInteractor->getCustomer($user);
        $module   = $moduleTest->getModule();
        $result   = ModuleTestResult::create($module, $customer, $correctAnswerNumber);
        
        $this->resultRepository->store($result);
    }
    
    public function isTested(ModuleTest $moduleTest, User $user)
    {
        $result = $this->resultRepository->findByModuleAndCustomer(
            $moduleTest->getModule(),
            $this->customerInteractor->getCustomer($user)
        );
        
        return $result instanceof ModuleTestResult;
    }
}
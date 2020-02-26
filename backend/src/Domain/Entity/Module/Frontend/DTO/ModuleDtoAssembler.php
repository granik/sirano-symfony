<?php

namespace App\Domain\Entity\Module\Frontend\DTO;


use App\Domain\Backend\Interactor\CustomerInteractor;
use App\Domain\Entity\Article\Frontend\DTO\ArticleDtoAssembler;
use App\Domain\Entity\Module\Frontend\ModuleTestResultRepositoryInterface;
use App\Domain\Entity\Module\Module;
use App\Domain\Entity\Module\ModuleTest;
use App\Domain\Entity\Module\ModuleTestResult;
use App\Domain\Frontend\Interactor\Exceptions\UserIsNotCustomer;
use App\Domain\Frontend\Interactor\ModuleTestInteractor;
use App\DTO\DtoAssembler;
use App\Security\SymfonyUser;
use Symfony\Component\Security\Core\Security;

final class ModuleDtoAssembler extends DtoAssembler
{
    /**
     * @var string
     */
    private $fileUrlPrefix;
    /**
     * @var ArticleDtoAssembler
     */
    private $articleDtoAssembler;
    /**
     * @var ModuleTestResultRepositoryInterface
     */
    private $resultRepository;
    /**
     * @var CustomerInteractor
     */
    private $customerInteractor;
    /**
     * @var Security
     */
    private $security;
    /**
     * @var ModuleTestInteractor
     */
    private $testInteractor;
    
    /**
     * ModuleDtoAssembler constructor.
     *
     * @param string                              $fileUrlPrefix
     * @param ArticleDtoAssembler                 $articleDtoAssembler
     * @param ModuleTestResultRepositoryInterface $resultRepository
     * @param CustomerInteractor                  $customerInteractor
     * @param ModuleTestInteractor                $testInteractor
     * @param Security                            $security
     */
    public function __construct(
        string $fileUrlPrefix,
        ArticleDtoAssembler $articleDtoAssembler,
        ModuleTestResultRepositoryInterface $resultRepository,
        CustomerInteractor $customerInteractor,
        ModuleTestInteractor $testInteractor,
        Security $security
    ) {
        $this->fileUrlPrefix       = $fileUrlPrefix;
        $this->articleDtoAssembler = $articleDtoAssembler;
        $this->resultRepository    = $resultRepository;
        $this->customerInteractor  = $customerInteractor;
        $this->testInteractor      = $testInteractor;
        $this->security            = $security;
    }
    
    protected function createDto()
    {
        return new ModuleDto();
    }
    
    /**
     * @param ModuleDto $dto
     * @param Module    $entity
     *
     * @throws UserIsNotCustomer
     */
    protected function fill($dto, $entity)
    {
        $dto->id            = $entity->getId();
        $dto->name          = $entity->getName();
        $dto->directionName = $entity->getDirection()->getName();
        $dto->category      = $entity->getCategory() === null ? '' : $entity->getCategory()->getName();
        $dto->number        = $entity->getNumber();
        $dto->youtubeCode   = $entity->getYoutubeCode();
        
        if ($entity->getTest() instanceof ModuleTest) {
            $dto->hasTest  = $entity->getTest() instanceof ModuleTest;
            $dto->testName = $entity->getTest()->getName();
        }
        
        foreach ($entity->getSlides() as $slide) {
            $dto->slides[] = $this->fileUrlPrefix . '/' . $slide->getImage();
        }
        
        $dto->articles = $this->articleDtoAssembler->assembleList($entity->getArticles());
        
        $symfonyUser = $this->security->getUser();
        
        if ($symfonyUser instanceof SymfonyUser) {
            $user     = $symfonyUser->getUser();
            $customer = $this->customerInteractor->getCustomer($user);
            $result   = $this->resultRepository->findByModuleAndCustomer($entity, $customer);
            
            if ($result instanceof ModuleTestResult) {
                $dto->correctAnswers = $result->getCorrectAnswers();
                $dto->isTested       = $this->testInteractor->isTested($entity->getTest(), $user);
                $dto->isPassed       = $result->getCorrectAnswers() >= 8;
            }
        }
    }
}
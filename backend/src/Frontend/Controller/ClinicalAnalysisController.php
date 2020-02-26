<?php


namespace App\Frontend\Controller;


use App\Domain\Entity\ClinicalAnalysis\ClinicalAnalysis;
use App\Domain\Entity\ClinicalAnalysis\Frontend\DTO\ClinicalAnalysisDtoAssembler;
use App\Domain\Entity\Direction\Category;
use App\Domain\Entity\Direction\Direction;
use App\Domain\Entity\Direction\DTO\DirectionDtoAssembler;
use App\Domain\Entity\Direction\Frontend\DTO\CategoryDtoAssembler;
use App\Domain\Frontend\Interactor\CategoryInteractor;
use App\Domain\Frontend\Interactor\ClinicalAnalysisInteractor;
use App\Domain\Frontend\Interactor\DirectionInteractor;
use App\Domain\Frontend\Interactor\FilterDirectionInterface;
use App\Security\SymfonyUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

final class ClinicalAnalysisController extends AbstractController
{
    const PER_PAGE = 12;
    
    /**
     * @var ClinicalAnalysisInteractor
     */
    private $interactor;
    /**
     * @var ClinicalAnalysisDtoAssembler
     */
    private $dtoAssembler;
    /**
     * @var FilterDirectionInterface
     */
    private $filterDirection;
    /**
     * @var DirectionInteractor
     */
    private $directionInteractor;
    /**
     * @var CategoryInteractor
     */
    private $categoryInteractor;
    /**
     * @var DirectionDtoAssembler
     */
    private $directionDtoAssembler;
    /**
     * @var CategoryDtoAssembler
     */
    private $categoryDtoAssembler;
    
    /**
     * ClinicalAnalysisController constructor.
     *
     * @param ClinicalAnalysisInteractor   $interactor
     * @param DirectionInteractor          $directionInteractor
     * @param CategoryInteractor           $categoryInteractor
     * @param ClinicalAnalysisDtoAssembler $dtoAssembler
     * @param DirectionDtoAssembler        $directionDtoAssembler
     * @param CategoryDtoAssembler         $categoryDtoAssembler
     * @param FilterDirectionInterface     $filterDirection
     */
    public function __construct(
        ClinicalAnalysisInteractor $interactor,
        DirectionInteractor $directionInteractor,
        CategoryInteractor $categoryInteractor,
        ClinicalAnalysisDtoAssembler $dtoAssembler,
        DirectionDtoAssembler $directionDtoAssembler,
        CategoryDtoAssembler $categoryDtoAssembler,
        FilterDirectionInterface $filterDirection
    ) {
        $this->interactor            = $interactor;
        $this->dtoAssembler          = $dtoAssembler;
        $this->filterDirection       = $filterDirection;
        $this->directionInteractor   = $directionInteractor;
        $this->categoryInteractor    = $categoryInteractor;
        $this->directionDtoAssembler = $directionDtoAssembler;
        $this->categoryDtoAssembler  = $categoryDtoAssembler;
    }
    
    public function index(Request $request)
    {
        $page  = $request->query->get('page', 1);
        $limit = $request->query->get('limit', self::PER_PAGE);
        
        $filterDirection   = $this->filterDirection->getSelectedDirection();
        $selectedDirection = null;
        if ($filterDirection instanceof Direction) {
            $selectedDirection = $filterDirection;
        } else {
            $directionId = $request->query->get('direction');
            
            if ($directionId !== null) {
                $selectedDirection = $this->directionInteractor->find($directionId);
            }
        }
        
        $categoryId       = $request->query->get('category');
        $selectedCategory = null;
        if ($categoryId !== null) {
            $selectedCategory = $this->categoryInteractor->find($categoryId);
        }
        
        $list     = $this->interactor->list($page, $limit, $selectedDirection, $selectedCategory);
        $entities = $this->dtoAssembler->assembleList($list);
        
        $directions = $this->directionDtoAssembler->assembleList($this->directionInteractor->activeList());
        
        $selectedDirectionDto = null;
        $categories           = [];
        if ($selectedDirection instanceof Direction) {
            $selectedDirectionDto = $this->directionDtoAssembler->assemble($selectedDirection);
            $categories           = $selectedDirection->getCategories();
        }
        
        $categoryDtos = $this->categoryDtoAssembler->assembleList($categories);
        
        $selectedCategoryDto = null;
        if ($selectedCategory instanceof Category) {
            $selectedCategoryDto = $this->categoryDtoAssembler->assemble($selectedCategory);
        }
        
        $pages = ceil($list->count() / $limit);
        
        return $this->render(
            'frontend/clinicalAnalysis/list.html.twig',
            [
                'list'              => $entities,
                'page'              => $page,
                'pages'             => $pages,
                'limit'             => $limit,
                'directions'        => $directions,
                'selectedDirection' => $selectedDirectionDto,
                'category'          => $categoryDtos,
                'selectedCategory'  => $selectedCategoryDto,
            ]
        );
    }
    
    public function show($id)
    {
        $entity = $this->interactor->find($id);
        
        if (!$entity instanceof ClinicalAnalysis) {
            throw $this->createNotFoundException('Нет такого клинического разбора');
        }
        
        $dto = $this->dtoAssembler->assemble($entity);
        
        return $this->render(
            'frontend/clinicalAnalysis/show.html.twig',
            [
                'entity' => $dto,
            ]
        );
    }
    
    public function sendMessage(Request $request)
    {
        if (!$request->request->has('id') || !$request->request->has('question')) {
            return $this->json([
                'status' => 'error',
                'error'  => 'required parameter missing',
            ]);
        }
        
        $id               = $request->request->get('id');
        $clinicalAnalysis = $this->interactor->find($id);
        
        if (!$clinicalAnalysis instanceof ClinicalAnalysis) {
            return $this->json([
                'status' => 'error',
                'error'  => 'clinical analysis not found'
            ]);
        }
        
        /** @var SymfonyUser $symfonyUser */
        $symfonyUser = $this->getUser();
        
        $message = $request->request->get('question');
        
        $this->interactor->sendMessage($clinicalAnalysis, $symfonyUser->getUser(), $message);
        
        return $this->json(['status' => 'ok']);
    }
    
    public function sendCompanyMessage(Request $request)
    {
        if (!$request->request->has('id') || !$request->request->has('question')) {
            return $this->json([
                'status' => 'error',
                'error'  => 'required parameter missing',
            ]);
        }
        
        $id               = $request->request->get('id');
        $clinicalAnalysis = $this->interactor->find($id);
        
        if (!$clinicalAnalysis instanceof ClinicalAnalysis) {
            return $this->json([
                'status' => 'error',
                'error'  => 'clinical analysis not found'
            ]);
        }
        
        /** @var SymfonyUser $symfonyUser */
        $symfonyUser = $this->getUser();
        
        $message = $request->request->get('question');
        
        $this->interactor->sendCompanyMessage($clinicalAnalysis, $symfonyUser->getUser(), $message);
        
        return $this->json(['status' => 'ok']);
    }
}
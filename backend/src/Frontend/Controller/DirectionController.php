<?php

namespace App\Frontend\Controller;


use App\Domain\Backend\Interactor\DirectionInteractor;
use App\Domain\Entity\Direction\Direction;
use App\Domain\Entity\Direction\DTO\DirectionDtoAssembler;
use App\Domain\Entity\Direction\Frontend\DTO\CategoryDtoAssembler;
use App\Domain\Interactor\UserInteractor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

final class DirectionController extends AbstractController
{
    /**
     * @var DirectionInteractor
     */
    private $directionInteractor;
    /**
     * @var DirectionDtoAssembler
     */
    private $directionDtoAssembler;
    /**
     * @var UserInteractor
     */
    private $userInteractor;
    /**
     * @var CategoryDtoAssembler
     */
    private $categoryDtoAssembler;
    
    /**
     * DirectionController constructor.
     *
     * @param DirectionInteractor   $directionInteractor
     * @param DirectionDtoAssembler $directionDtoAssembler
     * @param CategoryDtoAssembler  $categoryDtoAssembler
     * @param UserInteractor        $userInteractor
     */
    public function __construct(
        DirectionInteractor $directionInteractor,
        DirectionDtoAssembler $directionDtoAssembler,
        CategoryDtoAssembler $categoryDtoAssembler,
        UserInteractor $userInteractor
    ) {
        $this->directionInteractor   = $directionInteractor;
        $this->directionDtoAssembler = $directionDtoAssembler;
        $this->userInteractor        = $userInteractor;
        $this->categoryDtoAssembler  = $categoryDtoAssembler;
    }
    
    public function list()
    {
        $directions           = $this->directionDtoAssembler->assembleList($this->directionInteractor->activeList());
        $selectedDirectionDto = null;
        $selectedDirection    = $this->userInteractor->getSelectedDirection();
        
        if ($selectedDirection instanceof Direction) {
            $selectedDirectionDto = $this->directionDtoAssembler->assemble($selectedDirection);
        }
        
        return $this->render(
            'frontend/partials/modals/directions.html.twig',
            [
                'list'              => $directions,
                'selectedDirection' => $selectedDirectionDto,
            ]
        );
    }
    
    public function mobileList()
    {
        $directions           = $this->directionDtoAssembler->assembleList($this->directionInteractor->activeList());
        $selectedDirectionDto = null;
        $selectedDirection    = $this->userInteractor->getSelectedDirection();
        
        if ($selectedDirection instanceof Direction) {
            $selectedDirectionDto = $this->directionDtoAssembler->assemble($selectedDirection);
        }
        
        return $this->render(
            'frontend/partials/mobile-directions.html.twig',
            [
                'list'              => $directions,
                'selectedDirection' => $selectedDirectionDto,
            ]
        );
    }
    
    public function select(Request $request)
    {
        if (!$request->request->has('id')) {
            return $this->json([
                'status' => 'error',
                'error'  => 'required parameter missing',
            ]);
        }
        
        $id = $request->request->get('id');
        
        $direction = $this->directionInteractor->find($id);
        
        if (!$direction instanceof Direction) {
            return $this->json([
                'status' => 'error',
                'error'  => 'direction not found'
            ]);
        }
        
        $this->userInteractor->selectDirection($direction);
        
        return $this->json(['status' => 'ok']);
    }
    
    public function drop(Request $request)
    {
        $this->userInteractor->dropDirection();
        
        return $this->json(['status' => 'ok']);
    }
    
    public function getCategories(Request $request)
    {
        $id        = $request->query->get('direction');
        $direction = $this->directionInteractor->find($id);
        
        $categories = [];
        if ($direction instanceof Direction) {
            $categories = $direction->getCategories();
        }
        
        $categoryDtos = $this->categoryDtoAssembler->assembleList($categories);
        
        return $this->render('frontend/partials/categories.html.twig', [
            'categories' => $categoryDtos,
        ]);
    }
}
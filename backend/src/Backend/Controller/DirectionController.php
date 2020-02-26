<?php

namespace App\Backend\Controller;


use App\Backend\Form\DirectionType;
use App\Domain\Backend\Interactor\DirectionInteractor;
use App\Domain\Entity\Direction\DTO\DirectionDto;
use App\Domain\Entity\Direction\DTO\DirectionDtoAssembler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class DirectionController extends AbstractController
{
    use DeleteController;
    
    const PER_PAGE = 12;
    
    /**
     * @var DirectionInteractor
     */
    private $interactor;
    /**
     * @var DirectionDtoAssembler
     */
    private $dtoAssembler;
    
    /**
     * DirectionController constructor.
     *
     * @param DirectionInteractor   $interactor
     * @param DirectionDtoAssembler $dtoAssembler
     */
    public function __construct(DirectionInteractor $interactor, DirectionDtoAssembler $dtoAssembler)
    {
        $this->interactor   = $interactor;
        $this->dtoAssembler = $dtoAssembler;
    }
    
    public function index(Request $request)
    {
        $page  = $request->query->get('page', 1);
        $limit = $request->query->get('limit', self::PER_PAGE);
        
        $list     = $this->interactor->list($page, $limit);
        $entities = $this->dtoAssembler->assembleList($list);
        
        $pages = ceil($list->count() / $limit);
        
        return $this->render(
            'backend/direction/list.html.twig',
            [
                'list'        => $entities,
                'currentPage' => $page,
                'pages'       => $pages,
                'limit'       => $limit,
            ]
        );
    }
    
    public function create(Request $request)
    {
        $directionDto = new DirectionDto();
        
        $form = $this->createForm(DirectionType::class, $directionDto);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $direction = $this->interactor->create($directionDto);
            
            $this->addFlash('notice', 'Направление создано');
            
            return $this->redirectToRoute('cms_direction_edit', ['id' => $direction->getId()]);
        }
        
        return $this->render('backend/direction/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    public function edit(Request $request, $id)
    {
        $direction    = $this->interactor->find($id);
        $directionDto = $this->dtoAssembler->assemble($direction);
        
        $form = $this->createForm(DirectionType::class, $directionDto);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->interactor->update($directionDto);
            
            $this->addFlash('notice', 'Направление сохранено');
            
            return $this->redirectToRoute('cms_direction_edit', ['id' => $direction->getId()]);
        }
        
        return $this->render('backend/direction/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
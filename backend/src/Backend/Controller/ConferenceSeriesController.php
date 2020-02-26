<?php

namespace App\Backend\Controller;


use App\Backend\Form\ConferenceSeriesType;
use App\Domain\Backend\Interactor\ConferenceSeriesInteractor;
use App\Domain\Entity\Conference\Backend\DTO\ConferenceSeriesDtoAssembler;
use App\Domain\Entity\Conference\Backend\DTO\ConferenceSeriesDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ConferenceSeriesController extends AbstractController
{
    use DeleteController;
    
    const PER_PAGE = 12;
    
    /**
     * @var ConferenceSeriesInteractor
     */
    private $interactor;
    /**
     * @var ConferenceSeriesDtoAssembler
     */
    private $dtoAssembler;
    
    /**
     * ConferenceSeriesController constructor.
     *
     * @param ConferenceSeriesInteractor   $interactor
     * @param ConferenceSeriesDtoAssembler $dtoAssembler
     */
    public function __construct(
        ConferenceSeriesInteractor $interactor,
        ConferenceSeriesDtoAssembler $dtoAssembler
    ) {
        $this->interactor   = $interactor;
        $this->dtoAssembler = $dtoAssembler;
    }
    
    public function index(Request $request)
    {
        $page  = $request->query->get('page', 1);
        $limit = $request->query->get('limit', self::PER_PAGE);
        
        $list             = $this->interactor->list($page, $limit);
        $conferenceSeries = $this->dtoAssembler->assembleList($list);
        
        $pages = ceil($list->count() / $limit);
        
        return $this->render(
            'backend/conferenceSeries/list.html.twig',
            [
                'list'        => $conferenceSeries,
                'currentPage' => $page,
                'pages'       => $pages,
                'limit'       => $limit,
            ]
        );
    }
    
    public function create(Request $request)
    {
        $conferenceSeriesDto = new ConferenceSeriesDto();
        
        $form = $this->createForm(ConferenceSeriesType::class, $conferenceSeriesDto);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $conferenceSeries = $this->interactor->create($conferenceSeriesDto);
            
            $this->addFlash('notice', 'Цикл конференций создан');
            
            return $this->redirectToRoute('cms_conference_series_edit', ['id' => $conferenceSeries->getId()]);
        }
        
        return $this->render('backend/conferenceSeries/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    public function edit(Request $request, $id)
    {
        $entity = $this->interactor->find($id);
        $dto    = $this->dtoAssembler->assemble($entity);
        
        $form = $this->createForm(ConferenceSeriesType::class, $dto);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->interactor->update($dto);
            
            $this->addFlash('notice', 'Цикл конференций сохранён');
        }
        
        return $this->render('backend/conferenceSeries/edit.html.twig', [
            'form' => $form->createView(),
            'id'   => $id,
        ]);
    }
}
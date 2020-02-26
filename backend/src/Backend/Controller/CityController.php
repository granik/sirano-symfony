<?php

namespace App\Backend\Controller;


use App\Backend\Form\CityType;
use App\Domain\Interactor\CityDto;
use App\Domain\Interactor\CityInteractor;
use App\DTO\CityDtoAssembler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class CityController extends AbstractController
{
    use DeleteController;
    
    const PER_PAGE = 12;
    
    /**
     * @var CityInteractor
     */
    private $interactor;
    /**
     * @var CityDtoAssembler
     */
    private $dtoAssembler;
    
    /**
     * CityController constructor.
     *
     * @param CityInteractor   $interactor
     * @param CityDtoAssembler $dtoAssembler
     */
    public function __construct(CityInteractor $interactor, CityDtoAssembler $dtoAssembler)
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
            'backend/city/list.html.twig',
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
        $cityDto = new CityDto();
        
        $form = $this->createForm(CityType::class, $cityDto);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $city = $this->interactor->create($cityDto);
            
            $this->addFlash('notice', 'Город создан');
            
            return $this->redirectToRoute('cms_city_edit', ['id' => $city->getId()]);
        }
        
        return $this->render('backend/city/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    public function edit(Request $request, $id)
    {
        $city = $this->interactor->find($id);
        
        $cityDto = $this->dtoAssembler->assemble($city);
        
        $form = $this->createForm(CityType::class, $cityDto);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->interactor->update($cityDto);
            
            $this->addFlash('notice', 'Город сохранён');
        }
        
        return $this->render('backend/city/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
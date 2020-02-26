<?php


namespace App\Backend\Controller;


use App\Backend\Form\SpecialtyType;
use App\Domain\Backend\Interactor\AdditionalSpecialtyInteractor;
use App\Domain\Entity\Specialty\Backend\DTO\SpecialtyDto;
use App\Domain\Entity\Specialty\Backend\DTO\SpecialtyDtoAssembler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

final class AdditionalSpecialtyController extends AbstractController
{
    use DeleteController;
    
    const PER_PAGE = 12;
    
    /**
     * @var AdditionalSpecialtyInteractor
     */
    private $interactor;
    /**
     * @var SpecialtyDtoAssembler
     */
    private $dtoAssembler;
    
    /**
     * AdditionalSpecialtyController constructor.
     *
     * @param AdditionalSpecialtyInteractor   $interactor
     * @param SpecialtyDtoAssembler $dtoAssembler
     */
    public function __construct(AdditionalSpecialtyInteractor $interactor, SpecialtyDtoAssembler $dtoAssembler)
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
            'backend/additionalSpecialty/list.html.twig',
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
        $dto = new SpecialtyDto();
        
        $form = $this->createForm(SpecialtyType::class, $dto);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entity = $this->interactor->create($dto);
            
            $this->addFlash('notice', 'Специальность создана');
            
            return $this->redirectToRoute('cms_additional_specialty_edit', ['id' => $entity->getId()]);
        }
        
        return $this->render('backend/additionalSpecialty/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    public function edit(Request $request, $id)
    {
        $entity = $this->interactor->find($id);
        $dto    = $this->dtoAssembler->assemble($entity);
        
        $form = $this->createForm(SpecialtyType::class, $dto, ['validation_groups' => ['update']]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->interactor->update($dto);
            
            $this->addFlash('notice', 'Специальность сохранена');
            
            return $this->redirectToRoute('cms_additional_specialty_edit', ['id' => $entity->getId()]);
        }
        
        return $this->render('backend/additionalSpecialty/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
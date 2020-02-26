<?php


namespace App\Backend\Controller;


use App\Backend\Form\DocumentType;
use App\Domain\Backend\Interactor\DocumentInteractor;
use App\Domain\Entity\Document\Backend\DTO\DocumentDto;
use App\Domain\Entity\Document\Backend\DTO\DocumentDtoAssembler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

final class DocumentController extends AbstractController
{
    use DeleteController;
    
    const PER_PAGE = 12;
    
    /**
     * @var DocumentInteractor
     */
    private $interactor;
    /**
     * @var DocumentDtoAssembler
     */
    private $dtoAssembler;
    
    /**
     * DocumentController constructor.
     *
     * @param DocumentInteractor   $interactor
     * @param DocumentDtoAssembler $dtoAssembler
     */
    public function __construct(DocumentInteractor $interactor, DocumentDtoAssembler $dtoAssembler)
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
            'backend/document/list.html.twig',
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
        $dto = new DocumentDto();
        
        $form = $this->createForm(DocumentType::class, $dto);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entity = $this->interactor->create($dto);
            
            $this->addFlash('notice', 'Документ создан');
            
            return $this->redirectToRoute('cms_document_edit', ['id' => $entity->getId()]);
        }
        
        return $this->render('backend/document/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    public function edit(Request $request, $id)
    {
        $entity = $this->interactor->find($id);
        $dto    = $this->dtoAssembler->assemble($entity);
        
        $form = $this->createForm(DocumentType::class, $dto, ['validation_groups' => ['update']]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->interactor->update($dto);
            
            $this->addFlash('notice', 'Документ сохранён');
            
            return $this->redirectToRoute('cms_document_edit', ['id' => $entity->getId()]);
        }
        
        return $this->render('backend/document/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
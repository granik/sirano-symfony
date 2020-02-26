<?php


namespace App\Backend\Controller;


use App\Backend\Form\BannerType;
use App\Domain\Backend\Interactor\BannerInteractor;
use App\Domain\Entity\Banner\Backend\DTO\BannerDto;
use App\Domain\Entity\Banner\Backend\DTO\BannerDtoAssembler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

final class BannerController extends AbstractController
{
    use DeleteController;
    
    const PER_PAGE = 12;
    
    /**
     * @var BannerInteractor
     */
    private $interactor;
    /**
     * @var BannerDtoAssembler
     */
    private $dtoAssembler;
    
    /**
     * BannerController constructor.
     *
     * @param BannerInteractor   $interactor
     * @param BannerDtoAssembler $dtoAssembler
     */
    public function __construct(BannerInteractor $interactor, BannerDtoAssembler $dtoAssembler)
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
            'backend/banner/list.html.twig',
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
        $dto = new BannerDto();
        
        $form = $this->createForm(BannerType::class, $dto);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entity = $this->interactor->create($dto);
            
            $this->addFlash('notice', 'Баннер создан');
            
            return $this->redirectToRoute('cms_banner_edit', ['id' => $entity->getId()]);
        }
        
        return $this->render('backend/banner/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    public function edit(Request $request, $id)
    {
        $entity = $this->interactor->find($id);
        $dto    = $this->dtoAssembler->assemble($entity);
        
        $form = $this->createForm(BannerType::class, $dto, ['validation_groups' => ['update']]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->interactor->update($dto);
            
            $this->addFlash('notice', 'Баннер сохранён');
            
            return $this->redirectToRoute('cms_banner_edit', ['id' => $entity->getId()]);
        }
        
        return $this->render('backend/banner/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
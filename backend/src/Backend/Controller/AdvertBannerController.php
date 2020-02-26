<?php


namespace App\Backend\Controller;


use App\Backend\Form\AdvertBannerType;
use App\Domain\Backend\Interactor\AdvertBannerInteractor;
use App\Domain\Entity\AdvertBanner\Backend\DTO\AdvertBannerDto;
use App\Domain\Entity\AdvertBanner\Backend\DTO\AdvertBannerDtoAssembler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

final class AdvertBannerController extends AbstractController
{
    use DeleteController;
    
    const PER_PAGE = 12;
    /**
     * @var AdvertBannerInteractor
     */
    private $interactor;
    /**
     * @var AdvertBannerDtoAssembler
     */
    private $dtoAssembler;
    
    /**
     * AdvertBannerController constructor.
     *
     * @param AdvertBannerInteractor   $interactor
     * @param AdvertBannerDtoAssembler $dtoAssembler
     */
    public function __construct(AdvertBannerInteractor $interactor, AdvertBannerDtoAssembler $dtoAssembler)
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
            'backend/advertBanner/list.html.twig',
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
        $dto = new AdvertBannerDto();
        
        $form = $this->get('form.factory')->createNamed('a_banner', AdvertBannerType::class, $dto);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entity = $this->interactor->create($dto);
            
            $this->addFlash('notice', 'Баннер создан');
            
            return $this->redirectToRoute('cms_advert_banner_edit', ['id' => $entity->getId()]);
        }
        
        return $this->render('backend/advertBanner/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    public function edit(Request $request, $id)
    {
        $entity = $this->interactor->find($id);
        $dto    = $this->dtoAssembler->assemble($entity);
        
        $form = $this->get('form.factory')->createNamed('a_banner', AdvertBannerType::class, $dto, ['validation_groups' => ['update']]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->interactor->update($dto);
            
            $this->addFlash('notice', 'Баннер сохранён');
            
            return $this->redirectToRoute('cms_advert_banner_edit', ['id' => $entity->getId()]);
        }
        
        return $this->render('backend/advertBanner/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
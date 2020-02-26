<?php

namespace App\Backend\Controller;


use App\Backend\Form\ModuleType;
use App\Domain\Backend\Interactor\ModuleInteractor;
use App\Domain\Entity\Module\Backend\DTO\ModuleDto;
use App\Domain\Entity\Module\Backend\DTO\ModuleDtoAssembler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ModuleController extends AbstractController
{
    use DeleteController;
    
    const PER_PAGE = 12;
    
    /**
     * @var ModuleInteractor
     */
    private $interactor;
    /**
     * @var ModuleDtoAssembler
     */
    private $dtoAssembler;
    
    /**
     * ModuleController constructor.
     *
     * @param ModuleInteractor   $interactor
     * @param ModuleDtoAssembler $dtoAssembler
     */
    public function __construct(ModuleInteractor $interactor, ModuleDtoAssembler $dtoAssembler)
    {
        $this->interactor   = $interactor;
        $this->dtoAssembler = $dtoAssembler;
    }
    
    public function index(Request $request)
    {
        $page  = $request->query->get('page', 1);
        $limit = $request->query->get('limit', self::PER_PAGE);
        $criteria = [];
        foreach (
            [
                'number',
                'name',
            ] as $key) {
        
            $value = null;
        
            if ($request->query->has($key)) {
                $value = $request->query->get($key);
            
                if ($value === '') {
                    $value = null;
                } elseif (is_numeric($value) && (int)$value == $value) {
                    $value = (int)$value;
                }
            }
        
            $$key           = $value;
            $criteria[$key] = $value;
        }
    
        $list     = $this->interactor->list($page, $limit, $criteria);
        $entities = $this->dtoAssembler->assembleList($list);
        
        $pages = ceil($list->count() / $limit);
        
        return $this->render(
            'backend/module/list.html.twig',
            [
                'list'        => $entities,
                'currentPage' => $page,
                'pages'       => $pages,
                'limit'       => $limit,
                'number'      => $number,
                'name'        => $name,
            ]
        );
    }
    
    public function create(Request $request)
    {
        $dto = new ModuleDto();
        
        $form = $this->createForm(ModuleType::class, $dto);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entity = $this->interactor->create($dto);
            
            $this->addFlash('notice', 'Модуль создан');
            
            return $this->redirectToRoute('cms_module_edit', ['id' => $entity->getId()]);
        }
        
        return $this->render('backend/module/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    public function edit(Request $request, $id)
    {
        $entity = $this->interactor->find($id);
        $dto    = $this->dtoAssembler->assemble($entity);
        
        $form = $this->createForm(ModuleType::class, $dto, ['validation_groups' => ['update']]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->interactor->update($dto);
            
            $this->addFlash('notice', 'Модуль сохранён');
            
            return $this->redirectToRoute('cms_module_edit', ['id' => $entity->getId()]);
        }
        
        return $this->render('backend/module/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    public function getCategory(Request $request)
    {
        $dto            = new ModuleDto();
        $dto->direction = $request->query->get('direction');
        $form           = $this->createForm(ModuleType::class, $dto, ['validation_groups' => ['update']]);
        
        if (!$form->has('category')) {
            return new Response(null, 204);
        }
        
        return $this->render('backend/module/category.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
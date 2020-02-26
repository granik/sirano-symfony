<?php

namespace App\Backend\Controller;


use App\Backend\Form\ArticleType;
use App\Backend\Form\ModuleType;
use App\Domain\Backend\Interactor\ArticleInteractor;
use App\Domain\Entity\Article\Backend\DTO\ArticleDto;
use App\Domain\Entity\Article\Backend\DTO\ArticleDtoAssembler;
use App\Domain\Entity\Module\Backend\DTO\ModuleDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ArticleController extends AbstractController
{
    use DeleteController;
    
    const PER_PAGE = 12;

    /**
     * @var ArticleInteractor
     */
    private $interactor;
    /**
     * @var ArticleDtoAssembler
     */
    private $dtoAssembler;
    
    /**
     * ArticleController constructor.
     *
     * @param ArticleInteractor   $interactor
     * @param ArticleDtoAssembler $dtoAssembler
     */
    public function __construct(ArticleInteractor $interactor, ArticleDtoAssembler $dtoAssembler)
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
                'author',
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
            'backend/article/list.html.twig',
            [
                'list'        => $entities,
                'currentPage' => $page,
                'pages'       => $pages,
                'limit'       => $limit,
                'author'      => $author,
                'name'        => $name,
            ]
        );
    }
    
    public function create(Request $request)
    {
        $dto = new ArticleDto();
        
        $form = $this->createForm(ArticleType::class, $dto);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entity = $this->interactor->create($dto);
    
            $this->addFlash('notice', 'Статья создана');
    
            return $this->redirectToRoute('cms_article_edit', ['id' => $entity->getId()]);
        }
        
        return $this->render('backend/article/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    public function edit(Request $request, $id)
    {
        $entity = $this->interactor->find($id);
        $dto    = $this->dtoAssembler->assemble($entity);
        
        $form = $this->createForm(ArticleType::class, $dto, ['validation_groups' => ['update']]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->interactor->update($dto);
    
            $this->addFlash('notice', 'Статья сохранена');
    
            return $this->redirectToRoute('cms_article_edit', ['id' => $entity->getId()]);
        }
        
        return $this->render('backend/article/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    public function getCategory(Request $request)
    {
        $dto            = new ArticleDto();
        $dto->direction = $request->query->get('direction');
        $form           = $this->createForm(ArticleType::class, $dto, ['validation_groups' => ['update']]);
        
        if (!$form->has('category')) {
            return new Response(null, 204);
        }
        
        return $this->render('backend/article/category.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
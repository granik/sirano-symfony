<?php


namespace App\Frontend\Controller;


use App\Domain\Entity\News\Frontend\DTO\NewsDtoAssembler;
use App\Domain\Entity\News\News;
use App\Domain\Frontend\Interactor\NewsInteractor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

final class NewsController extends AbstractController
{
    const PER_PAGE = 12;
    /**
     * @var NewsInteractor
     */
    private $interactor;
    /**
     * @var NewsDtoAssembler
     */
    private $dtoAssembler;
    
    /**
     * NewsController constructor.
     *
     * @param NewsInteractor   $interactor
     * @param NewsDtoAssembler $dtoAssembler
     */
    public function __construct(NewsInteractor $interactor, NewsDtoAssembler $dtoAssembler)
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
            'frontend/news/list.html.twig',
            [
                'list'  => $entities,
                'page'  => $page,
                'pages' => $pages,
                'limit' => $limit,
            ]
        );
    }
    
    public function show($id)
    {
        $entity = $this->interactor->find($id);
        
        if (!$entity instanceof News) {
            throw $this->createNotFoundException('Нет такой новости');
        }
        
        $dto = $this->dtoAssembler->assemble($entity);
        
        $randomNews         = $this->interactor->randomNews($entity);
        $recommendedNewsDto = null;
        if ($randomNews instanceof News) {
            $recommendedNewsDto = $this->dtoAssembler->assemble($randomNews);
        }
        
        return $this->render(
            'frontend/news/show.html.twig',
            [
                'entity'    => $dto,
                'recommend' => $recommendedNewsDto,
            ]
        );
    }
}
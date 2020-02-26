<?php


namespace App\Backend\Controller;


use App\Backend\Form\NewsType;
use App\Domain\Backend\Interactor\NewsInteractor;
use App\Domain\Entity\News\Backend\DTO\NewsDto;
use App\Domain\Entity\News\Backend\DTO\NewsDtoAssembler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

final class NewsController extends AbstractController
{
    use DeleteController;
    
    const PER_PAGE         = 12;
    const UPLOAD_DIRECTORY = 'news/images';
    /**
     * @var NewsInteractor
     */
    private $interactor;
    /**
     * @var NewsDtoAssembler
     */
    private $dtoAssembler;
    /**
     * @var string
     */
    private $targetDirectory;
    /**
     * @var string
     */
    private $fileUrlPrefix;
    
    /**
     * NewsController constructor.
     *
     * @param NewsInteractor   $interactor
     * @param NewsDtoAssembler $dtoAssembler
     * @param string           $targetDirectory
     * @param string           $fileUrlPrefix
     */
    public function __construct(
        NewsInteractor $interactor,
        NewsDtoAssembler $dtoAssembler,
        string $targetDirectory,
        string $fileUrlPrefix
    ) {
        $this->interactor      = $interactor;
        $this->dtoAssembler    = $dtoAssembler;
        $this->targetDirectory = $targetDirectory;
        $this->fileUrlPrefix   = $fileUrlPrefix;
    }
    
    
    public function index(Request $request)
    {
        $page  = $request->query->get('page', 1);
        $limit = $request->query->get('limit', self::PER_PAGE);
        $criteria = [];
        foreach (
            [
                'createdAt',
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
            'backend/news/list.html.twig',
            [
                'list'        => $entities,
                'currentPage' => $page,
                'pages'       => $pages,
                'limit'       => $limit,
                'createdAt'   => $createdAt,
                'name'        => $name,
            ]
        );
    }
    
    public function create(Request $request)
    {
        $dto = new NewsDto();
        
        $form = $this->createForm(NewsType::class, $dto);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entity = $this->interactor->create($dto);
            
            $this->addFlash('notice', 'Новость создана');
            
            return $this->redirectToRoute('cms_news_edit', ['id' => $entity->getId()]);
        }
        
        return $this->render('backend/news/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    public function edit(Request $request, $id)
    {
        $entity = $this->interactor->find($id);
        $dto    = $this->dtoAssembler->assemble($entity);
        
        $form = $this->createForm(NewsType::class, $dto, ['validation_groups' => ['update']]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->interactor->update($dto);
            
            $this->addFlash('notice', 'Новость сохранена');
            
            return $this->redirectToRoute('cms_news_edit', ['id' => $entity->getId()]);
        }
        
        return $this->render('backend/news/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    public function fileUpload(Request $request)
    {
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $request->files->get('file');
        $fileName     = $uploadedFile->getClientOriginalName();
        
        $uploadedFile->move($this->targetDirectory . '/' . self::UPLOAD_DIRECTORY, $fileName);
        
        return $this->json(['location' => $this->fileUrlPrefix . '/' . self::UPLOAD_DIRECTORY . '/' . $fileName]);
    }
}
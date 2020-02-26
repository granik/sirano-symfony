<?php

namespace App\Backend\Controller;


use App\Backend\Form\ConferenceType;
use App\Domain\Backend\Interactor\ConferenceInteractor;
use App\Domain\Entity\City;
use App\Domain\Entity\Conference\Backend\DTO\ConferenceDtoAssembler;
use App\Domain\Entity\Conference\DTO\ConferenceDto;
use App\Domain\Entity\Conference\DTO\ConferenceSubscriberDtoAssembler;
use App\Domain\Interactor\CityInteractor;
use App\Service\ExcelReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ConferenceController extends AbstractController
{
    use DeleteController;
    
    const PER_PAGE = 12;
    
    /**
     * @var ConferenceInteractor
     */
    private $interactor;
    /**
     * @var ConferenceDtoAssembler
     */
    private $conferenceDtoAssembler;
    /**
     * @var ConferenceSubscriberDtoAssembler
     */
    private $subscriberDtoAssembler;
    /**
     * @var ExcelReader
     */
    private $excelReader;
    /**
     * @var CityInteractor
     */
    private $cityInteractor;
    
    /**
     * ConferenceController constructor.
     *
     * @param ConferenceInteractor             $interactor
     * @param CityInteractor                   $cityInteractor
     * @param ConferenceDtoAssembler           $conferenceDtoAssembler
     * @param ConferenceSubscriberDtoAssembler $subscriberDtoAssembler
     * @param ExcelReader                      $excelReader
     */
    public function __construct(
        ConferenceInteractor $interactor,
        CityInteractor $cityInteractor,
        ConferenceDtoAssembler $conferenceDtoAssembler,
        ConferenceSubscriberDtoAssembler $subscriberDtoAssembler,
        ExcelReader $excelReader
    ) {
        $this->interactor             = $interactor;
        $this->conferenceDtoAssembler = $conferenceDtoAssembler;
        $this->subscriberDtoAssembler = $subscriberDtoAssembler;
        $this->excelReader            = $excelReader;
        $this->cityInteractor         = $cityInteractor;
    }
    
    public function index(Request $request)
    {
        $page  = $request->query->get('page', 1);
        $limit = $request->query->get('limit', self::PER_PAGE);
        $criteria = [];
        foreach (
            [
                'startDate',
                'city',
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
        $conferences = $this->conferenceDtoAssembler->assembleList($list);
        
        $pages = ceil($list->count() / $limit);
    
        $cities = [];
        /** @var City $city */
        foreach ($this->cityInteractor->listAll() as $cityItem) {
            $cities[$cityItem->getId()] = $cityItem->getName();
        }
    
        return $this->render(
            'backend/conference/list.html.twig',
            [
                'list'        => $conferences,
                'currentPage' => $page,
                'pages'       => $pages,
                'limit'       => $limit,
                'startDate'   => $startDate,
                'city'        => $city,
                'name'        => $name,
                'cities'      => $cities,
            ]
        );
    }
    
    public function create(Request $request)
    {
        $conferenceDto = new ConferenceDto();
        
        $form = $this->createForm(ConferenceType::class, $conferenceDto);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $conference = $this->interactor->create($conferenceDto);
            
            $this->addFlash('notice', 'Конференция создана');
            
            return $this->redirectToRoute('cms_conference_edit', ['id' => $conference->getId()]);
        }
        
        return $this->render('backend/conference/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    public function edit(Request $request, $id)
    {
        $entity = $this->interactor->find($id);
        $dto    = $this->conferenceDtoAssembler->assemble($entity);
        
        $subscribers = $this->subscriberDtoAssembler->assembleList($entity->getSubscribers());
        
        $form = $this->createForm(ConferenceType::class, $dto);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->interactor->update($dto);
            
            $this->addFlash('notice', 'Конференция сохранена');
        }
        
        return $this->render('backend/conference/edit.html.twig', [
            'form'        => $form->createView(),
            'subscribers' => $subscribers,
            'id'          => $id,
        ]);
    }
    
    public function updateSubscribers(Request $request, $id)
    {
        $this->interactor->updateSubscribersVisits($id, $request->request->get('subscribers'));
        
        return $this->redirectToRoute('cms_conference_edit', ['id' => $id]);
    }
    
    public function saveSubscibers(Request $request, $id)
    {
        $entity = $this->interactor->find($id);
        $excel  = $this->interactor->saveSubscribers($id);
        
        $response = new StreamedResponse(function () use ($excel) {
            $excel->save('php://output');
        });
        
        $response->headers->set('Content-Type', 'application/vnd.ms-excel; charset=utf-8');
        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            "report_conference_{$entity->getStartDateTime()->format('Ymd')}.xlsx"
        );
        $response->headers->set('Content-Disposition', $disposition);
        
        return $response;
    }
    
    public function loadSubscibers(Request $request, $id)
    {
        if ($request->files->has('list') && $request->files->get('list') !== null) {
            $entity = $this->interactor->find($id);
            
            /** @var UploadedFile $excelFile */
            $excelFile = $request->files->get('list');
            $list      = $this->excelReader->readConferenceSubscribersFromFile($excelFile->getPathname());
            
            $this->interactor->loadSubscribers($entity, $list);
        }
        
        return $this->redirectToRoute('cms_conference_edit', ['id' => $id]);
    }
}
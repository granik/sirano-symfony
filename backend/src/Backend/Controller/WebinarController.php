<?php

namespace App\Backend\Controller;


use App\Backend\Form\WebinarReportType;
use App\Backend\Form\WebinarType;
use App\Webinar\Backend\DTO\WebinarDtoAssembler;
use App\Webinar\Backend\WebinarInteractor;
use App\Webinar\DTO\WebinarDto;
use App\Webinar\DTO\WebinarReportDtoAssembler;
use App\Webinar\DTO\WebinarSubscriberDtoAssembler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class WebinarController extends AbstractController
{
    use DeleteController;
    
    const PER_PAGE = 12;
    
    private $interactor;
    private $dtoAssembler;
    /**
     * @var WebinarReportDtoAssembler
     */
    private $webinarReportDtoAssembler;
    /**
     * @var WebinarSubscriberDtoAssembler
     */
    private $subscriberDtoAssembler;
    
    /**
     * WebinarController constructor.
     *
     * @param WebinarInteractor             $interactor
     * @param WebinarDtoAssembler           $dtoAssembler
     * @param WebinarReportDtoAssembler     $webinarReportDtoAssembler
     * @param WebinarSubscriberDtoAssembler $subscriberDtoAssembler
     */
    public function __construct(
        WebinarInteractor $interactor,
        WebinarDtoAssembler $dtoAssembler,
        WebinarReportDtoAssembler $webinarReportDtoAssembler,
        WebinarSubscriberDtoAssembler $subscriberDtoAssembler
    ) {
        $this->interactor                = $interactor;
        $this->dtoAssembler              = $dtoAssembler;
        $this->webinarReportDtoAssembler = $webinarReportDtoAssembler;
        $this->subscriberDtoAssembler    = $subscriberDtoAssembler;
    }
    
    public function index(Request $request)
    {
        $page  = $request->query->get('page', 1);
        $limit = $request->query->get('limit', self::PER_PAGE);
        $criteria = [];
        foreach (
            [
                'startDate',
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
        $webinars = $this->dtoAssembler->assembleList($list);
        
        $pages = ceil($list->count() / $limit);
        
        return $this->render(
            'backend/webinar/list.html.twig',
            [
                'list'        => $webinars,
                'currentPage' => $page,
                'pages'       => $pages,
                'limit'       => $limit,
                'startDate'   => $startDate,
                'name'        => $name,
            ]
        );
    }
    
    public function create(Request $request)
    {
        $webinarDto = new WebinarDto();
        
        $form = $this->createForm(WebinarType::class, $webinarDto);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $webinar = $this->interactor->create($webinarDto);
            
            $this->addFlash('notice', 'Вебинар создан');
            
            return $this->redirectToRoute('cms_webinar_edit', ['id' => $webinar->getId()]);
        }
        
        return $this->render('backend/webinar/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    public function edit(Request $request, $id)
    {
        $entity = $this->interactor->find($id);
        $dto    = $this->dtoAssembler->assemble($entity);
        
        $subscribers = $this->subscriberDtoAssembler->assembleList($entity->getSubscribers());
        
        $form = $this->createForm(WebinarType::class, $dto);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->interactor->update($dto);
            
            $this->addFlash('notice', 'Вебинар сохранён');
            
            return $this->redirectToRoute('cms_webinar_edit', ['id' => $entity->getId()]);
        }
        
        return $this->render('backend/webinar/edit.html.twig', [
            'form'        => $form->createView(),
            'id'          => $id,
            'subscribers' => $subscribers,
        ]);
    }
    
    public function report(Request $request, $id)
    {
        $report = $this->interactor->findReport($id);
        
        $reportDto = $this->webinarReportDtoAssembler->assemble($report);
        
        $form = $this->createForm(WebinarReportType::class, $reportDto);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->interactor->updateReport($report->getWebinar(), $reportDto);
            
            $this->addFlash('notice', 'Отчёт по вебинару сохранён');
            
            return $this->redirectToRoute('cms_webinar_report', ['id' => $id]);
        }
        
        return $this->render('backend/webinar/report.html.twig', [
            'form' => $form->createView(),
            'id'   => $id,
        ]);
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
            "report_webinars_{$entity->getStartDatetime()->format('Ymd')}.xlsx"
        );
        $response->headers->set('Content-Disposition', $disposition);
        
        return $response;
    }
}
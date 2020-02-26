<?php

namespace App\Frontend\Controller;


use App\Domain\Backend\Interactor\CustomerInteractor;
use App\Domain\Frontend\Interactor\FilterDirectionInterface;
use App\Security\SymfonyUser;
use App\Webinar\DTO\WebinarDto;
use App\Webinar\DTO\WebinarDtoAssembler;
use App\Webinar\DTO\WebinarReportFromWebinarDtoAssembler;
use App\Webinar\Frontend\Interactor\WebinarInteractor;
use App\Webinar\Webinar;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class WebinarController extends AbstractController
{
    const PER_PAGE               = 12;
    const DEFAULT_PERIOD         = 'year';
    const DEFAULT_ARCHIVE_PERIOD = 'all';
    
    private $interactor;
    /**
     * @var WebinarDtoAssembler
     */
    private $dtoAssembler;
    /**
     * @var CustomerInteractor
     */
    private $customerInteractor;
    /**
     * @var WebinarReportFromWebinarDtoAssembler
     */
    private $reportFromWebinarDtoAssembler;
    /**
     * @var FilterDirectionInterface
     */
    private $filterDirection;
    
    /**
     * WebinarController constructor.
     *
     * @param WebinarInteractor                    $interactor
     * @param WebinarDtoAssembler                  $dtoAssembler
     * @param WebinarReportFromWebinarDtoAssembler $reportFromWebinarDtoAssembler
     * @param CustomerInteractor                   $customerInteractor
     * @param FilterDirectionInterface             $filterDirection
     */
    public function __construct(
        WebinarInteractor $interactor,
        WebinarDtoAssembler $dtoAssembler,
        WebinarReportFromWebinarDtoAssembler $reportFromWebinarDtoAssembler,
        CustomerInteractor $customerInteractor,
        FilterDirectionInterface $filterDirection
    ) {
        $this->interactor                    = $interactor;
        $this->dtoAssembler                  = $dtoAssembler;
        $this->customerInteractor            = $customerInteractor;
        $this->reportFromWebinarDtoAssembler = $reportFromWebinarDtoAssembler;
        $this->filterDirection               = $filterDirection;
    }
    
    public function show($id)
    {
        $webinar = $this->interactor->find($id);
        
        if (!$webinar instanceof Webinar) {
            throw $this->createNotFoundException('Нет такого вебинара');
        }
        
        if ($webinar->isArchive()) {
            $reportDto = $this->reportFromWebinarDtoAssembler->assemble($webinar);
            
            $randomWebinar = $this->interactor->randomArchive($webinar);
            if ($randomWebinar instanceof Webinar) {
                $randomReportDto = $this->reportFromWebinarDtoAssembler->assemble($randomWebinar);
            } else {
                $randomReportDto = null;
            }
            
            return $this->render(
                'frontend/webinar/show_archive.html.twig',
                [
                    'report'    => $reportDto,
                    'recommend' => $randomReportDto,
                ]
            );
        }
        
        $webinarDto = $this->dtoAssembler->assemble($webinar);
        
        return $this->render('frontend/webinar/show.html.twig', ['webinar' => $webinarDto]);
    }
    
    public function list(Request $request)
    {
        $page   = $request->query->get('page', 1);
        $limit  = $request->query->get('limit', self::PER_PAGE);
        $period = $request->query->get('period', self::DEFAULT_PERIOD);
        
        $direction = $this->filterDirection->getSelectedDirection();
        
        $list     = $this->interactor->list($page, $limit, $direction, $period);
        $webinars = $this->dtoAssembler->assembleList($list);
        
        $pages = ceil($list->count() / $limit);
        
        return $this->render(
            'frontend/webinar/list.html.twig',
            [
                'list'   => $webinars,
                'page'   => $page,
                'pages'  => $pages,
                'limit'  => $limit,
                'period' => $period,
            ]
        );
    }
    
    public function archive(Request $request)
    {
        $page   = $request->query->get('page', 1);
        $limit  = $request->query->get('limit', self::PER_PAGE);
        $period = $request->query->get('period', self::DEFAULT_ARCHIVE_PERIOD);
        
        $direction = $this->filterDirection->getSelectedDirection();
        
        $list     = $this->interactor->archive($page, $limit, $direction, $period);
        $webinars = $this->reportFromWebinarDtoAssembler->assembleList($list);
        
        $pages = ceil($list->count() / self::PER_PAGE);
        
        return $this->render(
            'frontend/webinar/archive.html.twig',
            [
                'webinars' => $webinars,
                'page'     => $page,
                'pages'    => $pages,
                'limit'    => $limit,
                'period'   => $period,
            ]
        );
    }
    
    public function confirm(Request $request)
    {
        if (!$request->request->has('id')) {
            return $this->json([
                'status' => 'error',
                'error'  => 'required parameter missing',
            ]);
        }
        
        $id = $request->request->get('id');
        
        $webinar = $this->interactor->find($id);
        
        if (!$webinar instanceof Webinar) {
            return $this->json([
                'status' => 'error',
                'error'  => 'webinar not found'
            ]);
        }
        
        /** @var SymfonyUser $symfonyUser */
        $symfonyUser = $this->getUser();
        
        $this->interactor->confirmView($webinar, $symfonyUser->getUser());
        
        return $this->json(['status' => 'ok']);
    }
    
    public function getTimes(Request $request)
    {
        if (!$request->query->has('id')) {
            return $this->json(['status' => 'error']);
        }
        
        $id = $request->query->get('id');
        
        $webinar = $this->interactor->find($id);
        
        if (!$webinar instanceof Webinar) {
            return $this->json(['status' => 'error']);
        }
        
        /** @var WebinarDto $webinarDto */
        $webinarDto = $this->dtoAssembler->assemble($webinar);
        
        return $this->json([
            'start_time'       => $webinarDto->jsStartDatetime,
            'finish_time'      => $webinarDto->jsEndDatetime,
            'popup_start_time' => $webinarDto->jsConfirmationTime1,
            'popup_mid_time'   => $webinarDto->jsConfirmationTime2,
            'popup_end_time'   => $webinarDto->jsConfirmationTime3,
        ]);
    }
    
    public function subscribe(Request $request)
    {
        if (!$request->request->has('id')) {
            return $this->json([
                'status' => 'error',
                'error'  => 'required parameter missing',
            ]);
        }
        
        $id = $request->request->get('id');
        
        $webinar = $this->interactor->find($id);
        
        if (!$webinar instanceof Webinar) {
            return $this->json([
                'status' => 'error',
                'error'  => 'webinar not found'
            ]);
        }
        
        /** @var SymfonyUser $symfonyUser */
        $symfonyUser = $this->getUser();
        
        $this->interactor->subscribe($webinar, $symfonyUser->getUser());
        
        return $this->json(['status' => 'ok']);
    }
    
    public function unsubscribe(Request $request)
    {
        if (!$request->request->has('id')) {
            return $this->json([
                'status' => 'error',
                'error'  => 'required parameter missing',
            ]);
        }
        
        $id = $request->request->get('id');
        
        $webinar = $this->interactor->find($id);
        
        if (!$webinar instanceof Webinar) {
            return $this->json([
                'status' => 'error',
                'error'  => 'webinar not found'
            ]);
        }
        
        /** @var SymfonyUser $symfonyUser */
        $symfonyUser = $this->getUser();
        
        $this->interactor->unsubscribe($webinar, $symfonyUser->getUser());
        
        return $this->json(['status' => 'ok']);
    }
    
    public function profile(Request $request)
    {
        $symfonyUser = $this->getUser();
        $user        = $symfonyUser->getUser();
        $page        = $request->query->get('page', 1);
        $limit       = $request->query->get('limit', self::PER_PAGE);
        $period      = $request->query->get('period', self::DEFAULT_ARCHIVE_PERIOD);
        
        $list     = $this->interactor->getProfileWebinars($user, $page, $limit, $period);
        $webinars = $this->dtoAssembler->assembleList($list);
        
        $pages = ceil($list->count() / $limit);
        
        return $this->render(
            'frontend/profile/webinars.html.twig',
            [
                'list'   => $webinars,
                'page'   => $page,
                'pages'  => $pages,
                'limit'  => $limit,
                'period' => $period,
            ]
        );
    }
    
    public function sendMessage(Request $request)
    {
        if (!$request->request->has('id') || !$request->request->has('question')) {
            return $this->json([
                'status' => 'error',
                'error'  => 'required parameter missing',
            ]);
        }
        
        $id      = $request->request->get('id');
        $webinar = $this->interactor->find($id);
        
        if (!$webinar instanceof Webinar) {
            return $this->json([
                'status' => 'error',
                'error'  => 'webinar not found'
            ]);
        }
        
        /** @var SymfonyUser $symfonyUser */
        $symfonyUser = $this->getUser();
        
        $message = $request->request->get('question');
        
        $this->interactor->sendMessage($webinar, $symfonyUser->getUser(), $message);
        
        return $this->json(['status' => 'ok']);
    }
}
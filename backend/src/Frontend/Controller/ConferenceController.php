<?php

namespace App\Frontend\Controller;


use App\Domain\Entity\Conference\Conference;
use App\Domain\Entity\Conference\DTO\ConferenceDtoAssembler;
use App\Domain\Frontend\Interactor\ConferenceFrontendInteractor;
use App\Domain\Frontend\Interactor\FilterDirectionInterface;
use App\Security\SymfonyUser;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

final class ConferenceController extends AbstractController
{
    const PER_PAGE               = 12;
    const DEFAULT_PERIOD         = 'year';
    const DEFAULT_ARCHIVE_PERIOD = 'all';
    
    /**
     * @var ConferenceFrontendInteractor
     */
    private $interactor;
    /**
     * @var ConferenceDtoAssembler
     */
    private $dtoAssembler;
    /**
     * @var FilterDirectionInterface
     */
    private $filterDirection;
    
    /**
     * ConferenceController constructor.
     *
     * @param ConferenceFrontendInteractor $interactor
     * @param ConferenceDtoAssembler       $dtoAssembler
     * @param FilterDirectionInterface     $filterDirection
     */
    public function __construct(
        ConferenceFrontendInteractor $interactor,
        ConferenceDtoAssembler $dtoAssembler,
        FilterDirectionInterface $filterDirection
    ) {
        $this->interactor      = $interactor;
        $this->dtoAssembler    = $dtoAssembler;
        $this->filterDirection = $filterDirection;
    }
    
    public function list(Request $request)
    {
        $page   = $request->query->get('page', 1);
        $limit  = $request->query->get('limit', self::PER_PAGE);
        $period = $request->query->get('period', self::DEFAULT_PERIOD);
        
        $direction = $this->filterDirection->getSelectedDirection();
        
        $list        = $this->interactor->list($page, $limit, $direction, $period);
        $conferences = $this->dtoAssembler->assembleList($list);
        
        $pages = ceil($list->count() / $limit);
        
        return $this->render(
            'frontend/conference/list.html.twig',
            [
                'list'   => $conferences,
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
        
        $list        = $this->interactor->archive($page, $limit, $direction, $period);
        $conferences = $this->dtoAssembler->assembleList($list);
        
        $pages = ceil($list->count() / self::PER_PAGE);
        
        return $this->render(
            'frontend/conference/archive.html.twig',
            [
                'list'   => $conferences,
                'page'   => $page,
                'pages'  => $pages,
                'limit'  => $limit,
                'period' => $period,
            ]
        );
    }
    
    public function show($id)
    {
        $conference = $this->interactor->find($id);
        
        if (!$conference instanceof Conference) {
            throw $this->createNotFoundException('Нет такой коференции');
        }
        
        $conferenceDto = $this->dtoAssembler->assemble($conference);
        
        return $this->render('frontend/conference/show.html.twig', ['conference' => $conferenceDto]);
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
        
        $conference = $this->interactor->find($id);
        
        if (!$conference instanceof Conference) {
            return $this->json([
                'status' => 'error',
                'error'  => 'conference not found'
            ]);
        }
        
        /** @var SymfonyUser $symfonyUser */
        $symfonyUser = $this->getUser();
        
        try {
            $this->interactor->subscribe($conference, $symfonyUser->getUser());
        } catch (Exception $e) {
            return $this->json([
                'status' => 'error',
                'error'  => 'subscribe error'
            ]);
        }
        
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
        
        $conference = $this->interactor->find($id);
        
        if (!$conference instanceof Conference) {
            return $this->json([
                'status' => 'error',
                'error'  => 'conference not found'
            ]);
        }
        
        /** @var SymfonyUser $symfonyUser */
        $symfonyUser = $this->getUser();
        
        try {
            $this->interactor->unsubscribe($conference, $symfonyUser->getUser());
        } catch (Exception $e) {
            return $this->json([
                'status' => 'error',
                'error'  => 'unsubscribe error'
            ]);
        }
        
        return $this->json(['status' => 'ok']);
    }
    
    public function profile(Request $request)
    {
        $symfonyUser = $this->getUser();
        $user        = $symfonyUser->getUser();
        $page        = $request->query->get('page', 1);
        $limit       = $request->query->get('limit', self::PER_PAGE);
        $period      = $request->query->get('period', self::DEFAULT_ARCHIVE_PERIOD);
        
        $list = $this->interactor->getProfileConferences($user, $page, $limit, $period);
        $dtos = $this->dtoAssembler->assembleList($list);
        
        $pages = ceil($list->count() / $limit);
        
        return $this->render(
            'frontend/profile/conferences.html.twig',
            [
                'list'   => $dtos,
                'page'   => $page,
                'pages'  => $pages,
                'limit'  => $limit,
                'period' => $period,
            ]
        );
    }
}
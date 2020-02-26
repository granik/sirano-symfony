<?php

namespace App\Frontend\Controller;


use App\Domain\Entity\AdvertBanner\Frontend\DTO\AdvertBannerDtoAssembler;
use App\Domain\Entity\Banner\Frontend\DTO\BannerDtoAssembler;
use App\Domain\Entity\Conference\DTO\ConferenceDtoAssembler;
use App\Domain\Entity\Conference\Frontend\DTO\ConferenceSeriesDtoAssembler;
use App\Domain\Entity\Direction\Direction;
use App\Domain\Entity\Direction\DTO\DirectionDtoAssembler;
use App\Domain\Entity\News\Frontend\DTO\NewsDtoAssembler;
use App\Domain\Frontend\Interactor\AdvertBannerInteractor;
use App\Domain\Frontend\Interactor\BannerInteractor;
use App\Domain\Frontend\Interactor\ConferenceFrontendInteractor;
use App\Domain\Frontend\Interactor\ConferenceSeriesInteractor;
use App\Domain\Frontend\Interactor\DirectionInteractor;
use App\Domain\Frontend\Interactor\FilterDirectionInterface;
use App\Domain\Frontend\Interactor\NewsInteractor;
use App\Webinar\DTO\WebinarDtoAssembler;
use App\Webinar\Frontend\Interactor\WebinarInteractor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    const EVENTS_NUMBER = 12;
    const EVENTS_PERIOD = 'year';
    const NEWS_NUMBER   = 12;
    
    /**
     * @var ConferenceFrontendInteractor
     */
    private $conferenceInteractor;
    /**
     * @var ConferenceDtoAssembler
     */
    private $conferenceDtoAssembler;
    /**
     * @var WebinarInteractor
     */
    private $webinarInteractor;
    /**
     * @var WebinarDtoAssembler
     */
    private $webinarDtoAssembler;
    /**
     * @var NewsInteractor
     */
    private $newsInteractor;
    /**
     * @var NewsDtoAssembler
     */
    private $newsDtoAssembler;
    /**
     * @var AdvertBannerInteractor
     */
    private $bannerInteractor;
    /**
     * @var AdvertBannerDtoAssembler
     */
    private $advertBannerDtoAssembler;
    /**
     * @var BannerDtoAssembler
     */
    private $bannerDtoAssembler;
    /**
     * @var AdvertBannerInteractor
     */
    private $advertBannerInteractor;
    /**
     * @var DirectionInteractor
     */
    private $directionInteractor;
    /**
     * @var DirectionDtoAssembler
     */
    private $directionDtoAssembler;
    /**
     * @var FilterDirectionInterface
     */
    private $filterDirection;
    /**
     * @var ConferenceSeriesInteractor
     */
    private $conferenceSeriesInteractor;
    /**
     * @var ConferenceSeriesDtoAssembler
     */
    private $conferenceSeriesDtoAssembler;
    
    /**
     * DefaultController constructor.
     *
     * @param ConferenceFrontendInteractor $conferenceInteractor
     * @param WebinarInteractor            $webinarInteractor
     * @param NewsInteractor               $newsInteractor
     * @param AdvertBannerInteractor       $advertBannerInteractor
     * @param BannerInteractor             $bannerInteractor
     * @param ConferenceSeriesInteractor   $conferenceSeriesInteractor
     * @param ConferenceDtoAssembler       $conferenceDtoAssembler
     * @param WebinarDtoAssembler          $webinarDtoAssembler
     * @param NewsDtoAssembler             $newsDtoAssembler
     * @param AdvertBannerDtoAssembler     $advertBannerDtoAssembler
     * @param BannerDtoAssembler           $bannerDtoAssembler
     * @param DirectionInteractor          $directionInteractor
     * @param DirectionDtoAssembler        $directionDtoAssembler
     * @param ConferenceSeriesDtoAssembler $conferenceSeriesDtoAssembler
     * @param FilterDirectionInterface     $filterDirection
     */
    public function __construct(
        ConferenceFrontendInteractor $conferenceInteractor,
        WebinarInteractor $webinarInteractor,
        NewsInteractor $newsInteractor,
        AdvertBannerInteractor $advertBannerInteractor,
        BannerInteractor $bannerInteractor,
        ConferenceSeriesInteractor $conferenceSeriesInteractor,
        ConferenceDtoAssembler $conferenceDtoAssembler,
        WebinarDtoAssembler $webinarDtoAssembler,
        NewsDtoAssembler $newsDtoAssembler,
        AdvertBannerDtoAssembler $advertBannerDtoAssembler,
        BannerDtoAssembler $bannerDtoAssembler,
        DirectionInteractor $directionInteractor,
        DirectionDtoAssembler $directionDtoAssembler,
        ConferenceSeriesDtoAssembler $conferenceSeriesDtoAssembler,
        FilterDirectionInterface $filterDirection
    ) {
        $this->conferenceInteractor         = $conferenceInteractor;
        $this->conferenceDtoAssembler       = $conferenceDtoAssembler;
        $this->webinarInteractor            = $webinarInteractor;
        $this->advertBannerInteractor       = $advertBannerInteractor;
        $this->bannerInteractor             = $bannerInteractor;
        $this->webinarDtoAssembler          = $webinarDtoAssembler;
        $this->newsInteractor               = $newsInteractor;
        $this->newsDtoAssembler             = $newsDtoAssembler;
        $this->advertBannerDtoAssembler     = $advertBannerDtoAssembler;
        $this->bannerDtoAssembler           = $bannerDtoAssembler;
        $this->directionInteractor          = $directionInteractor;
        $this->directionDtoAssembler        = $directionDtoAssembler;
        $this->filterDirection              = $filterDirection;
        $this->conferenceSeriesInteractor   = $conferenceSeriesInteractor;
        $this->conferenceSeriesDtoAssembler = $conferenceSeriesDtoAssembler;
    }
    
    public function index()
    {
        $selectedDirection    = $this->filterDirection->getSelectedDirection();
        $selectedDirectionDto = null;
        if ($selectedDirection instanceof Direction) {
            $selectedDirectionDto = $this->directionDtoAssembler->assemble($selectedDirection);
        }
        
        $conferenceSeries = $this->conferenceSeriesDtoAssembler->assembleList(
            $this->conferenceSeriesInteractor->list(self::EVENTS_NUMBER, $selectedDirection)
        );
        
        $conferenceNumber = self::EVENTS_NUMBER - count($conferenceSeries);
        
        $conferences = [];
        if ($conferenceNumber > 0) {
            $conferences = $this->conferenceDtoAssembler->assembleList(
                $this->conferenceInteractor->listComingSoon($conferenceNumber, $selectedDirection)
            );
        }
        
        $webinars = $this->webinarDtoAssembler->assembleList(
            $this->webinarInteractor->list(1, self::EVENTS_NUMBER, $selectedDirection, self::EVENTS_PERIOD)
        );
        
        $news = $this->newsDtoAssembler->assembleList(
            $this->newsInteractor->mainPage(self::NEWS_NUMBER, $selectedDirection)
        );
        
        $banners = $this->bannerDtoAssembler->assembleList(
            $this->bannerInteractor->list()
        );
        
        $adBanners = $this->advertBannerDtoAssembler->assembleList(
            $this->advertBannerInteractor->list()
        );
        
        $directions = $this->directionDtoAssembler->assembleList(
            $this->directionInteractor->mainPage()
        );
        
        return $this->render('frontend/default.html.twig', [
            'conferences'       => $conferences,
            'webinars'          => $webinars,
            'news'              => $news,
            'banners'           => $banners,
            'adBanners'         => $adBanners,
            'directions'        => $directions,
            'selectedDirection' => $selectedDirectionDto,
            'conferenceSeries'  => $conferenceSeries,
        ]);
    }
    
    public function chukaeva()
    {
        return $this->render('frontend/static/chukaeva.html.twig');
    }
}
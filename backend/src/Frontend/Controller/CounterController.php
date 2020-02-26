<?php


namespace App\Frontend\Controller;


use App\Domain\Frontend\Interactor\CounterInteractor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class CounterController extends AbstractController
{
    /**
     * @var CounterInteractor
     */
    private $counterInteractor;
    
    /**
     * CounterController constructor.
     *
     * @param CounterInteractor $counterInteractor
     */
    public function __construct(CounterInteractor $counterInteractor)
    {
        $this->counterInteractor = $counterInteractor;
    }
    
    public function index()
    {
        $todayViews = $this->counterInteractor->getTodayViews();
        $allViews   = $this->counterInteractor->getAllViews();
        
        return $this->render('frontend/partials/counter.html.twig', [
            'todayViews' => $todayViews,
            'allViews'   => $allViews,
        ]);
    }
}
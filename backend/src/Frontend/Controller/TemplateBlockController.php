<?php


namespace App\Frontend\Controller;


use App\Domain\Backend\Interactor\CustomerInteractor;
use App\Domain\Entity\Customer\Frontend\DTO\CustomerProfileDtoAssembler;
use App\Domain\Entity\Direction\Direction;
use App\Domain\Frontend\Interactor\FilterDirectionInterface;
use App\Security\SymfonyUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class TemplateBlockController extends AbstractController
{
    /**
     * @var CustomerInteractor
     */
    private $customerInteractor;
    
    /**
     * @var CustomerProfileDtoAssembler
     */
    private $dtoAssembler;
    /**
     * @var FilterDirectionInterface
     */
    private $filterDirection;
    
    /**
     * TemplateBlockController constructor.
     *
     * @param CustomerInteractor          $customerInteractor
     * @param CustomerProfileDtoAssembler $dtoAssembler
     * @param FilterDirectionInterface    $filterDirection
     */
    public function __construct(
        CustomerInteractor $customerInteractor,
        CustomerProfileDtoAssembler $dtoAssembler,
        FilterDirectionInterface $filterDirection
    ) {
        $this->customerInteractor = $customerInteractor;
        $this->dtoAssembler       = $dtoAssembler;
        $this->filterDirection    = $filterDirection;
    }
    
    public function profile()
    {
        /** @var SymfonyUser $symfonyUser */
        $symfonyUser = $this->getUser();
        $customer    = $this->customerInteractor->getCustomer($symfonyUser->getUser());
        $dto         = $this->dtoAssembler->assemble($customer);
        
        return $this->render('frontend/partials/profile.html.twig', [
            'dto' => $dto,
        ]);
    }
    
    public function mobileProfile()
    {
        /** @var SymfonyUser $symfonyUser */
        $symfonyUser = $this->getUser();
        $customer    = $this->customerInteractor->getCustomer($symfonyUser->getUser());
        $dto         = $this->dtoAssembler->assemble($customer);
        
        return $this->render('frontend/partials/mobile-profile.html.twig', [
            'dto' => $dto,
        ]);
    }
    
    public function direction()
    {
        $direction = $this->filterDirection->getSelectedDirection();
        
        $directionName = null;
        if ($direction instanceof Direction) {
            $directionName = $direction->getName();
        }
        
        return $this->render('frontend/partials/direction.html.twig', [
            'directionName' => $directionName,
        ]);
    }
}
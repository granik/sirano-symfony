<?php

namespace App\Frontend\Controller;


use App\Domain\Backend\Interactor\CustomerInteractor;
use App\Domain\Entity\Conference\DTO\ConferenceDtoAssembler;
use App\Domain\Entity\Customer\Frontend\DTO\CustomerProfileDtoAssembler;
use App\Domain\Entity\Customer\Frontend\DTO\CustomerUpdateDtoAssembler;
use App\Domain\Entity\Module\Frontend\DTO\ModuleDtoAssembler;
use App\Domain\Frontend\Interactor\ConferenceFrontendInteractor;
use App\Domain\Frontend\Interactor\CustomerInteractor as FrontendCustomerInteractor;
use App\Domain\Frontend\Interactor\ModuleInteractor;
use App\Domain\Interactor\User\DTO\UserPasswordUpdateDto;
use App\Domain\Interactor\UserInteractor;
use App\Frontend\Form\CustomerUpdateType;
use App\Frontend\Form\UserPasswordUpdateType;
use App\Webinar\DTO\WebinarDtoAssembler;
use App\Webinar\Frontend\Interactor\WebinarInteractor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;

final class ProfileController extends AbstractController
{
    /**
     * @var WebinarDtoAssembler
     */
    private $webinarDtoAssembler;
    /**
     * @var WebinarInteractor
     */
    private $webinarInteractor;
    /**
     * @var ConferenceFrontendInteractor
     */
    private $conferenceFrontendInteractor;
    /**
     * @var ConferenceDtoAssembler
     */
    private $conferenceDtoAssembler;
    /**
     * @var ModuleDtoAssembler
     */
    private $moduleDtoAssembler;
    /**
     * @var ModuleInteractor
     */
    private $moduleInteractor;
    /**
     * @var CustomerInteractor
     */
    private $customerInteractor;
    /**
     * @var CustomerUpdateDtoAssembler
     */
    private $customerDtoAssembler;
    /**
     * @var UserInteractor
     */
    private $userInteractor;
    /**
     * @var FrontendCustomerInteractor
     */
    private $frontendCustomerInteractor;
    /**
     * @var CustomerProfileDtoAssembler
     */
    private $customerProfileDtoAssembler;
    
    /**
     * ProfileController constructor.
     *
     * @param WebinarInteractor            $webinarInteractor
     * @param WebinarDtoAssembler          $webinarDtoAssembler
     * @param ConferenceFrontendInteractor $conferenceFrontendInteractor
     * @param ConferenceDtoAssembler       $conferenceDtoAssembler
     * @param ModuleDtoAssembler           $moduleDtoAssembler
     * @param ModuleInteractor             $moduleInteractor
     * @param CustomerInteractor           $customerInteractor
     * @param CustomerUpdateDtoAssembler   $customerDtoAssembler
     * @param UserInteractor               $userInteractor
     * @param FrontendCustomerInteractor   $frontendCustomerInteractor
     * @param CustomerProfileDtoAssembler  $customerProfileDtoAssembler
     */
    public function __construct(
        WebinarInteractor $webinarInteractor,
        WebinarDtoAssembler $webinarDtoAssembler,
        ConferenceFrontendInteractor $conferenceFrontendInteractor,
        ConferenceDtoAssembler $conferenceDtoAssembler,
        ModuleDtoAssembler $moduleDtoAssembler,
        ModuleInteractor $moduleInteractor,
        CustomerInteractor $customerInteractor,
        CustomerUpdateDtoAssembler $customerDtoAssembler,
        UserInteractor $userInteractor,
        FrontendCustomerInteractor $frontendCustomerInteractor,
        CustomerProfileDtoAssembler $customerProfileDtoAssembler
    ) {
        $this->webinarDtoAssembler          = $webinarDtoAssembler;
        $this->webinarInteractor            = $webinarInteractor;
        $this->conferenceFrontendInteractor = $conferenceFrontendInteractor;
        $this->conferenceDtoAssembler       = $conferenceDtoAssembler;
        $this->moduleDtoAssembler           = $moduleDtoAssembler;
        $this->moduleInteractor             = $moduleInteractor;
        $this->customerInteractor           = $customerInteractor;
        $this->customerDtoAssembler         = $customerDtoAssembler;
        $this->userInteractor               = $userInteractor;
        $this->frontendCustomerInteractor   = $frontendCustomerInteractor;
        $this->customerProfileDtoAssembler  = $customerProfileDtoAssembler;
    }
    
    public function dashboard()
    {
        $symfonyUser = $this->getUser();
        $user        = $symfonyUser->getUser();
        
        $webinars    = $this->webinarDtoAssembler->assembleList($this->webinarInteractor->getDashboardWebinars($user));
        $conferences = $this->conferenceDtoAssembler->assembleList($this->conferenceFrontendInteractor->getDashboardConferences($user));
        $modules     = $this->moduleDtoAssembler->assembleList($this->moduleInteractor->getDashboardModules($user));
        
        $customer            = $this->customerInteractor->getCustomer($user);
        $onlineScore         = $this->frontendCustomerInteractor->getOnlineScore($customer);
        $onlinePercentScore  = $this->frontendCustomerInteractor->getOnlinePercentScore($customer);
        $offlineScore        = $this->frontendCustomerInteractor->getOfflineScore($customer);
        $offlinePercentScore = $this->frontendCustomerInteractor->getOfflinePercentScore($customer);
        
        $customerDto = $this->customerProfileDtoAssembler->assemble($customer);
        
        return $this->render(
            'frontend/profile/dashboard.html.twig',
            [
                'webinars'            => $webinars,
                'conferences'         => $conferences,
                'modules'             => $modules,
                'onlineScore'         => $onlineScore,
                'onlinePercentScore'  => $onlinePercentScore,
                'offlineScore'        => $offlineScore,
                'offlinePercentScore' => $offlinePercentScore,
                'customerDto'         => $customerDto,
            ]
        );
    }
    
    public function profile(Request $request)
    {
        $symfonyUser = $this->getUser();
        $user        = $symfonyUser->getUser();
        $customer    = $this->customerInteractor->getCustomer($user);
        $customerDto = $this->customerDtoAssembler->assemble($customer);
        
        $passwordUpdateDto = new UserPasswordUpdateDto();
        $passwordForm      = $this->createForm(
            UserPasswordUpdateType::class, $passwordUpdateDto, ['action' => $this->generateUrl('password_update')]
        );
        
        $form = $this->createForm(CustomerUpdateType::class, $customerDto);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $notifications = $this->customerInteractor->updateUser($user, $customerDto);
            
            if (empty($notifications)) {
                return $this->redirectToRoute('profile');
            }
            
            foreach ($notifications as $error) {
                $form->addError(new FormError($error));
            }
        }
        
        return $this->render('frontend/profile/edit.html.twig', [
            'form'         => $form->createView(),
            'passwordForm' => $passwordForm->createView(),
        ]);
    }
    
    public function passwordUpdate(Request $request)
    {
        $symfonyUser = $this->getUser();
        $user        = $symfonyUser->getUser();
        
        $customer    = $this->customerInteractor->getCustomer($user);
        $customerDto = $this->customerDtoAssembler->assemble($customer);
        $form        = $this->createForm(CustomerUpdateType::class, $customerDto, ['action' => $this->generateUrl('profile')]);
        
        $passwordUpdateDto = new UserPasswordUpdateDto();
        $passwordForm      = $this->createForm(
            UserPasswordUpdateType::class, $passwordUpdateDto, ['action' => $this->generateUrl('password_update')]
        );
        $passwordForm->handleRequest($request);
        
        if ($passwordForm->isSubmitted() && $passwordForm->isValid()) {
            $notifications = $this->userInteractor->updatePassword($user, $passwordUpdateDto);
            
            if (empty($notifications)) {
                $this->addFlash('notice', 'Пароль обновлён');
            } else {
                foreach ($notifications as $error) {
                    $passwordForm->addError(new FormError($error));
                }
            }
        }
        
        return $this->render('frontend/profile/edit.html.twig', [
            'form'         => $form->createView(),
            'passwordForm' => $passwordForm->createView(),
        ]);
    }
}
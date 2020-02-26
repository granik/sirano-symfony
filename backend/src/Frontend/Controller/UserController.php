<?php

namespace App\Frontend\Controller;


use App\Domain\Backend\Interactor\CustomerInteractor;
use App\Domain\Entity\Customer\Backend\DTO\CustomerDto;
use App\Domain\Interactor\UserInteractor;
use App\Frontend\Form\CustomerType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{
    /**
     * @var CustomerInteractor
     */
    private $customerInteractor;
    /**
     * @var UserInteractor
     */
    private $userInteractor;
    
    /**
     * UserController constructor.
     *
     * @param CustomerInteractor $customerInteractor
     * @param UserInteractor     $userInteractor
     */
    public function __construct(CustomerInteractor $customerInteractor, UserInteractor $userInteractor)
    {
        $this->customerInteractor = $customerInteractor;
        $this->userInteractor     = $userInteractor;
    }
    
    public function register(Request $request)
    {
        $customerDto = new CustomerDto();
        $form        = $this->createForm(CustomerType::class, $customerDto);
        $form->handleRequest($request);
        
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->customerInteractor->create($customerDto);
                
                return $this->json(['status' => 'ok']);
            }
            
            return $this->json(['status' => 'error', 'errors' => (string)$form->getErrors()]);
        }
        
        return $this->render('frontend/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    public function checkUserEmail(Request $request)
    {
        $email = $request->get('customer')['email'];
        
        if (empty($email)) {
            return $this->json('Email не указан');
        }
        
        if ($this->userInteractor->checkIfUserExists($email)) {
            return $this->json('Такой email уже зарегистрирован');
        }
        
        return $this->json(true);
    }
    
    public function confirm($code)
    {
        $notifications = $this->userInteractor->activateUser($code);
        
        return $this->render('frontend/login.html.twig', [
            'notifications' => $notifications,
        ]);
    }
    
    public function recovery(Request $request)
    {
        if (!$request->request->has('recovery-email')) {
            return $this->json(['status' => 'error', 'errors' => 'Email не указан']);
        }
        
        $email = $request->request->get('recovery-email');
        
        $notifications = $this->userInteractor->restoreUserPassword($email);
        
        if (empty($notifications)) {
            return $this->json(['status' => 'ok']);
        }
        
        return $this->json(['status' => 'error', 'errors' => implode("\n", $notifications)]);
    }
}
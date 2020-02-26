<?php

namespace App\Backend\Controller;


use App\Backend\Form\CustomerType;
use App\Backend\Form\UserPasswordUpdateType;
use App\Domain\Backend\Interactor\CustomerInteractor;
use App\Domain\Entity\Customer\Customer;
use App\Domain\Frontend\Interactor\CustomerInteractor as CustomerFrontendInteractor;
use App\Domain\Interactor\User\DTO\UserPasswordUpdateDto;
use App\Domain\Interactor\UserInteractor;
use App\DTO\CustomerDtoAssembler;
use App\Service\ExcelReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CustomerController extends AbstractController
{
    use DeleteController;
    
    const PER_PAGE = 12;
    
    /**
     * @var CustomerInteractor
     */
    private $interactor;
    /**
     * @var CustomerDtoAssembler
     */
    private $dtoAssembler;
    /**
     * @var CustomerFrontendInteractor
     */
    private $customerFrontendInteractor;
    /**
     * @var ExcelReader
     */
    private $excelReader;
    /**
     * @var UserInteractor
     */
    private $userInteractor;
    
    /**
     * CustomerController constructor.
     *
     * @param CustomerInteractor         $customerInteractor
     * @param CustomerDtoAssembler       $customerDtoAssembler
     * @param CustomerFrontendInteractor $customerFrontendInteractor
     * @param UserInteractor             $userInteractor
     * @param ExcelReader                $excelReader
     */
    public function __construct(
        CustomerInteractor $customerInteractor,
        CustomerDtoAssembler $customerDtoAssembler,
        CustomerFrontendInteractor $customerFrontendInteractor,
        UserInteractor $userInteractor,
        ExcelReader $excelReader
    ) {
        $this->interactor                 = $customerInteractor;
        $this->dtoAssembler               = $customerDtoAssembler;
        $this->customerFrontendInteractor = $customerFrontendInteractor;
        $this->excelReader                = $excelReader;
        $this->userInteractor             = $userInteractor;
    }
    
    public function index(Request $request)
    {
        $page     = $request->query->get('page', 1);
        $limit    = $request->query->get('limit', self::PER_PAGE);
        $criteria = [];
        foreach (
            [
                'name',
                'email',
                'addedFrom',
                'registeredAt',
                'isActive',
                'sendingCounter',
                'sendingDateTime',
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
        $total    = $this->interactor->total($criteria);
        $entities = $this->dtoAssembler->assembleList($list);
        
        $pages = ceil($list->count() / $limit);
        
        return $this->render(
            'backend/customer/list.html.twig',
            [
                'list'            => $entities,
                'currentPage'     => $page,
                'pages'           => $pages,
                'limit'           => $limit,
                'name'            => $name,
                'email'           => $email,
                'addedFrom'       => $addedFrom,
                'registeredAt'    => $registeredAt,
                'isActive'        => $isActive,
                'sendingCounter'  => $sendingCounter,
                'sendingDateTime' => $sendingDateTime,
                'total'           => $total,
            ]
        );
    }
    
    public function edit(Request $request, $id)
    {
        /** @var Customer $entity */
        $entity = $this->interactor->find($id);
        $dto    = $this->dtoAssembler->assemble($entity);
        
        $form = $this->createForm(CustomerType::class, $dto);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->interactor->update($dto);
            
            $this->addFlash('notice', 'Пользователь сохранён');
            
            return $this->redirectToRoute('cms_customer_edit', ['id' => $entity->getId()]);
        }
        
        $offlineScore = $this->customerFrontendInteractor->getOfflineScore($entity);
        $onlineScore  = $this->customerFrontendInteractor->getOnlineScore($entity);
        
        return $this->render('backend/customer/edit.html.twig', [
            'form'            => $form->createView(),
            'conferenceScore' => $this->customerFrontendInteractor->getConferenceScore($entity),
            'webinarScore'    => $this->customerFrontendInteractor->getWebinarScore($entity),
            'moduleScore'     => $this->customerFrontendInteractor->getModuleScore($entity),
            'offlineScore'    => $offlineScore,
            'onlineScore'     => $onlineScore,
            'score'           => $onlineScore + $offlineScore,
        ]);
    }
    
    public function passwordUpdate(Request $request, $id)
    {
        /** @var Customer $entity */
        $entity = $this->interactor->find($id);
        
        $dto  = new UserPasswordUpdateDto();
        $form = $this->createForm(UserPasswordUpdateType::class, $dto);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $user          = $this->userInteractor->findByCustomer($entity);
            $notifications = $this->userInteractor->updatePasswordWithoutCheck($user, $form->get('newPassword')->getData());
            
            $this->addFlash('notice', 'Пароль обновлён');
            
            return $this->redirectToRoute('cms_customer_edit', ['id' => $id]);
        }
        
        return $this->render('backend/customer/password.html.twig', [
            'form' => $form->createView(),
            'id'   => $id,
        ]);
    }
    
    public function download(Request $request)
    {
        $criteria = [];
        foreach (
            [
                'name',
                'email',
                'addedFrom',
                'registeredAt',
                'isActive',
                'sendingCounter',
                'sendingDateTime',
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
        $excel = $this->interactor->saveList($criteria);
        
        $response = new StreamedResponse(function () use ($excel) {
            $excel->save('php://output');
        });
        
        $date = date('Ymd');
        
        $response->headers->set('Content-Type', 'application/vnd.ms-excel; charset=utf-8');
        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            "report_users_$date.xlsx"
        );
        $response->headers->set('Content-Disposition', $disposition);
        
        return $response;
    }
    
    public function upload(Request $request)
    {
        if ($request->files->has('list') && $request->files->get('list') !== null) {
            /** @var UploadedFile $excelFile */
            $excelFile = $request->files->get('list');
            $list      = $this->excelReader->readCustomersFromFile($excelFile->getPathname());
            
            $this->interactor->loadFromList($list);
        }
        
        return $this->redirectToRoute('cms_customers');
    }
}
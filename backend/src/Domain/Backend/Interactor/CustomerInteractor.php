<?php

namespace App\Domain\Backend\Interactor;


use App\Domain\Entity\Conference\Backend\DTO\ConferenceSubscriberDto;
use App\Domain\Entity\Customer\Backend\CustomerRepositoryInterface;
use App\Domain\Entity\Customer\Backend\DTO\CustomerDto;
use App\Domain\Entity\Customer\Customer;
use App\Domain\Entity\Customer\Frontend\DTO\CustomerUpdateDto;
use App\Domain\Entity\Customer\Interactor\CustomerCityGetInteractor;
use App\Domain\Entity\Customer\Interactor\CustomerCityNameInteractor;
use App\Domain\Frontend\Interactor\Exceptions\UserIsNotCustomer;
use App\Domain\Interactor\User\DTO\UserDto;
use App\Domain\Interactor\User\User;
use App\Domain\Interactor\UserInteractor;
use App\Domain\Service\UserUtils;
use App\Interactors\MailerInterface;
use App\Interactors\NonExistentEntity;

final class CustomerInteractor
{
    const UPLOAD_DIRECTORY = 'customer';
    
    /**
     * @var CustomerRepositoryInterface
     */
    private $repository;
    /**
     * @var UserInteractor
     */
    private $userInteractor;
    /**
     * @var FileUploader
     */
    private $fileUploader;
    /**
     * @var TableWriterInterface
     */
    private $tableWriter;
    /**
     * @var MailerInterface
     */
    private $mailer;
    /**
     * @var CustomerCityNameInteractor
     */
    private $customerCityNameInteractor;
    /**
     * @var CustomerCityGetInteractor
     */
    private $customerCityGetInteractor;
    /**
     * @var MainSpecialtyInteractor
     */
    private $mainSpecialtyInteractor;
    /**
     * @var AdditionalSpecialtyInteractor
     */
    private $additionalSpecialtyInteractor;
    
    /**
     * CustomerInteractor constructor.
     *
     * @param CustomerRepositoryInterface   $repository
     * @param UserInteractor                $userInteractor
     * @param CustomerCityNameInteractor    $customerCityNameInteractor
     * @param CustomerCityGetInteractor     $customerCityGetInteractor
     * @param MainSpecialtyInteractor       $mainSpecialtyInteractor
     * @param AdditionalSpecialtyInteractor $additionalSpecialtyInteractor
     * @param FileUploader                  $fileUploader
     * @param TableWriterInterface          $tableWriter
     * @param MailerInterface               $mailer
     */
    public function __construct(
        CustomerRepositoryInterface $repository,
        UserInteractor $userInteractor,
        CustomerCityNameInteractor $customerCityNameInteractor,
        CustomerCityGetInteractor $customerCityGetInteractor,
        MainSpecialtyInteractor $mainSpecialtyInteractor,
        AdditionalSpecialtyInteractor $additionalSpecialtyInteractor,
        FileUploader $fileUploader,
        TableWriterInterface $tableWriter,
        MailerInterface $mailer
    ) {
        $this->repository                    = $repository;
        $this->userInteractor                = $userInteractor;
        $this->fileUploader                  = $fileUploader;
        $this->tableWriter                   = $tableWriter;
        $this->mailer                        = $mailer;
        $this->customerCityNameInteractor    = $customerCityNameInteractor;
        $this->customerCityGetInteractor     = $customerCityGetInteractor;
        $this->mainSpecialtyInteractor       = $mainSpecialtyInteractor;
        $this->additionalSpecialtyInteractor = $additionalSpecialtyInteractor;
    }
    
    public function create(CustomerDto $dto)
    {
        return $this->createWithStatus($dto, User::ADDED_FROM_SITE);
    }
    
    /**
     * @param CustomerDto $dto
     *
     * @return bool
     * @throws \Exception
     */
    public function createFromFile(CustomerDto $dto)
    {
        $dto->password     = UserUtils::getPassword();
        $cityDto           = $this->customerCityGetInteractor->getCityFromString($dto->cityName);
        $dto->cityName     = $cityDto->name;
        $dto->country      = $cityDto->country;
        $dto->fullCityName = $cityDto->fullName;
        $dto->kladrId      = $cityDto->kladrId;
        
        return $this->createWithStatus($dto, User::ADDED_FROM_FILE);
    }
    
    /**
     * @param ConferenceSubscriberDto $dto
     *
     * @return Customer|bool
     * @throws \Exception
     */
    public function createFromSubscriber(ConferenceSubscriberDto $dto)
    {
        if ($this->userInteractor->checkIfUserExists($dto->email)) {
            return false;
        }
        
        $cityDto = $this->customerCityGetInteractor->getCityFromString($dto->city);
        $city    = $this->customerCityGetInteractor->getCity(
            $cityDto->country,
            $cityDto->name,
            $cityDto->fullName,
            $cityDto->kladrId
        );
        $entity  = new Customer();
        $entity
            ->setName($dto->name)
            ->setMiddlename($dto->middlename)
            ->setLastname($dto->lastname)
            ->setPhone($dto->phone)
            ->setEmail($dto->email)
            ->setCityId($city->getId())
            ->setMainSpecialtyId($dto->mainSpecialtyId)
            ->setAdditionalSpecialtyId($dto->additionalSpecialtyId);
        $entity = $this->repository->store($entity);
        
        $password            = UserUtils::getPassword();
        $userDto             = new UserDto();
        $userDto->login      = $dto->email;
        $userDto->isAdmin    = false;
        $userDto->password   = $password;
        $userDto->customerId = $entity->getId();
        $userDto->addedFrom  = User::ADDED_FROM_FILE;
        $this->userInteractor->create($userDto);
        
        return $entity;
    }
    
    public function list(int $page, int $perPage, array $criteria)
    {
        return $this->repository->list($page, $perPage, $criteria);
    }
    
    public function find($id)
    {
        return $this->repository->find($id);
    }
    
    /**
     * @param User              $user
     * @param CustomerUpdateDto $dto
     *
     * @return array
     * @throws UserIsNotCustomer
     * @throws Exceptions\NoUploadFile
     */
    public function updateUser(User $user, CustomerUpdateDto $dto)
    {
        $entity = $this->getCustomer($user);
        
        $notifications = [];
        
        $userWithEmail = $this->userInteractor->findAnyByLogin($dto->email);
        if ($userWithEmail instanceof User && $userWithEmail->getId() !== $user->getId()) {
            $notifications[] = 'Пользователь с таким email уже существвует';
            
            return $notifications;
        }
        
        $this->fillEntity($entity, $dto);
        $this->fillEntityImages($entity, $dto);
        $this->repository->update($entity);
        
        if ($user->getLogin() !== $dto->email) {
            $user->setLogin($dto->email);
            $this->userInteractor->updateEntity($user);
        }
        
        return $notifications;
    }
    
    /**
     * @param User $user
     *
     * @return Customer
     * @throws UserIsNotCustomer
     */
    public function getCustomer(User $user): Customer
    {
        $customerId = $user->getCustomerId();
        
        if ($customerId === null) {
            throw new UserIsNotCustomer();
        }
        
        return $this->find($customerId);
    }
    
    /**
     * @param CustomerDto $dto
     *
     * @throws NonExistentEntity
     */
    public function update(CustomerDto $dto)
    {
        $entity = $this->find($dto->id);
        
        if (!$entity instanceof Customer) {
            throw new NonExistentEntity();
        }
        
        $city = $this->customerCityGetInteractor->getCity(
            $dto->country,
            $dto->cityName,
            $dto->fullCityName,
            $dto->kladrId
        );
        
        $entity
            ->setName($dto->name)
            ->setMiddlename($dto->middlename)
            ->setLastname($dto->lastname)
            ->setPhone($dto->phone)
            ->setEmail($dto->email)
            ->setDirectionId($dto->directionId)
            ->setCityId($city->getId())
            ->setMainSpecialtyId($dto->mainSpecialtyId)
            ->setAdditionalSpecialtyId($dto->additionalSpecialtyId);
        
        $this->repository->update($entity);
        
        $user = $this->userInteractor->findByCustomer($entity);
        $this->userInteractor->updateEntity($user->setIsAdmin($dto->admin)->setSendingCounter($dto->sendingCounter));
    }
    
    public function saveList(array $criteria = [])
    {
        $report   = [];
        $report[] = [
            'Фамилия',
            'Имя',
            'Отчество',
            'Населенный пункт',
            'Специальность',
            'Ученая степень',
            'Email',
            'Телефон',
            'Дата регистрации',
            'Статус',
        ];
        
        /** @var Customer $customer */
        foreach ($this->repository->listAll($criteria) as $customer) {
            $user = $this->userInteractor->findByCustomer($customer);
            
            if (!$user instanceof User) {
                continue;
            }
            
            $report[] = [
                $customer->getLastname(),
                $customer->getName(),
                $customer->getMiddlename(),
                $this->customerCityNameInteractor->getFullCityName($customer),
                $this->mainSpecialtyInteractor->getNameById($customer->getMainSpecialtyId()),
                $this->additionalSpecialtyInteractor->getNameById($customer->getAdditionalSpecialtyId()),
                $customer->getEmail(),
                $customer->getPhone(),
                $user->getRegisteredAt(),
                $user->getAddedFromName(),
            ];
        }
        
        return $this->tableWriter->write($report);
    }
    
    /**
     * @param $id
     *
     * @throws NonExistentEntity
     */
    public function delete($id)
    {
        $entity = $this->find($id);
        
        if (!$entity instanceof Customer) {
            throw new NonExistentEntity();
        }
        
        $user = $this->userInteractor->findByCustomer($entity);
        
        $this->repository->delete($entity);
        
        if ($user instanceof User) {
            $this->userInteractor->delete($user);
        }
    }
    
    public function findByEmail($email)
    {
        return $this->repository->findByEmail($email);
    }
    
    /**
     * @param CustomerDto[] $list
     */
    public function loadFromList(array $list)
    {
        foreach ($list as $customerDto) {
            $customer = $this->findByEmail($customerDto->email);
            
            if ($customer instanceof Customer) {
                continue;
            }
            
            $this->createFromFile($customerDto);
        }
    }
    
    public function total(array $criteria)
    {
        return $this->repository->total($criteria);
    }
    
    private function createWithStatus(CustomerDto $dto, int $status)
    {
        if ($this->userInteractor->checkIfUserExists($dto->email)) {
            return false;
        }
        
        $city   = $this->customerCityGetInteractor->getCity(
            $dto->country,
            $dto->cityName,
            $dto->fullCityName,
            $dto->kladrId
        );
        $entity = new Customer();
        $entity
            ->setName($dto->name)
            ->setMiddlename($dto->middlename)
            ->setLastname($dto->lastname)
            ->setPhone($dto->phone)
            ->setEmail($dto->email)
            ->setDirectionId($dto->directionId)
            ->setCityId($city->getId())
            ->setMainSpecialtyId($dto->mainSpecialtyId)
            ->setAdditionalSpecialtyId($dto->additionalSpecialtyId);
        
        $entity = $this->repository->store($entity);
        
        $userDto             = new UserDto();
        $userDto->login      = $dto->email;
        $userDto->isAdmin    = false;
        $userDto->password   = $dto->password;
        $userDto->customerId = $entity->getId();
        $userDto->addedFrom  = $status;
        
        $this->userInteractor->create($userDto);
        
        return true;
    }
    
    private function fillEntity(Customer $entity, CustomerUpdateDto $dto)
    {
        $city = $this->customerCityGetInteractor->getCity(
            $dto->country,
            $dto->cityName,
            $dto->fullCityName,
            $dto->kladrId
        );
        
        $entity
            ->setName($dto->name)
            ->setMiddlename($dto->middlename)
            ->setLastname($dto->lastname)
            ->setPhone($dto->phone)
            ->setEmail($dto->email)
            ->setDirectionId($dto->directionId)
            ->setCityId($city->getId())
            ->setMainSpecialtyId($dto->mainSpecialtyId)
            ->setAdditionalSpecialtyId($dto->additionalSpecialtyId);
    }
    
    /**
     * @param Customer          $entity
     * @param CustomerUpdateDto $dto
     *
     * @return void
     * @throws Exceptions\NoUploadFile
     */
    private function fillEntityImages(Customer $entity, CustomerUpdateDto $dto)
    {
        if (
            $dto->avatarFile instanceof File
            && $dto->avatarFile->getUploadedFile() instanceof UploadedFile
        ) {
            $entity->setAvatar(
                $this->fileUploader->upload(
                    $dto->avatarFile,
                    'avatar',
                    self::UPLOAD_DIRECTORY . '/' . $entity->getId()
                )
            );
        }
    }
}
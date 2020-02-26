<?php


namespace App\Frontend\Repository;


use App\Domain\Entity\Customer\Customer;
use App\Domain\Entity\Module\Frontend\ModuleTestResultRepositoryInterface;
use App\Domain\Entity\Module\Module;
use App\Domain\Entity\Module\ModuleTestResult;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;

final class ModuleTestResultRepository implements ModuleTestResultRepositoryInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    
    /**
     * @var ObjectRepository
     */
    private $objectRepository;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager    = $entityManager;
        $this->objectRepository = $this->entityManager->getRepository(ModuleTestResult::class);
    }
    
    public function findByModuleAndCustomer(Module $module, Customer $customer)
    {
        return $this->objectRepository->findOneBy(['module' => $module, 'customer' => $customer]);
    }
    
    public function store(ModuleTestResult $result)
    {
        $this->entityManager->persist($result);
        $this->entityManager->flush();
    }
}
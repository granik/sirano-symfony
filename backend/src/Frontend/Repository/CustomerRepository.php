<?php


namespace App\Frontend\Repository;


use App\Domain\Entity\Customer\Customer;
use App\Domain\Entity\Customer\Frontend\CustomerRepositoryInterface;
use App\Domain\Interactor\User\User;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

final class CustomerRepository implements CustomerRepositoryInterface
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
        $this->objectRepository = $this->entityManager->getRepository(Customer::class);
    }
    
    public function list(int $page, int $perPage, ?string $search)
    {
        $where = '';
        
        if (!empty($search)) {
            $where = ' AND (c.name LIKE :query OR c.lastname LIKE :query OR c.middlename LIKE :query) ';
        }
        
        $dql   = 'SELECT c FROM '
            . Customer::class
            . ' c JOIN '
            . User::class
            . ' u WHERE c.id = u.customerId AND u.isActive = true '
            . $where
            . ' ORDER BY c.lastname, c.name, c.middlename ';
        $query = $this->entityManager->createQuery($dql)
            ->setFirstResult(($page - 1) * $perPage)
            ->setMaxResults($perPage);
    
        if (!empty($search)) {
            $query->setParameter('query', "%$search%");
        }
        
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        
        return $paginator;
    }
}
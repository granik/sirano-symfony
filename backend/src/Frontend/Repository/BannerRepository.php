<?php


namespace App\Frontend\Repository;


use App\Domain\Entity\Banner\Banner;
use App\Domain\Entity\Banner\Frontend\BannerRepositoryInterface;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;

final class BannerRepository implements BannerRepositoryInterface
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
        $this->objectRepository = $this->entityManager->getRepository(Banner::class);
    }
    
    public function list()
    {
        return $this->objectRepository->findBy(['isActive' => true], ['number' => 'asc']);
    }
}
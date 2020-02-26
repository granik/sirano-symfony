<?php


namespace App\Frontend\Repository;


use App\Domain\Entity\AdvertBanner\AdvertBanner;
use App\Domain\Entity\AdvertBanner\Frontend\AdvertBannerRepositoryInterface;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;

final class AdvertBannerRepository implements AdvertBannerRepositoryInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    
    /**
     * @var ObjectRepository
     */
    private $objectRepository;
    
    /**
     * @var ObjectRepository
     */
    private $slideRepository;
    
    /**
     * @var ObjectRepository
     */
    private $articleRepository;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager    = $entityManager;
        $this->objectRepository = $this->entityManager->getRepository(AdvertBanner::class);
    }
    
    public function list()
    {
        return $this->objectRepository->findBy(['isActive' => true], ['number' => 'asc']);
    }
}
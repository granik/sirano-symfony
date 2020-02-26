<?php

namespace App\Interfaces;


use App\Webinar\WebinarReport;
use App\Webinar\WebinarReportRepositoryInterface;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;

final class WebinarReportRepository implements WebinarReportRepositoryInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;
    
    /** @var ObjectRepository */
    private $objectRepository;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager    = $entityManager;
        $this->objectRepository = $this->entityManager->getRepository(WebinarReport::class);
    }
    
    public function store(WebinarReport $report)
    {
        $this->entityManager->persist($report);
        $this->entityManager->flush();
    }
    
    public function update(WebinarReport $report)
    {
        $this->entityManager->flush();
    }
}
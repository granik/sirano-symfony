<?php

namespace App\Webinar\Backend;

use App\Domain\Backend\Interactor\DirectionInteractor;
use App\Domain\Backend\Interactor\FileUploader;
use App\Domain\Backend\Interactor\TableWriterInterface;
use App\Domain\Entity\Direction\Backend\CategoryRepositoryInterface;
use App\Domain\Entity\Direction\DirectionRepositoryInterface;
use App\Tests\Builders\CustomerBuilder;
use App\Webinar\Webinar;
use App\Webinar\WebinarReportRepositoryInterface;
use App\Webinar\WebinarSubscriber;
use PHPUnit\Framework\TestCase;

class WebinarInteractorTest extends TestCase
{
    
    public function testSaveSubscribers()
    {
        $customerBuilder = CustomerBuilder::instance();
        
        $webinarRepository = $this->createMock(WebinarRepositoryInterface::class);
        $webinarRepository
            ->method('find')
            ->willReturn(
                (new Webinar())
                    ->setSubscribers([
                        (new WebinarSubscriber())
                            ->setCustomer($customerBuilder->build())
                    ])
            );
        $webinarReportRepository = $this->createMock(WebinarReportRepositoryInterface::class);
        $directionRepository     = $this->createMock(DirectionRepositoryInterface::class);
        $categoryRepository      = $this->createMock(CategoryRepositoryInterface::class);
        $tableWriter             = $this->getMockBuilder(TableWriterInterface::class)->setMethods(['write'])->getMock();
        $list                    = [
            [
                'Фамилия',
                'Имя',
                'Отчество',
                'Город',
                'Специализация',
                'Посещение',
                'Телефон',
                'Email',
                'Кол-во отметок',
            ],
            [
                'Lastname',
                'Name',
                null,
                'City',
                'Specialty',
                'Не посетил',
                '+7(000)000-00-00',
                'user@example.com',
                0,
            ],
        ];
        $tableWriter->expects($this->once())
            ->method('write')
            ->with($this->identicalTo($list));
        
        $fileUploader        = new FileUploader('target_dir');
        $directionInteractor = new DirectionInteractor(
            $directionRepository,
            $categoryRepository,
            $fileUploader,
            'target_dir'
        );
        
        $webinarInteractor = new WebinarInteractor(
            $webinarRepository,
            $directionInteractor,
            $webinarReportRepository,
            $fileUploader,
            $tableWriter
        );
        
        $webinarInteractor->saveSubscribers(1);
    }
}

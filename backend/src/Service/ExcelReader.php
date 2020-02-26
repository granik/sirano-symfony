<?php


namespace App\Service;


use App\Domain\Backend\Interactor\AdditionalSpecialtyInteractor;
use App\Domain\Backend\Interactor\MainSpecialtyInteractor;
use App\Domain\Entity\Conference\Backend\DTO\ConferenceSubscriberDto;
use App\Domain\Entity\Customer\Backend\DTO\CustomerDto;
use PhpOffice\PhpSpreadsheet\IOFactory;

final class ExcelReader
{
    /**
     * @var MainSpecialtyInteractor
     */
    private $mainSpecialtyInteractor;
    /**
     * @var AdditionalSpecialtyInteractor
     */
    private $additionalSpecialtyInteractor;
    
    /**
     * ExcelReader constructor.
     *
     * @param MainSpecialtyInteractor       $mainSpecialtyInteractor
     * @param AdditionalSpecialtyInteractor $additionalSpecialtyInteractor
     */
    public function __construct(
        MainSpecialtyInteractor $mainSpecialtyInteractor,
        AdditionalSpecialtyInteractor $additionalSpecialtyInteractor
    ) {
        $this->mainSpecialtyInteractor       = $mainSpecialtyInteractor;
        $this->additionalSpecialtyInteractor = $additionalSpecialtyInteractor;
    }
    
    /**
     * @param $fileName
     *
     * @return ConferenceSubscriberDto[]
     *
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function readConferenceSubscribersFromFile($fileName)
    {
        $spreadsheet = IOFactory::load($fileName);
        $worksheet   = $spreadsheet->getActiveSheet();
        $list        = [];
        
        foreach ($worksheet->getRowIterator(2) as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
            
            if ($cellIterator->current()->getValue() === null) {
                continue;
            }
            
            $dto           = new ConferenceSubscriberDto();
            $dto->lastname = $cellIterator->current()->getValue();
            $cellIterator->next();
            $dto->name = $cellIterator->current()->getValue();
            $cellIterator->next();
            $dto->middlename = $cellIterator->current()->getValue();
            $cellIterator->next();
            $dto->city = $cellIterator->current()->getValue();
            $cellIterator->next();
            $dto->mainSpecialtyId = $this->mainSpecialtyInteractor->findIdByName($cellIterator->current()->getValue());
            $cellIterator->next();
            $dto->additionalSpecialtyId = $this->additionalSpecialtyInteractor->findIdByName(
                $cellIterator->current()->getValue()
            );
            $cellIterator->next();
            $dto->email = $cellIterator->current()->getValue();
            $cellIterator->next();
            $dto->phone = $cellIterator->current()->getValue();
            
            $list[] = $dto;
        }
        
        return $list;
    }
    
    /**
     * @param $fileName
     *
     * @return CustomerDto[]
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function readCustomersFromFile($fileName)
    {
        $spreadsheet = IOFactory::load($fileName);
        $worksheet   = $spreadsheet->getActiveSheet();
        $list        = [];
        
        foreach ($worksheet->getRowIterator(2) as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
            
            $dto           = new CustomerDto();
            $dto->lastname = $cellIterator->current()->getValue();
            $cellIterator->next();
            $dto->name = $cellIterator->current()->getValue();
            $cellIterator->next();
            $dto->middlename = $cellIterator->current()->getValue();
            $cellIterator->next();
            $dto->cityName = $cellIterator->current()->getValue();
            $cellIterator->next();
            $dto->mainSpecialtyId = $this->mainSpecialtyInteractor->findIdByName($cellIterator->current()->getValue());
            $cellIterator->next();
            $dto->additionalSpecialtyId = $this->additionalSpecialtyInteractor->findIdByName(
                $cellIterator->current()->getValue()
            );
            $cellIterator->next();
            $dto->email = $cellIterator->current()->getValue();
            $cellIterator->next();
            $dto->phone = $cellIterator->current()->getValue();
            
            $list[] = $dto;
        }
        
        return $list;
    }
}
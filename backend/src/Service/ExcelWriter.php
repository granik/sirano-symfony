<?php


namespace App\Service;


use App\Domain\Backend\Interactor\TableWriterInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

final class ExcelWriter implements TableWriterInterface
{
    public function write(array $report)
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
        $spreadsheet->getDefaultStyle()->getFont()->setSize(9.5);
        
        $spreadsheet
            ->getProperties()
            ->setCreator('Edu')
            ->setLastModifiedBy('Edu')
            ->setTitle('Report')
            ->setSubject('Report')
            ->setDescription('Report from Edu');
        
        $spreadsheet->setActiveSheetIndex(0);
        
        $spreadsheet
            ->getActiveSheet()
            ->getPageSetup()
            ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
        
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Report');
        $sheet->getPageMargins()->setTop(0.4);
        $sheet->getPageMargins()->setRight(0.4);
        $sheet->getPageMargins()->setLeft(0.4);
        $sheet->getPageMargins()->setBottom(0.6);
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(0);
        
        $row = 1;
        foreach ($report as $reportRow) {
            $column = 1;
            foreach ($reportRow as $reportColumn) {
                $sheet->setCellValueByColumnAndRow($column++, $row, $reportColumn);
            }
            $row++;
        }
        
        return new Xlsx($spreadsheet);
    }
}
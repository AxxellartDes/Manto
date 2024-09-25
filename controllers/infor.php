<?php

require_once 'libs/controller.php';
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Infor extends Controller
{

    function __construct()
    {
        parent::__construct();
        $this->view->mensaje = "";
    }

    function render()
    {
        $reports = $this->model->searchAllGift();
        $users   = $this->model->searchAllUsers();
        $titles     = $reports['0'];
        $titles     = (array_keys($titles));
        $titles2     = $users['0'];
        $titles2     = (array_keys($titles2));
        // print_r($titles);
        $mensaje = '';
        $this->view->mensaje = $mensaje;
        $reports = json_decode(json_encode($reports), true);
        $titles     = $reports['0'];
        $titles     = (array_keys($titles));

        date_default_timezone_set('America/Bogota');
        $time = time();
        $time_now = date('Y-m-d');

        $fecha_busqueda = $time_now . " 00:00:00";
        $filename = "GIFT_" . date("dmY-His", $time) . '.xlsx';

        $spreadsheet = new Spreadsheet();

        $spreadsheet
            ->getProperties()
            ->setCreator("Sí18")
            ->setLastModifiedBy('SI18')
            ->setTitle('Gift')
            ->setSubject('Excel de prueba')
            ->setDescription('Excel generado como prueba')
            ->setKeywords('PHPSpreadsheet')
            ->setCategory('Categoría de prueba');

        $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(15);
        $sheet = $spreadsheet->getActiveSheet()->setAutoFilter('A1:I1');
        
        $sheet->fromArray($titles, NULL, 'A1');
        $sheet->fromArray($reports, NULL, 'A2');
        $sheet->getStyle("A1:I1")->getFont()->setBold(true);
        
        $sheet1 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Colaboradores');
        $spreadsheet->addSheet($sheet1);
        $sheet = $spreadsheet->setActiveSheetIndexByName('Colaboradores')->setAutoFilter('A1:I1');
        $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(15);
        $sheet->fromArray($titles2, NULL, 'A1');
        $sheet->fromArray($users, NULL, 'A2');
        $sheet->getStyle("A1:Z1")->getFont()->setBold(true);

        // Set the value of cell A1 
        // $sheet->setCellValue('A1', 'A1 Cell Data Here');
        // $sheet->setCellValue('B1', 'B1 Cell Data Here');

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
        // $this->view->render('infor/index');
    }

}

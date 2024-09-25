<?php

require_once 'libs/controller.php';

require_once 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class Enlistment extends Controller
{

    function __construct()
    {
        parent::__construct();
        $this->view->mensaje = "";
        $this->view->mensaje_1 = "";
        $this->view->mensaje_2 = "";
    }

    // function render()
    // {
    //     $this->view->render('almacen/select');
    // }

    function listRegister()
    {

        if (!isset($_SESSION)) {
            session_start();
        }

        if (isset($_SESSION['iduser'])) {
            $company    = $_SESSION['company'];

            if ($_SESSION['rol_idrol'] == 1) {
                $this->view->registers = $this->model->getRegisters();
            } elseif ($_SESSION['rol_idrol'] == 6) {
                $this->view->registers = $this->model->getRegistersCompany($company);
            }
        }
        $this->view->render('enlistment/listregister');
    }

    function listRegisterDate()
    {

        if (!isset($_SESSION)) {
            session_start();
        }

        $initial_date   = $_POST['initial_date'];
        $initial_hour   = $_POST['initial_hour'];
        $final_date     = $_POST['final_date'];
        $final_hour     = $_POST['final_hour'];

        $initial_date   = $initial_date." ".$initial_hour;
        $final_date     = $final_date." ".$final_hour;


        if (isset($_SESSION['iduser'])) {
            $company    = $_SESSION['company'];

            if (isset($_POST['bus']) AND $_POST['bus'] != "") {
                $bus   = $_POST['bus'];
                if ($_SESSION['rol_idrol'] == 1) {
                    $this->view->registers = $this->model->getRegistersDateBus($initial_date, $final_date, $bus);
                } elseif ($_SESSION['rol_idrol'] == 6) {
                    $this->view->registers = $this->model->getRegistersCompanyDateBus($company, $initial_date, $final_date, $bus);
                }
            }else{
                if ($_SESSION['rol_idrol'] == 1) {
                    $this->view->registers = $this->model->getRegistersDate($initial_date, $final_date);
                } elseif ($_SESSION['rol_idrol'] == 6) {
                    $this->view->registers = $this->model->getRegistersCompanyDate($company, $initial_date, $final_date);
                }
            }
        }
        $this->view->render('enlistment/listregister');
    }

    function listRegisterDateExp()
    {

        if (!isset($_SESSION)) {
            session_start();
        }

        $initial_date   = $_POST['initial_date'];
        $initial_hour   = $_POST['initial_hour'];
        $final_date     = $_POST['final_date'];
        $final_hour     = $_POST['final_hour'];

        $initial_date   = $initial_date." ".$initial_hour;
        $final_date     = $final_date." ".$final_hour;


        if (isset($_SESSION['iduser'])) {
            $company    = $_SESSION['company'];

            if (isset($_POST['bus']) AND $_POST['bus'] != "") {
                $bus   = $_POST['bus'];
                if ($_SESSION['rol_idrol'] == 1) {
                    $dataexp = $this->model->getRegistersDateBusExp($initial_date, $final_date, $bus);
                } elseif ($_SESSION['rol_idrol'] == 6) {
                    $dataexp = $this->model->getRegistersCompanyDateBusExp($company, $initial_date, $final_date, $bus);
                }
            }else{
                if ($_SESSION['rol_idrol'] == 1) {
                    $dataexp = $this->model->getRegistersDateExp($initial_date, $final_date);
                } elseif ($_SESSION['rol_idrol'] == 6) {
                    $dataexp = $this->model->getRegistersCompanyDateExp($company, $initial_date, $final_date);
                }
            }


            $titles = ['ID_REGISTRO', 'USUARIO', 'NOMBRE', 'BUS', 'FECHA', 'OBSERVACIÓN GENERAL', 'ITEM', 'OBSERVACIÓN ITEM'];
            
            // print_r($dataexp);
            $this->export2($dataexp, $titles, $initial_date);
        }

        $this->listRegister();
    }

    function detailRegister($param = null)
    {
        $idregister = $param[0];
        $this->view->dataregister = $this->model->getDetailRegister($idregister);
        $this->view->render('enlistment/detailregister');
    }

    function export2($data, $datakeys, $initial_date)
    {
        $data = json_decode(json_encode($data), true);

        $spread = new Spreadsheet();

        $spread
            ->getProperties()
            ->setCreator("TIC")
            ->setTitle('Excel creado con PhpSpreadSheet')
            ->setSubject('Excel de prueba')
            ->setDescription('Excel generado como prueba')
            ->setKeywords('PHPSpreadsheet')
            ->setCategory('Categoría de prueba');

        $sheet = $spread->getActiveSheet()->setAutoFilter('A1:H1');
        $sheet->getDefaultColumnDimension()->setWidth(15);
        $sheet->setTitle("Report");
        $sheet->fromArray($datakeys, NULL, 'A1');
        $sheet->fromArray($data, NULL, 'A2');
        $sheet->getStyle("A1:H1")->getFont()->setBold(true);

        $spread->setActiveSheetIndex(0);

        $writer = new Xlsx($spread);
        // $writer->save('reporte.xlsx');

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Alistamiento'.$initial_date.'.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spread, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

}

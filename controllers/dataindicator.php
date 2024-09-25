<?php

require_once 'libs/controller.php';
require_once 'models/maintenance.php';
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;

class DataIndicator extends Controller
{

    function __construct()
    {
        parent::__construct();
        $this->view->mensaje = "";
    }

    function render()
    {

        echo "Hola";

        // $this->view->render('data/index');
        
    }

    function updateIndicators()
    {

        $this->updateIndicator1();
        $this->updateIndicator3();
        $this->updateIndicator4();
        
    }

    function updateIndicator1()
    {

        $duration = $this->model->getDataInd1([]);
        
    }

    function updateIndicator3()
    {

        $duration = $this->model->getDataInd3([]);
        
    }

    function updateIndicator4()
    {

        $duration = $this->model->getDataInd4([]);
        
    }

    function loadProgrammedFront()
    {

       
        $mensaje = "";       

        $this->view->mensaje = $mensaje;

        $this->view->render('data/loadFront');
        
    }

    function loadProgrammed()
    {

        $name_document = $_FILES['filem']['name'];

        $namelocation = $_FILES['filem']['tmp_name'];

        $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($namelocation);

        /**  Create a new Reader of the type that has been identified  **/
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);

        /**  Load $inputFileName to a Spreadsheet Object  **/
        $spreadsheet = $reader->load($namelocation);

        /**  Convert Spreadsheet Object to an Array for ease of use  **/
        $schdeules = $spreadsheet->getActiveSheet()->toArray();

        $items = [];

        $control = 0;

        foreach ($schdeules as $single_schedule) { 

            if ($single_schedule['0'] == "Ot" OR $single_schedule['0'] == "OT") {
            } else {
                // echo $single_schedule[0]."<br>";
                $datos = new Maintenance();

                if(is_int($single_schedule[0])){

                    $datos->id_ot               = $single_schedule[0];
                    $datos->date_programmed     = date('Y-m-d', strtotime($single_schedule[1]));
                    $datos->rutina              = $single_schedule[2];
                    $datos->maintenance         = $single_schedule[3];
                    $datos->classification      = $single_schedule[4];
                    
                }else{
                    $control++;
                }
                

                if ($single_schedule[0] != "") {
                    array_push($items, $datos);
                }
            }
        }
        // echo "<pre>";
        // print_r($items);
        // echo "</pre>";
        $total = count($items);
        if($control == 0){

            if ($this->model->load($items)) {
                $mensaje =
                    '<div class="alert alert-success" role="alert">
                    Informacion almacenada con exito, se agregaron <b>' . $total . '</b> registros
                </div>';
                unset($_POST);
            } else {
                $mensaje =
                    '<div class="alert alert-danger" role="alert">
                    El documento no cumple con la estructura para el almacenamiento
                </div>';
            }
        }else{
            $mensaje =
                '<div class="alert alert-danger" role="alert">
                    Alguno de los ID de OT no son validos por favor verifique e intente de nuevo.
                </div>';
        }
        $this->view->mensaje = $mensaje;
        $this->view->render('data/loadFront');
    }
}

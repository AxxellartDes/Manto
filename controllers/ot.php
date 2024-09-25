<?php

require_once 'libs/controller.php';
require_once 'models/ots.php';

require_once 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class Ot extends Controller
{

    function __construct()
    {
        parent::__construct();
        $this->view->mensaje = "";
        $this->view->mensaje_1 = "";
        $this->view->mensaje_2 = "";
    }

    function render()
    {
        $this->view->render('almacen/select');
    }

    function inicio()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        if($_SESSION['rol_idrol'] == 1){
            $this->view->myorders = $this->model->listAllOrders();
            $this->view->render('almacen/all_orders_detail');
        }elseif($_SESSION['rol_idrol'] == 2){
            $this->view->myorders = $this->model->listMyOrders([
                'id_user'          => $_SESSION['iduser']
            ]);
            $this->view->render('almacen/my_orders');
        }elseif($_SESSION['rol_idrol'] == 3){
            $this->view->myorders = $this->model->listMyOrdersDelivery($_SESSION['company']);
            $this->view->render('almacen/my_orders_delivery');
        }
    }

    // function loginUser()
    // {

    //     $iduser = $_POST['user'];
    //     $pass   = $_POST['password'];

    //     $session = $this->model->login([
    //         'iduser' => $iduser,
    //     ]);
    //     if (isset($session->iduser)) {
    //         $password = $session->password;
    //         if (password_verify($pass, $password)) {
    //             session_start();
    //             $_SESSION['iduser']     = $session->iduser;
    //             $_SESSION['name']       = $session->name;
    //             $_SESSION['rol_idrol']  = $session->rol_idrol;
    //             $_SESSION['company']    = $session->company_idcompany;


    //             $this->view->childrens = $this->model->listChildrensRegister($_SESSION['iduser']);
    //             $this->view->render('home/index');
    //         } else {
    //             $mensaje_1 = '';
    //             $mensaje_2 = 'Contraseña incorrecta';
    //             $this->view->mensaje_1 = $mensaje_1;
    //             $this->view->mensaje_2 = $mensaje_2;
    //             $this->render();
    //         }
    //     } else {
    //         $mensaje_1 = 'Usuario no registrado';
    //         $mensaje_2 = '';
    //         $this->view->mensaje_1 = $mensaje_1;
    //         $this->view->mensaje_2 = $mensaje_2;
    //         $this->render();
    //     }
    // }

    function select()
    {

        if (!isset($_SESSION)) {
            session_start();
        }

        if (isset($_SESSION['iduser'])) {
            $company    = $_SESSION['company'];
            // $iduser     = $_SESSION['iduser'];

            if ($_SESSION['rol_idrol'] == 1) {
                $this->view->buses = $this->model->listBus();
            } elseif ($_SESSION['rol_idrol'] == 2 || $_SESSION['rol_idrol'] == 3) {
                $this->view->buses = $this->model->listBusByCompany($company);
            }

            $this->view->supplier = $this->model->listSupplier();
            $this->view->areamaintenance = $this->model->areaMaintenance();
            $this->view->itemsmaintenance = $this->model->itemsMaintenance();
            $this->view->kitmaintenance = $this->model->kitMaintenance();
        }
        $this->view->render('ot/select');
    }

    function select2()
    {

        if (!isset($_SESSION)) {
            session_start();
        }

        if (isset($_SESSION['iduser'])) {
            $company    = $_SESSION['company'];
            // $iduser     = $_SESSION['iduser'];

            if ($_SESSION['rol_idrol'] == 1) {
                $this->view->buses = $this->model->listBus();
            } elseif ($_SESSION['rol_idrol'] == 2 || $_SESSION['rol_idrol'] == 3) {
                $this->view->buses = $this->model->listBusByCompany($company);
            }

            $this->view->supplier = $this->model->listSupplier();
            $this->view->areamaintenance = $this->model->areaMaintenance();
            $this->view->itemsmaintenance = $this->model->itemsMaintenance();
            $this->view->kitmaintenance = $this->model->kitMaintenance();
        }
        $this->view->render('ot/select2');
    }

    function getdataot($param = null)
    {
        $id_ot = $param[0];

        $dataot = $this->model->getDataOtJson($id_ot);

        print $dataot;
    }

    function listtypeservice($param = null)
    {
        $id_supplier = $param[0];

        $dataservice = $this->model->listtypeserviceJson($id_supplier);

        print $dataservice;
    }

    function typeservice($param = null)
    {
        $id_type = $param[0];

        $datatypeservice = $this->model->typeserviceJson($id_type);

        print $datatypeservice;
    }

    function getdescriptionservice($param = null)
    {
        $ideventclass = $param[0];
        $data = $this->model->listKitJson($ideventclass);
        print $data;
    }

    function saveot()
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        setlocale(LC_TIME, "es_CO.UTF-8");
        date_default_timezone_set('America/Bogota');
        $date = date("Y-m-d H:i:s");

        $id_user = ($_SESSION['iduser']);

        $num_ot             = $_POST['numOt'];
        $idbus              = $_POST['bus'];

        $idbus = $this->model->getLastbusId($idbus);

        $suppliers          = $_POST['suppliers'];
        $company            = $_SESSION['company'];
        $totitems           = $_POST['totitems'];

        if($this->model->saveOt([
            'num_ot'            => $num_ot,
            'id_user_alm'       => $id_user,
            'idbus'             => $idbus,
            'suppliers'         => $suppliers,
            'company'           => $company,
            'date_register'     => $date,
            'status'            => 4
        ])){
            $id_ot_missing = $this->model->getLastOtId();
            print_r($totitems);
            for($i=1; $i<=$totitems; $i++){
                $type_service = "type_service".$i;
                $cantidad = "cantidad".$i;
                if(isset($_POST[$type_service])){
                    $type_service = $_POST[$type_service];
                    $cantidad = $_POST[$cantidad];
                    if($this->model->saveServiceOt([
                        'id_ot_missing'     => $id_ot_missing,
                        'id_type_service'   => $type_service,
                        'cantidad'          => $cantidad
                    ])){

                    }else{
                        echo "Error al guardar los items.";
                    }
                }
            }

            $this->view->mensaje = '
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                OT guardada con exito.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            ';

        }else {
            $this->view->mensaje = '
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                No fue posible guardar la OT. Contacte al administrador.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            ';
        }

        $this->view->myots = $this->model->listMyOt([
            'id_user'          => $id_user
        ]);
        $this->view->render('ot/my_ots');
    }

    function saveotcomp()
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        setlocale(LC_TIME, "es_CO.UTF-8");
        date_default_timezone_set('America/Bogota');
        $date = date("Y-m-d H:i:s");

        $id_user = ($_SESSION['iduser']);

        $ordenc             = $_POST['ordenc'];
        $desoc              = $_POST['desoc'];
        $valor              = $_POST['valordb'];
        $status             = $_POST['idstatus'];
        $id_ot_m            = $_POST['id_ot_m'];
        $created            = $_POST['created'];

        if($this->model->saveOtComp([
            'ordenc'            => $ordenc,
            'desoc'             => $desoc,
            'valor'             => $valor,
            'status'            => $status,
            'id_ot_m'           => $id_ot_m,
            'created'           => $created,
            'id_user'           => $id_user
        ])){

            $this->view->mensaje = '
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                OT actualizada con exito.
            </div>
            ';

        }else {
            $this->view->mensaje = '
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                No fue posible guardar la OT. Contacte al administrador.
            </div>
            ';
        }

        $this->view->myots = $this->model->listMyOtComp([
            'company'          => $_SESSION['company']
        ]);
        $this->view->render('ot/my_ots');
    }

    function listot()
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        $id_user = ($_SESSION['iduser']);

        $this->view->myots = $this->model->listMyOtSol([
            'id_user'          => $_SESSION['iduser']
        ]);
        $this->view->render('ot/my_ots');
    }

    function listotall()
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        $id_user = ($_SESSION['iduser']);

        $this->view->myots = $this->model->listMyOtAll([]);
        $this->view->render('ot/my_ots');
    }

    function listotcomp()
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        $id_user = ($_SESSION['iduser']);

        $this->view->myots = $this->model->listMyOtComp([]);
        $this->view->render('ot/my_ots');
    }

    function detailOt($param = null)
    {
        $idorder = $param[0];
        $dataorder = $this->model->getDetailOt($idorder);
        // echo($dataorder[0]->suppliersid);
        $this->view->dataorder = $dataorder;
        $this->view->dataserviceorder = $this->model->getDetailServiceOt([
            'idorder'   => $idorder,
            'idsupp'    => $dataorder[0]->suppliersid
        ]);
        $this->view->render('ot/detailot');
    }

    function detailOtComp($param = null)
    {
        $idorder = $param[0];
        $dataorder = $this->model->getDetailOt($idorder);
        $this->view->dataorder = $dataorder;
        $this->view->dataserviceorder = $this->model->getDetailServiceOt([
            'idorder'  => $idorder,
            'idsupp'    => $dataorder[0]->suppliersid
        ]);
        $this->view->idorder = $idorder;
        $this->view->render('ot/detailotcomp');
    }

    function verifydataoc($param = null)
    {
        $itemkey= $param[0];
        // $itemkey= strtoupper($itemkey);

        $itemkey = explode("¿",$itemkey);

        $dataoc = $this->model->verifyDataOcJson($itemkey[0], $itemkey[1]);
        print $dataoc;
    }

    function getdataoc($param = null)
    {
        $itemkey= $param[0];

        $dataoc2 = $this->model->getDataOcJson($itemkey);
        print $dataoc2;
    }

    function detailOrderAdmin($param = null)
    {
        $idorder = $param[0];
        $this->view->dataorder = $this->model->getDetailOrderDelivery($idorder);
        $this->view->alamcenista = $this->model->getUserDelivery($idorder);
        $this->view->dataorderelements1 = $this->model->getDetailOrderElements1($idorder);
        $this->view->dataorderelements2 = $this->model->getDetailOrderElements2($idorder);
        $this->view->dataorderelements3 = $this->model->getDetailOrderElements3($idorder);
        $this->view->render('almacen/detailorderadmin');
    }

    function deliveryOrderOld($param = null)
    {
        $idorder = $param[0];
        $this->view->dataorder = $this->model->getDetailOrderDelivery($idorder);
        $this->view->alamcenista = $this->model->getUserDelivery($idorder);
        $this->view->dataorderelements1 = $this->model->getDetailOrderElements1($idorder);
        $this->view->dataorderelements2 = $this->model->getDetailOrderElements2($idorder);
        $this->view->dataorderelements3 = $this->model->getDetailOrderElements3($idorder);
        $this->view->render('almacen/deliveryorderold');
    }

    function listOrderDelivery()
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        $company = ($_SESSION['company']);

        $this->view->myorders = $this->model->listMyOrdersDelivery($company);
        $this->view->render('almacen/my_orders_delivery');
    }

    function deliveryOrder($param = null)
    {
        $idorder = $param[0];
        $this->view->dataorder = $this->model->getDetailOrder($idorder);
        $this->view->alamcenista = $this->model->getUserDelivery($idorder);
        $this->view->dataorderelements1 = $this->model->getDetailOrderElements1($idorder);
        $this->view->dataorderelements2 = $this->model->getDetailOrderElements2($idorder);
        $this->view->dataorderelements3 = $this->model->getDetailOrderElements3($idorder);
        $this->view->render('almacen/deliveryorder');
    }

    function deliveryOrderDetail($param = null)
    {
        $idorder = $param[0];
        $this->view->dataorder = $this->model->getDetailOrderDelivery($idorder);
        $this->view->alamcenista = $this->model->getUserDelivery($idorder);
        $this->view->dataorderelements1 = $this->model->getDetailOrderElements1($idorder);
        $this->view->dataorderelements2 = $this->model->getDetailOrderElements2($idorder);
        $this->view->dataorderelements3 = $this->model->getDetailOrderElements3($idorder);
        $this->view->render('almacen/deliveryorderdetail');
    }

    function saveDeliveryOld()
    {

        if (!isset($_SESSION)) {
            session_start();
        }

        $idorder    = $_POST['idorder'];

        setlocale(LC_TIME, "es_CO.UTF-8");
        date_default_timezone_set('America/Bogota');
        $date = date("Y-m-d H:m");

        $target_dir = "resources/riesgos/";
        $target_file = $target_dir . basename($_FILES['imagen']['name']);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        // print_r($_FILES['imagen']["tmp_name"]);
        if($_FILES['imagen']["tmp_name"] != ""){
            $check = getimagesize($_FILES['imagen']["tmp_name"]);
            if($check !== false) {
                $uploadOk = 1;
            } else {
                $uploadOk = 0;
            }

            // Verifica si el archivo ya existe
            if (file_exists($target_file)) {
                $uploadOk = 0;
            }

            // Verifica el tamaño del archivo
            if ($_FILES['imagen']["size"] < 1000) {
                $uploadOk = 0;
                echo "size NO ".$_FILES['imagen']["size"];
            }

            // Permite ciertos formatos de archivo
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
                $uploadOk = 0;
                $mensaje = '
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Lo sentimos actualmente no podemos procesar ese tipo de formato de imagen.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                ';
            }
        }else{
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            $mensaje = '
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Lo siento, tu archivo no se ha podido subir.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                ';
                print_r($_FILES['imagen']["error"]);
        // si todo está bien, intenta subir el archivo
        } else {

            $time = $this->nameFile();
            $path = 'resources/evidence/';
            $name = $path . $time;
            $filenamePiece = constant('URL') . $name.".".$imageFileType;
            $name = $name.".".$imageFileType;

            //Verificar formato correcto y función correcta
            if($imageFileType == "jpg" || $imageFileType == "jpeg"){
                // Comprime la imagen antes de subirla
                $imagen = imagecreatefromjpeg($_FILES['imagen']["tmp_name"]);
                imagejpeg($imagen, $name, 50);
                imagedestroy($imagen);
            }elseif($imageFileType == "png"){
                // Comprime la imagen antes de subirla
                $imagen = imagecreatefrompng($_FILES['imagen']["tmp_name"]);
                imagepng($imagen, $name, 9);
                imagedestroy($imagen);
            }else{
                $mensaje = '
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Lo sentimos actualmente no podemos procesar ese tipo de formato de imagen.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                ';
            }

            if (file_exists($name)) {
                if ($this->model->updateDeliveryOld([
                        'idorder'           => $idorder,
                        'date'              => $date,
                        'evidence'          => $name,
                        'status'            => 3
                    ])) {

                        $mensaje = '<div class="alert alert-success" role="alert">
                            Solicitud creada con exito
                        </div>';
                    } else {
                        $mensaje = '<div class="alert alert-danger" role="alert">
                        Error al intentar guardar la solicitud, comunicate con el administrador
                    </div>';
                    }
            } else {
                $mensaje = '
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Lo siento, ha habido un error al subir tu archivo.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                ';
            }
        }

        $this->view->mensaje = $mensaje;
        $this->listmyorders();
    }

    function nameFile()
    {
        date_default_timezone_set('America/Bogota');
        $time = time();
        $nameprog = "evidence_" . date("dmY-His", $time);
        return $nameprog;
    }

    function search_item($param = null)
    {
        $itemkey= $param[0];
        $data = $this->model->listItemsSearchJson($itemkey);
        print $data;
    }

    function MyOtCompFil()
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        setlocale(LC_TIME, "es_CO.UTF-8");
        date_default_timezone_set('America/Bogota');
        $date = date("Y-m-d H:i:s");


        $id_status          = $_POST['id_status'];
        $date_initial       = $_POST['initial_date'];
        $date_final         = $_POST['final_date'];

        $myots = $this->model->listMyOtCompFil([
            'id_status'         => $id_status,
            'date_initial'      => $date_initial,
            'date_final'        => $date_final
        ]);

        $this->view->myots = $myots;

        $this->view->render('ot/my_ots');
    }

    function MyOtMantFil()
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        setlocale(LC_TIME, "es_CO.UTF-8");
        date_default_timezone_set('America/Bogota');
        $date = date("Y-m-d H:i:s");


        $id_status          = $_POST['id_status'];
        $date_initial       = $_POST['initial_date'];
        $date_final         = $_POST['final_date'];

        $myots = $this->model->listMyOtMantFil([
            'id_status'         => $id_status,
            'date_initial'      => $date_initial,
            'date_final'        => $date_final,
            'company'           => $_SESSION['company']
        ]);

        $this->view->myots = $myots;

        $this->view->render('ot/my_ots');
    }

    function exportMyOtCompFil()
    {

        if (!isset($_SESSION)) {
            session_start();
        }

        $id_status          = $_POST['id_status'];
        $date_initial       = $_POST['initial_date'];
        $date_final         = $_POST['final_date'];

        if ($myots = $this->model->listMyOtCompFil([
            'id_status'         => $id_status,
            'date_initial'      => $date_initial,
            'date_final'        => $date_final
            ])) {
                // echo "<pre>";
                // print_r($myots);
                // echo "</pre>";
                $this->model->deleteOtTemp([]);

                foreach($myots as $row){
                    $myot = new Ots();
                    $myot = $row;

                    // echo $myot->num_ot;
                    $this->model->saveOtTemp([
                        'id_ot_missing'         => $myot->id_ot_missing,
                        'num_ot'                => $myot->num_ot,
                        'id_bus'                => $myot->id_bus,
                        'ordenc'                => $myot->ordenc,
                        'created'               => $myot->created,
                        'status'                => $myot->status,
                        'createdoc'             => $myot->createdoc
                    ]);
                }

                $report = $this->model->listMyOtCompFilTemp([
                    'id_status'         => $id_status,
                    'date_initial'      => $date_initial,
                    'date_final'        => $date_final
                ]);
            $mensaje = '';
            $this->view->mensaje = $mensaje;
            $titles                 = ['ID', 'OT', 'Bus', 'OC', 'Creacion Solicitud', 'Estado', 'Creacion OC'];

            $this->export2($report, $titles);

            $this->view->myots = $this->model->listMyOtComp([
                'company'          => $_SESSION['company']
            ]);
            $this->view->render('ot/my_ots');
        } else {
            $mensaje = '<div class="alert alert-danger" role="alert">
                    No se encontro ningun registro con el criterio de busqueda seleccionado
                </div>';
            // $payment = "";
            // $mensaje ='';
            $this->view->mensaje = $mensaje;
            $this->view->myots = $this->model->listMyOtComp([
                'company'          => $_SESSION['company']
            ]);
            $this->view->render('ot/my_ots');
        }
        // $this->listRequest();
    }

    function exportMyOtMantFil()
    {

        if (!isset($_SESSION)) {
            session_start();
        }

        $id_status          = $_POST['id_status'];
        $date_initial       = $_POST['initial_date'];
        $date_final         = $_POST['final_date'];

        if ($myots = $this->model->listMyOtMantFil([
            'id_status'         => $id_status,
            'date_initial'      => $date_initial,
            'date_final'        => $date_final,
            'company'           => $_SESSION['company']
            ])) {
                // echo "<pre>";
                // print_r($myots);
                // echo "</pre>";
                $this->model->deleteOtTemp([]);

                foreach($myots as $row){
                    $myot = new Ots();
                    $myot = $row;

                    // echo $myot->num_ot;
                    $this->model->saveOtTemp([
                        'id_ot_missing'         => $myot->id_ot_missing,
                        'num_ot'                => $myot->num_ot,
                        'id_bus'                => $myot->id_bus,
                        'ordenc'                => $myot->ordenc,
                        'created'               => $myot->created,
                        'status'                => $myot->status,
                        'createdoc'             => $myot->createdoc
                    ]);
                }

                $report = $this->model->listMyOtCompFilTemp([
                    'id_status'         => $id_status,
                    'date_initial'      => $date_initial,
                    'date_final'        => $date_final
                ]);
            $mensaje = '';
            $this->view->mensaje = $mensaje;
            $titles                 = ['ID', 'OT', 'Bus', 'OC', 'Creacion Solicitud', 'Estado', 'Creacion OC'];

            $this->export2($report, $titles);

            $this->view->myots = $this->model->listMyOtComp([
                'company'          => $_SESSION['company']
            ]);
            $this->view->render('ot/my_ots');
        } else {
            $mensaje = '<div class="alert alert-danger" role="alert">
                    No se encontro ningun registro con el criterio de busqueda seleccionado
                </div>';
            // $payment = "";
            // $mensaje ='';
            $this->view->mensaje = $mensaje;
            $this->view->myots = $this->model->listMyOtComp([
                'company'          => $_SESSION['company']
            ]);
            $this->view->render('ot/my_ots');
        }
        // $this->listRequest();
    }

    function export2($data, $datakeys)
    {

        $spread = new Spreadsheet();


        $spread
            ->getProperties()
            ->setCreator("TIC")
            ->setTitle('Excel creado con PhpSpreadSheet')
            ->setSubject('Excel de prueba')
            ->setDescription('Excel generado como prueba')
            ->setKeywords('PHPSpreadsheet')
            ->setCategory('Categoría de prueba');

        $sheet = $spread->getActiveSheet()->setAutoFilter('A1:G1');
        $sheet->getDefaultColumnDimension()->setWidth(15);
        $sheet->setTitle("Report");
        $sheet->fromArray($datakeys, NULL, 'A1');
        $sheet->fromArray($data, NULL, 'A2');
        $sheet->getStyle("A1:G1")->getFont()->setBold(true);

        $spread->setActiveSheetIndex(0);

        $writer = new Xlsx($spread);
        $writer->save('reporte.xlsx');

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ReporDia.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spread, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

    function search_service($param = null)
    {
        $itemkey= $param[0];
        $itemkey= strtoupper($itemkey);

        $itemkey = explode("¿",$itemkey);

        $data = $this->model->listServiceSearchJson($itemkey[0], $itemkey[1]);
        print $data;
    }
}

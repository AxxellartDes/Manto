<?php

require_once 'libs/controller.php';
require 'vendor/autoload.php';


class Almacen extends Controller
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
        $iduser = $_SESSION['iduser']; 
        // print_r($_SESSION['rol_idrol']);
        if($_SESSION['rol_idrol'] == 1){
            $this->view->myorders = $this->model->listAllOrders();
            $this->view->render('almacen/all_orders_detail');
        }elseif($_SESSION['rol_idrol'] == 2){
            $this->view->myorders = $this->model->listMyOrders([
                'id_user'          => $_SESSION['iduser']
            ]);
            $this->view->render('almacen/my_orders');
        }elseif($_SESSION['rol_idrol'] == 3){
            $this->view->myorders = $this->model->listMyOrdersDelivery($_SESSION['yard']);
            $this->view->render('almacen/my_orders_delivery'); 
        }elseif($_SESSION['rol_idrol'] == 5){
            $this->view->myots = $this->model->listMyOtComp([
                'company'          => $_SESSION['company']
            ]);
            $this->view->render('ot/my_ots');
        }elseif($_SESSION['rol_idrol'] == 6){
            $this->view->myots = $this->model->listMyOtSol([
                'iduser'          => $iduser
            ]);
            $this->view->render('ot/my_ots');
        }elseif($_SESSION['rol_idrol'] == 7){

            $this->view->render('data/loadFront');
        }
    }

    function listOrderAlm()
    {
        $store = $_POST['store'];

        session_start();
        $_SESSION['yard'] = $_POST['store'];
        
        $this->view->myorders = $this->model->listMyOrdersDelivery($store);
        $this->view->render('almacen/my_orders_delivery'); 
        
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

    function loginUserUrl()
    {

        $iduser = $_GET['user'];
        $pass   = $_GET['pass'];

        $session = $this->model->login([ 
            'iduser' => $iduser,
        ]);
        if (isset($session->iduser)) {
            $password = $session->password; 
            if (password_verify($pass, $password)) {
                session_start();
                $_SESSION['iduser']     = $session->iduser;
                $_SESSION['name']       = $session->name;
                $_SESSION['rol_idrol']  = $session->rol_idrol;
                $_SESSION['company']    = $session->company_idcompany;


                $this->view->childrens = $this->model->listChildrensRegister($_SESSION['iduser']);
                $this->view->render('home/index');
            } else {
                $mensaje_1 = '';
                $mensaje_2 = 'Contraseña incorrecta';
                $this->view->mensaje_1 = $mensaje_1;
                $this->view->mensaje_2 = $mensaje_2;
                $this->render();
            }
        } else {
            $mensaje_1 = 'Usuario no registrado';
            $mensaje_2 = '';
            $this->view->mensaje_1 = $mensaje_1;
            $this->view->mensaje_2 = $mensaje_2;
            $this->render();
        }
    }

    function select()
    {

        if (!isset($_SESSION)) {
            session_start();
        }

        // $this->model->ActListItems();

        if (isset($_SESSION['iduser'])) {
            $company    = $_SESSION['company'];
            // $iduser     = $_SESSION['iduser'];

            if ($_SESSION['rol_idrol'] == 1) {
                $this->view->buses = $this->model->listBus();
            } elseif ($_SESSION['rol_idrol'] == 2 || $_SESSION['rol_idrol'] == 3) {
                $this->view->buses = $this->model->listBusByCompany($company);
            }

            // $data = $this->model->listKitJson(1);
            // echo "<pre>";
            // print_r($data);
            // echo "</pre>";


            $this->view->maintenance = $this->model->listMaintenance();
            $this->view->areamaintenance = $this->model->areaMaintenance();
            $this->view->itemsmaintenance = $this->model->itemsMaintenance();
            $this->view->kitmaintenance = $this->model->kitMaintenance();
        }
        $this->view->render('almacen/select');
    }

    function listtitlekit($param = null)
    {
        $type_bus = $param[0];
        $bus = $this->model->typeBus($type_bus);
        // $bus = 1;
        if($bus == 1){
            $datakit = $this->model->listKitTitleArtJson();
        }else{
            $datakit = $this->model->listKitTitleBiArtJson();
        }
        print $datakit;
    }

    function getdataot($param = null)
    {
        $id_ot = $param[0];

        $dataot = $this->model->getDataOtJson($id_ot);

        print $dataot;
    }

    function list_kit($param = null)
    {
        $ideventclass = $param[0];
        $data = $this->model->listKitJson($ideventclass);
        print $data;
    }

    function list_kit_pedido($param = null)
    {
        $ideventclass = $param[0];
        $data = $this->model->listKitPedidoJson($ideventclass);
        print $data;
    }

    function list_item_search($param = null)
    {
        $ideventclass = $param[0];
        $ideventclass = strtoupper($ideventclass);

        $data = $this->model->listItemSearchJson($ideventclass);
        print $data;
    }

    function saveorder()
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        $id_user = ($_SESSION['iduser']);

        $num_ot         = $_POST['numOt'];
        $idbus          = $_POST['bus'];
        $area           = $_POST['area'];
        $kit_title      = $_POST['kit_title'];
        $company        = $_POST['company'];

        if($area == 'CAR'){
            $area = 1;
        }elseif($area == 'ELE'){
            $area = 2;
        }elseif($area == 'MEC'){
            $area = 3;
        }

        if($company == 2){
            $store          = $_POST['store'];
        }else{
            $store          = $_POST['company'];
        }
        

        if(isset($_POST['totitems'])){
            $totitems         = $_POST['totitems']+1;
        }else{
            $totitems = 0;
        }

        if(isset($_POST['numrep'])){
            $numrep         = $_POST['numrep']+1;
        }else{
            $numrep = 0;
        }

        if(isset($_POST['numrep2'])){
            $numrep2         = $_POST['numrep2']+1;
        }else{
            $numrep2 = 0;
        }

        $idbus = $this->model->getLastbusId($idbus);

        if($this->model->saveOrder([
            'num_ot'            => $num_ot,
            'id_user_order'     => $id_user,
            'idbus'             => $idbus,
            'area'              => $area,
            'kit_title'         => $kit_title,
            'company'           => $company,
            'store'             => $store,
            'status'            => 1
        ])){
            $id_order = $this->model->getLastOrderId(); 
            for($i=0; $i<$numrep; $i++){
                $item_kit = "kit_".$i;
                $item_kit_cant = "kit_cant".$i;
                if(isset($_POST[$item_kit])){
                    $item_kit = $_POST[$item_kit]; 
                    $item_kit_cant = $_POST[$item_kit_cant]; 
                    if($this->model->saveItemOrder([
                        'id_order'          => $id_order,
                        'id_item'           => $item_kit,
                        'cantidad'          => $item_kit_cant,
                        'type_order'        => 1
                    ])){

                    }else{
                        echo "Error al guardar los items.";
                    }
                }
            }
            for($i=0; $i<$numrep2; $i++){
                $item_kit = "kit2_".$i;
                $item_kit_cant = "kit2_cant".$i;
                if(isset($_POST[$item_kit])){
                    $item_kit = $_POST[$item_kit]; 
                    $item_kit_cant = $_POST[$item_kit_cant]; 
                    if($this->model->saveItemOrder([
                        'id_order'          => $id_order,
                        'id_item'           => $item_kit,
                        'cantidad'          => $item_kit_cant,
                        'type_order'        => 2
                    ])){

                    }else{
                        echo "Error al guardar los items-pedido.";
                    }
                }
            }

            // print_r($totitems);
            for($i=0; $i<=$totitems; $i++){
                $itemsid = "itemscod".$i;
                // $itemsdes = "itemsdes".$i;
                $itemscant = "itemscant".$i;
                if(isset($_POST[$itemsid]) and $_POST[$itemsid]!= ""){
                    $itemsid = $_POST[$itemsid]; 
                    $itemscant = $_POST[$itemscant]; 
                    if($this->model->saveItemOrder([
                        'id_order'          => $id_order,
                        'id_item'           => $itemsid,
                        'cantidad'          => $itemscant,
                        'type_order'        => 3
                    ])){

                    }else{
                        echo "Error al guardar los items-pedido.";
                    }
                }
            }

            $this->view->mensaje = '
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Solicitud guardada con exito.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            ';

        }else {
            $this->view->mensaje = '
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                No fue posible guardar la solicitud. Contacte al administrador.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            ';
        }

        $this->view->myorders = $this->model->listMyOrders([
            'id_user'          => $id_user
        ]);
        $this->view->render('almacen/my_orders');
    }

    function listmyorders()
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        $id_user = ($_SESSION['iduser']);

        $this->view->myorders = $this->model->listMyOrders([
            'id_user'          => $_SESSION['iduser']
        ]);
        $this->view->render('almacen/my_orders');
    }

    function detailOrder($param = null)
    {
        $idorder = $param[0];
        $this->view->dataorder = $this->model->getDetailOrderDelivery($idorder);
        $this->view->alamcenista = $this->model->getUserDelivery($idorder);
        $this->view->dataorderelements1 = $this->model->getDetailOrderElements1($idorder);
        $this->view->dataorderelements2 = $this->model->getDetailOrderElements2($idorder);
        $this->view->dataorderelements3 = $this->model->getDetailOrderElements3($idorder);
        $this->view->render('almacen/detailorder');
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

    function saveDelivery()
    {

        if (!isset($_SESSION)) {
            session_start();
        }

        $idorder    = $_POST['idorder'];
        $novelty    = $_POST['novelty'];
        $id_user    = $_SESSION['iduser'];

        if(isset($_POST['confirm'])){
            $confirm    = 1;
        }else{
            $confirm    = 0;
        }
        
        setlocale(LC_TIME, "es_CO.UTF-8");
        date_default_timezone_set('America/Bogota');
        $date = date("Y-m-d H:i:s");
        
        $target_dir = "resources/riesgos/";
        $target_file = $target_dir . basename($_FILES['imagen']['name']);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        // print_r($_FILES);
        $galery = "";
        $infometa = "";
        // Ruta temporal del archivo subido (desde $_FILES)
        $ruta_temporal = $_FILES["imagen"]["tmp_name"];
        // print_r($ruta_temporal);
        // Leer los metadatos Exif
        // if(exif_read_data($ruta_temporal, "EXIF")){
        //     // echo "entra aqui";
        //     $metadatos = exif_read_data($ruta_temporal, "EXIF");
        // }else{
        //     // echo "No entra aquí";
        //     $metadatos = [];
        // }
        // echo $metadatos['DateTime']." tiempo actual - ".$date;
        // Mostrar los metadatos
        // echo "<pre>";
        // print_r($metadatos);
        // echo "</pre>";
        // print_r($_FILES['imagen']["tmp_name"]);
        //-----------------------------------------
        // $date1 = date($date, (strtotime ("-1 Minute")));
        // $date2 = date($date, (strtotime ("+1 Minute")));
        // // print_r($metadatos);
        // if($metadatos == ""){
        //     $infometa = "metadatos vacio";
        //     $metadatos['DateTime'] = 0;
        // }elseif(empty($metadatos)){
        //     $infometa = "metadatos vacio";
        //     $metadatos['DateTime'] = 0;
        // }elseif(isset($metadatos['THUMBNAIL'])){
        //     echo "<pre>";
        //     // print_r($metadatos);
        //     echo "</pre>";
        //     $infometa = "metadatos con THUMBNAIL";
        //     $metadatos['DateTime'] = 0;
        // }elseif(!isset($metadatos['DateTime'])){
        //     echo "<pre>";
        //     // print_r($metadatos);
        //     echo "</pre>";
        //     $infometa = "metadatos con datos, sin datetime";
        //     $metadatos['DateTime'] = $date;
        // }else{
        //     echo "<pre>";
        //     // print_r($metadatos);
        //     echo "</pre>";
        //     $infometa = "datetime exist";
        // }

        // if($metadatos['DateTime'] >= $date1 and $metadatos['DateTime'] <= $date2){
        if(1==1){
            
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
            // $galery = "bien cumple ".$metadatos['DateTime']." initial_".$date1." enddate_".$date2;
        }else{
            $uploadOk = 0;
            $galery = "La evidencia subida no cumple los requerimientos, por favor sube la foto directamente desde la camara del movil. ".$metadatos['DateTime'] ." ---". $date1 ."---". $date2;
        }
        

        if ($uploadOk == 0) {
            $mensaje = '
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Lo siento, tu archivo no se ha podido subir.<br>'.$galery.'__'.$infometa.'
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                ';
                // print_r($_FILES['imagen']["error"]);
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
                if ($this->model->updateDelivery([
                        'idorder'           => $idorder,
                        'date'              => $date,
                        'novelty'           => $novelty,
                        'id_user'           => $id_user,
                        'evidence'          => $name,
                        'confirm'           => $confirm,
                        'status'            => 2
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
        $this->listOrderDelivery();
    }

    function saveDeliveryOld()
    {

        if (!isset($_SESSION)) {
            session_start();
        }

        $idorder    = $_POST['idorder'];

        if(isset($_POST['confirm'])){
            $confirm    = 1;
        }else{
            $confirm    = 0;
        }

        setlocale(LC_TIME, "es_CO.UTF-8");
        date_default_timezone_set('America/Bogota');
        $date = date("Y-m-d H:m");

        $target_dir = "resources/riesgos/";
        $target_file = $target_dir . basename($_FILES['imagen']['name']);
        $uploadOk = 1;
        $infometa = "";
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        // print_r($_FILES['imagen']["tmp_name"]);
        $galery = "";
        // Ruta temporal del archivo subido (desde $_FILES)
        $ruta_temporal = $_FILES["imagen"]["tmp_name"];
        // print_r($ruta_temporal);
        // Leer los metadatos Exif
        // if(exif_read_data($ruta_temporal, "EXIF")){
        //     // echo "entra aqui";
        //     $metadatos = exif_read_data($ruta_temporal, "EXIF");
        // }else{
        //     // echo "No entra aquí";
        //     $metadatos = [];
        // }

        //Aqui validacion ------------------------------------------------------------------------
        // $date1 = date($date, (strtotime ("-1 Minute")));
        // $date2 = date($date, (strtotime ("+1 Minute")));
        // // print_r($metadatos);
        // if($metadatos == ""){
        //     $infometa = "metadatos vacio";
        //     $metadatos['DateTime'] = 0;
        // }elseif(empty($metadatos)){
        //     $infometa = "metadatos vacio";
        //     $metadatos['DateTime'] = 0;
        // }elseif(isset($metadatos['THUMBNAIL'])){
        //     echo "<pre>";
        //     // print_r($metadatos);
        //     echo "</pre>";
        //     $infometa = "metadatos con THUMBNAIL";
        //     $metadatos['DateTime'] = 0;
        // }elseif(!isset($metadatos['DateTime'])){
        //     echo "<pre>";
        //     // print_r($metadatos);
        //     echo "</pre>";
        //     $infometa = "metadatos con datos, sin datetime";
        //     $metadatos['DateTime'] = $date;
        // }else{
        //     echo "<pre>";
        //     // print_r($metadatos);
        //     echo "</pre>";
        //     $infometa = "datetime exist";
        // }

        // if($metadatos['DateTime'] >= $date1 and $metadatos['DateTime'] <= $date2){
            if(1==1){

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
        // $galery = "bien cumple ".$metadatos['DateTime']." initial_".$date1." enddate_".$date2;
        }else{
            $uploadOk = 0;
            $galery = "La evidencia subida no cumple los requerimientos, por favor sube la foto directamente desde la camara del movil. ".$metadatos['DateTime'] ." ---". $date1 ."---". $date2;
        }

        if ($uploadOk == 0) {
            $mensaje = '
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Lo siento, tu archivo no se ha podido subir.<br>'.$galery.'__'.$infometa.'
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                ';
                // print_r($_FILES['imagen']["error"]);
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
                        'status'            => 3,
                        'confirm'           => $confirm
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
        $itemkey= strtoupper($itemkey);

        $itemkey = explode("¿",$itemkey);

        $data = $this->model->listItemsSearchJson($itemkey[0], $itemkey[1]);
        print $data;
    }

    public function listItemGroup()
    {
        $items = [];

        try {
            $query = $this->db->connect()->prepare('
            SELECT * FROM enlistment_cluster
            ');
            $query->execute([]);

            while ($row = $query->fetch()) {
                $item = new ItemsGroups();

                $item->iditem_group = $row['iditem_group'];
                $item->description  = $row['description'];

                array_push($items, $item);
            }

            return $items;
        } catch (PDOException $e) {
            echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return [];
        }
    }
}

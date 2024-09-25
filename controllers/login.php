<?php

require_once 'libs/controller.php';

class Login extends Controller
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
        $this->view->render('login/index');
    }

    function loginUser()
    {

        $iduser = $_POST['user'];
        $pass   = $_POST['password'];

        if(isset($_POST['mensaje'])){
            $this->view->mensaje = '<div class="alert alert-success" role="alert">
            Contraseña actualizada exitosamente
            </div>';
        }

        $session = $this->model->login([
            'iduser' => $iduser,
        ]);
        if (isset($session->iduser)) {
            $password = $session->password;
            if (password_verify($pass, $password)) {
                ini_set("session.cookie_lifetime","7200");
                ini_set("session.gc_maxlifetime","7200");
                session_start();
                $_SESSION['iduser']         = $session->iduser;
                $_SESSION['name']           = $session->name;
                $_SESSION['rol_idrol']      = $session->rol_idrol;
                $_SESSION['rol']            = strtoupper($session->rol);
                $_SESSION['company']        = $session->company_idcompany;
                $_SESSION['update_pass']    = $session->update_pass;

                if($session->update_pass == 0){
                    header("Location: ".constant('URL')."password");
                    exit();
                }else{
                    if($_SESSION['rol_idrol'] == 1){
                    $this->view->myorders = $this->model->listAllOrders();
                    $this->view->render('almacen/all_orders_detail');
                    }elseif($_SESSION['rol_idrol'] == 2){
                        $this->view->myorders = $this->model->listMyOrders([
                            'id_user'          => $iduser
                        ]);
                        $this->view->render('almacen/my_orders');
                    }elseif($_SESSION['rol_idrol'] == 3){
                        $this->view->myorders = $this->model->listMyOrdersDelivery($_SESSION['company']);
                        // $this->view->render('almacen/my_orders_delivery'); 
                        $this->view->render('almacen/select_company'); 
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

    function login1()
    {

        $iduser             = $_POST['id_user'];
        $mailcorp           = $_POST['mailcorp'];
        $company            = $_POST['company'];
        $name               = $_POST['name'];
        $idprofile          = $_POST['idprofile'];
        $position_main      = $_POST['position_main'];

        $session = $this->model->login([
            'iduser' => $iduser,
        ]);
        if (isset($session->iduser)) {
            if (1==1) {
                session_start();
                $_SESSION['iduser']         = $session->iduser;
                $_SESSION['name']           = $name;
                $_SESSION['rol_idrol']      = $idprofile;
                $_SESSION['rol']            = strtoupper($session->rol);
                $_SESSION['company']        = $company;
                $_SESSION['update_pass']    = $session->update_pass;

                if($session->update_pass == 0){
                    header("Location: ".constant('URL')."password");
                    exit();
                }else{
                    if($_SESSION['rol_idrol'] == 1){
                        $this->view->myorders = $this->model->listAllOrders();
                        $this->view->render('almacen/all_orders_detail');
                    }elseif($_SESSION['rol_idrol'] == 2){
                        $this->view->myorders = $this->model->listMyOrders([
                            'id_user'          => $iduser
                        ]);
                        $this->view->render('almacen/my_orders');
                    }elseif($_SESSION['rol_idrol'] == 3){
                        $this->view->myorders = $this->model->listMyOrdersDelivery($_SESSION['company']);
                        $this->view->render('almacen/my_orders_delivery'); 
                    }elseif($_SESSION['rol_idrol'] == 6){
                        $this->view->myots = $this->model->listMyOt([
                            'id_user'          => $_SESSION['iduser']
                        ]);
                        $this->view->render('ot/my_ots');
                    }
                }
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

    function logout()
    {
        session_start();

        session_destroy();
        $this->view->render('login/index');
    }

    function sql()
    {
        $data_eam           = $this->model->getDataEam();
        echo "<pre>";
        print_r($data_eam);
        echo "<pre>";
    }
}

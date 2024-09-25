<?php

require_once 'libs/controller.php';
// require 'vendor/autoload.php';

class Delivery extends Controller
{

    function __construct()
    {
        parent::__construct();
        $this->view->mensaje = "";
    }

    function render()
    {
        $numGift = $this->model->numGift();
        $this->view->numGift = $numGift;
        $this->view->render('delivery/gifts');
    }

    function viewhandles()
    {
        $numhandles = $this->model->numHandles();
        $this->view->numhandles = $numhandles;
        $this->view->render('delivery/handles');
    }

    function viewanchetas()
    {
        $numanchetas = $this->model->numAnchetas();
        $this->view->numanchetas = $numanchetas;
        $this->view->render('delivery/anchetas');
    }

    function search()
    {
        $iduser = $_POST['iduser'];
        $user = $this->model->searchById($iduser);
        if ($user->idusers != "") {
            $mensaje = '';
            $this->view->company = $this->model->listCompany();

            // $status = $this->model->listStatus();
            // $this->view->status = $status;
            $this->view->mensaje = $mensaje;
            // $user_quantity = $this->model->totalAdm();
            // $this->view->user_quantity = $user_quantity;
            $this->view->user = $user;
        } else {
            $this->view->mensaje = '
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
            No se encontro al usuario
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            ';
        }
        $numanchetas = $this->model->numAnchetas();
        $this->view->numanchetas = $numanchetas;
        $this->view->render('delivery/anchetas');
    }

    function searchhandles()
    {
        $iduser = $_POST['iduser'];
        $user = $this->model->searchByIdhandles($iduser);
        $validation = $this->model->validateUser($iduser);
        if ($user->idusers != "") {
            $this->view->user = $user;
            $this->view->validation = $validation;
        } else {
            $this->view->mensaje = '
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
            No se encontro al usuario
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            ';
        }
        $numhandles = $this->model->numHandles();
        $this->view->numhandles = $numhandles;
        $this->view->render('delivery/handles');
    }

    function searchgift()
    {
        $iduser = $_POST['iduser'];
        $user = $this->model->searchByIdGift($iduser);
        $children = $this->model->searchByIdChildren($iduser);
        $validation = $this->model->validatedelivery($iduser);
        if ($user->idusers != "") {
            $this->view->user = $user;
            $this->view->children = $children;
            $this->view->validation = $validation;
            
        } else {
            $this->view->mensaje = '
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
            No se encontro al usuario
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            ';
        }
        $numGift = $this->model->numGift();
        $this->view->numGift = $numGift;
        $this->view->render('delivery/gifts');
    }

    function select($param = null)
    {
        $idchildren = $param[0];
        $this->view->id = $idchildren;
        $this->view->giftsTypes = $this->model->listGiftTypes();
        $this->view->gifts = $this->model->listGift();
        $this->view->render('gift/select');
    }

    function save()
    {
        // print_r($_POST);
        if (!isset($_SESSION)) {
            session_start();
        }

        if ($this->model->saveDelivery([
            'iduser'        => $_POST['iduser'],
            'installations' => $_POST['company'],
            'manager'       => $_SESSION['iduser'],
            'comment'       => $_POST['comment'],
            'status'        => 3
        ])) {

            $this->view->mensaje = '
                <div class="alert alert-secondary alert-dismissible fade show" role="alert">
                Seleccion almacenada con exito
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                ';
        } else {
            $this->view->mensaje = '
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Error al almacenar la informacion
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                ';
        }
        $numanchetas = $this->model->numAnchetas();
        $this->view->numanchetas = $numanchetas;
        $this->view->render('delivery/anchetas');
    }

    function savehandles()
    {
        // print_r($_POST);
        if (!isset($_SESSION)) {
            session_start();
        }

        if ($this->model->saveDeliveryHandles([
            'iduser'        => $_POST['iduser'],
            'manager'       => $_SESSION['iduser'],
            'handles'       => $_POST['numhandles'],
            'comment'       => $_POST['comment']
        ])) {

            $this->view->mensaje = '
                <div class="alert alert-secondary alert-dismissible fade show" role="alert">
                Seleccion almacenada con exito
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                ';
        } else {
            $this->view->mensaje = '
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Error al almacenar la informacion
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                ';
        }
        $numhandles = $this->model->numHandles();
        $this->view->numhandles = $numhandles;
        $this->view->render('delivery/handles');
    }

    function savegift()
    {
        // print_r($_POST);
        if (!isset($_SESSION)) {
            session_start();
        }

        if ($this->model->saveDeliveryGift([
            'iduser'        => $_POST['iduser'],
            'manager'       => $_SESSION['iduser'],
            'comment'       => $_POST['comment']
        ])) {

            $this->view->mensaje = '
                <div class="alert alert-secondary alert-dismissible fade show" role="alert">
                Seleccion almacenada con exito
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                ';
        } else {
            $this->view->mensaje = '
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Error al almacenar la informacion
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                ';
        }

        $numGift = $this->model->numGift();
        $this->view->numGift = $numGift;
        $this->render();
        // $this->view->render('gift/select');
    }
}

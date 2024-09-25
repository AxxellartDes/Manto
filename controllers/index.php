<?php

require_once 'libs/controller.php';

class Index extends Controller
{

    function __construct()
    {
        parent::__construct();
        $this->view->mensaje = "";
    }

    function render()
    {
        if (!isset($_SESSION)) {
            session_start();
        }


        //$this->view->childrens = $this->model->listChildrensRegister($_SESSION['iduser']);
        $this->view->render('home/index');

    }

    function salir()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        session_destroy();
        $this->render();
    }
}

<?php

require_once 'libs/controller.php';

class Errores extends Controller{
    
    function __construct()
    {
        parent::__construct();
        $this->view->mensaje = "Error al cargar el controlador";
        $this->view->render('errores/index');
    }
}

?>
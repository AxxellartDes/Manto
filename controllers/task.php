<?php

require_once 'libs/controller.php';
require 'vendor/autoload.php';


class Task extends Controller
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
        if (!isset($_SESSION)) {
            session_start();
        }

        if (isset($_SESSION['iduser'])) {
            $company    = $_SESSION['company'];
            $id_user    = $_SESSION['iduser'];

            $id_ocupation = $this->model->idOcupation($id_user);
            $this->view->buses = $this->model->listBusByCompany($company);
            $this->view->tasktype = $this->model->listTaskType();

            $this->view->tasks = $this->model->listTasks($id_ocupation);
        }
        $this->view->render('task/select');
    }

    function select()
    {

        if (!isset($_SESSION)) {
            session_start();
        }

        if (isset($_SESSION['iduser'])) {
            $company    = $_SESSION['company'];
            $id_user    = $_SESSION['iduser'];
            // $iduser     = $_SESSION['iduser'];
            $this->view->buses = $this->model->listBusByCompany($company);

            $id_ocupation = $this->model->idOcupation($id_user);

            // $this->view->tasks = $this->model->listMyOrders($id_ocupation);
        }
        $this->view->render('almacen/select');
    }

    function search_task($param = null)
    {
        $param1 = $param[0];
        $param2 = explode(",", $param1);
        $taskkey = $param2[0];
        $tasktype = $param2[1];

        $data = $this->model->listTaskSearchJson($taskkey);
        print $data;
    }

    function saveTask()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $id_user    = $_SESSION['iduser'];

        setlocale(LC_TIME, "es_CO.UTF-8");
        date_default_timezone_set('America/Bogota');
        $date_initial = date("Y-m-d H:i:s");

        $company        = $_POST['company'];
        $task_plan      = $_POST['task_plan'];
        $bus            = $_POST['bus'];


        $type_task      = $this->model->getTaskType($task_plan);

        $this->model->taskSave([
            'id_user'       => $id_user,
            'date_initial'  => $date_initial,
            'company'       => $company,
            'id_task'       => $task_plan,
            'bus'           => $bus,
            'type_task'     => $type_task
        ]);

        $registers_On = $this->model->myRegistersOn($id_user);

        $this->view->registers_On   = $registers_On;

        $this->view->render('task/my_registers_on');
        
    }

    function endTask($param = null)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $id_user    = $_SESSION['iduser'];

        $id_register = $param[0];

        setlocale(LC_TIME, "es_CO.UTF-8");
        date_default_timezone_set('America/Bogota');
        $date_final = date("Y-m-d H:i:s");

        $this->model->taskEnd([
            'id_register'       => $id_register,
            'date_final'        => $date_final
        ]);

        $registers_Off = $this->model->myRegistersOff($id_user);

        $this->view->registers_Off   = $registers_Off;

        $this->view->render('task/my_registers_off');
        
    }

    function myRegistersOn()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $id_user    = $_SESSION['iduser'];

        $registers_On = $this->model->myRegistersOn($id_user);

        $this->view->registers_On   = $registers_On;

        $this->view->render('task/my_registers_on');
        
    }

    function myRegistersOff()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $id_user    = $_SESSION['iduser'];

        $registers_Off = $this->model->myRegistersOff($id_user);

        $this->view->registers_Off   = $registers_Off;

        $this->view->render('task/my_registers_off');
        
    }
}

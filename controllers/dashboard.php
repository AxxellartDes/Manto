<?php

require_once 'libs/controller.php';
// require 'vendor/autoload.php';

class Dashboard extends Controller
{

    function __construct()
    {
        parent::__construct();
        $this->view->mensaje = "";
    }

    function render()
    {

        // $this->view->program        = $this->model->last_date_prog();
        // $this->view->training       = $this->model->last_date_train();
        // $this->view->capacitation   = $this->model->training();
        // $this->view->license        = $this->model->license();
        // $this->view->inability      = $this->model->inability();
        // $this->view->break          = $this->model->break();
        // $this->view->available      = $this->model->available();
        // $this->view->programmed     = $this->model->programmed();
        // $this->view->loadTime       = $this->model->loadTime();
        // $this->view->totalUsers     = $this->model->totalUsers();
        $this->view->childrens_quantity = $this->model->totalchildrens();
        $this->view->companies          = $this->model->listCompanies();
        $this->view->status             = $this->model->listStatus();
        $this->view->childrens          = $this->model->listChildrens();

        $this->view->render('dashboard/index');
    }

    function stateGraph()
    {
        $data = $this->model->stGraph();
        print $data;
    }

    function statusGift()
    {
        $data = $this->model->stateGift();
        print $data;
    }
    function statusGiftTrans()
    {
        $data = $this->model->stateGiftTrans();
        print $data;
    }
    function statusGiftSuba()
    {
        $data = $this->model->stateGiftSuba();
        print $data;
    }

}

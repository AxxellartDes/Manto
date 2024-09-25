<?php

require_once 'libs/controller.php';
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;

class User extends Controller
{

    function __construct()
    {
        parent::__construct();
        $this->view->mensaje = "";
    }

    function render()
    {
        $this->view->companies  = $this->model->listCompanies();
        $this->view->users      = $this->model->list();
        $this->view->childrens  = $this->model->listChildrens();
        $this->view->render('user/index');
    }

    function masive_create()
    {
        $this->view->render('user/masive_load');
    }

    function create()
    {
        $this->view->roles      = $this->model->listRoles();
        $this->view->companies  = $this->model->listCompanies();
        $this->view->render('user/add');
    }

    function save()
    {
        $iduser     = $_POST['iduser'];
        $name       = mb_strtoupper($_POST['name'], 'utf-8');
        $phone      = $_POST['phone'];
        $email      = mb_strtolower($_POST['email'], 'utf-8');
        $age        = 20;
        $rol        = 2;
        $company    = $_POST['company'];

        $password   = $_POST['iduser'];
        $passenc    = password_hash($password, PASSWORD_DEFAULT);

        $mensaje = "";

        if ($this->model->save([

            'iduser'    => $iduser,
            'name'      => $name,
            'phone'     => $phone,
            'email'     => $email,
            'age'       => $age,
            'rol'       => $rol,
            'company'   => $company
        ])) {
            $this->model->createLogin([
                'user_iduser'   => $iduser,
                'password'      => $passenc,
            ]);

            $this->view->mensaje = '
            <div class="alert alert-secondary alert-dismissible fade show" role="alert">
            Usuario almacenado con exito
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            ';
            $this->render();
        } else {
            $this->view->mensaje = '
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
            Error al almacenar la informacion
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            ';
            $this->render();
        }
    }

    function edit($param = null)
    {
        $idempleado = $param[0];
        $user = $this->model->getById($idempleado);

        $this->view->users      = $user;
        $this->view->roles      = $this->model->listRoles();
        $this->view->companies  = $this->model->listCompanies();
        $this->view->mensaje    = "";
        $this->view->render('user/edit');
    }

    function update()
    {
        $iduser     = $_POST['iduser'];
        $code       = $_POST['code'];
        $name       = mb_strtoupper($_POST['name'], 'utf-8');
        $surname    = mb_strtoupper($_POST['name'], 'utf-8');
        $phone      = $_POST['phone'];
        $email      = mb_strtolower($_POST['email'], 'utf-8');
        $rol        = $_POST['rol'];
        $company    = $_POST['company'];


        if ($this->model->update([
            'iduser'    => $iduser,
            'code'      => $code,
            'name'      => $name,
            'surname'   => $surname,
            'phone'     => $phone,
            'email'     => $email,
            'rol'       => $rol,
            'company'   => $company
        ])) {
            $mensaje =
                '<div class="alert alert-primary" role="alert">
                    Usuario actualizado con exito
                </div>';
        } else {
            $mensaje =
                '<div class="alert alert-danger" role="alert">
                    Error al actualizar el usuario
                </div>';
        }
        $this->view->mensaje = $mensaje;
        $this->render();
    }

    function delete($param = null)
    {
        $idempleado = $param[0];
        $this->model->delete($idempleado);
        $mensaje =
            '<div class="alert alert-danger" role="alert">
                    Usuario eliminado con exito
                </div>';
        $this->view->mensaje = $mensaje;
        $this->render();
    }

    function restorePass($param = null)
    {
        $idempleado = $param[0];
        $passenc    = password_hash($idempleado, PASSWORD_DEFAULT);

        $this->model->restore([
            'iduser'    => $idempleado,
            'password'  => $passenc,
        ]);
        $mensaje =
            '<div class="alert alert-success" role="alert">
                    Contraseña reestablecida con exito
                </div>';
        $this->view->mensaje = $mensaje;
        $this->render();
    }

    function restoreSelection($param = null)
    {


        $data = explode("-", $param[0]);
        
        $idchildren = $data[0];
        $gift_idgift = $data[1];

        $this->model->restoreGift([
            'idchildren' => $idchildren,
            'gift_idgift' => $gift_idgift
        ]);
        $mensaje =
            '<div class="alert alert-success" role="alert">
                   la seleccion del menor fue reestablecida con exito
                </div>';
        $this->view->mensaje = $mensaje;
        $this->render();
    }

    function searchById()
    {
        $id = $_POST['iduser'];

        if ($id != "") {

            if ($users = $this->model->searchUsersById($id)) {
                $mensaje = '';
                $this->view->companies  = $this->model->searchCompaniesById($users[0]->company);
                $this->view->users      = $users;
                $this->view->childrens  = $this->model->searchChildrensById($id);
                $this->view->mensaje = $mensaje;
                $this->view->render('user/index');
            } else {
                $mensaje = '<div class="alert alert-danger" role="alert">
                    No se encontro ningun registro con el criterio de busqueda seleccionado
                </div>';
                $this->view->mensaje = $mensaje;
                $this->render();
            }
        } else {
            $mensaje = '<div class="alert alert-danger" role="alert">
            Debe ingresar un dato
            </div>';
            $this->view->mensaje = $mensaje;
            $this->render();
        }
    }

    function loadUsers()
    {
        $namelocation = $_FILES['fileProg']['tmp_name'];

        $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($namelocation);

        /**  Create a new Reader of the type that has been identified  **/
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);

        /**  Load $inputFileName to a Spreadsheet Object  **/
        $spreadsheet = $reader->load($namelocation);

        /**  Convert Spreadsheet Object to an Array for ease of use  **/
        $schdeules = $spreadsheet->getActiveSheet()->toArray();

        $items = [];

        foreach ($schdeules as $single_schedule) {

            if ($single_schedule['0'] == "ID") {
            } else {
                $datos = new Login();

                $datos->idusers         = $single_schedule[0];
                $datos->password        = password_hash($single_schedule[0], PASSWORD_DEFAULT);

                array_push($items, $datos);
            }
            // $description    = 'Carga ';

            // print_r($single_item);

            // print_r($items);
        }
        if ($this->model->createMasiveLogin($items)) {
            $mensaje =
                '<div class="alert alert-success" role="alert">
                Informacion almacenada con exito
            </div>';
            unset($_POST);
            $this->view->mensaje = $mensaje;
            $this->view->render('user/masive_load');
        } else {
            $mensaje =
                '<div class="alert alert-danger" role="alert">
            El documento no cumple con la estructura para el almacenamiento
            </div>';
            $this->view->mensaje = $mensaje;
            $this->view->render('user/masive_load');
        }
    }
}

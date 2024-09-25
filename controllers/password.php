<?php

require_once 'libs/controller.php';
// include "logs.php";

class Password extends Controller
{

    function __construct()
    {
        parent::__construct();
        $this->view->mensaje = "";
    }

    function render()
    {
        $this->view->render('password/index');
    }

    function changePass()
    {

        session_start();

        $user    = $_SESSION['iduser'];
        $newpass = $_POST['newpass'];
        $confirm = $_POST['confirm'];

        if ($newpass == $confirm) {
            $password = password_hash($newpass, PASSWORD_DEFAULT);
            if ($this->model->newPass([
                'user'      => $user,
                'password'  => $password
            ])) {
                $_SESSION['update_pass']    = 1;

                $mensaje = '<div class="alert alert-success" role="alert">
                Contraseña actualizada exitosamente
                </div>';

                echo '<html>
                <form action="'.constant('URL').'login/loginUser/" method="post" name="formulario1" id="formulario1">
                    <input type="hidden" name="user" value="'.$user.'">
                    <input type="hidden" name="password" value="'.$newpass.'">
                    <input type="hidden" name="mensaje" value="cambiado">
                </form>
                </html>';

                echo '<script>

                enviar_formulario();

                function enviar_formulario() {
                document.formulario1.submit();
                }

                </script>';
                
                
            } else {
                $mensaje = '<div class="alert alert-success" role="alert">
                Ocurrio un error inesperado
                </div>';

                $this->view->mensaje = $mensaje;
                $this->render();
            }
        } else {
            $mensaje = '<div class="alert alert-danger" role="alert">
            Las contraseñas no coinciden
            </div>';

            $this->view->mensaje = $mensaje;
            $this->render();
        }
        
    }
}

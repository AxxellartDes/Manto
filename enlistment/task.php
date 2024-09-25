<?php

include_once 'conection.php';

$iduser = $_GET['user'];

echo '<html>
        <form action="https://mantenimiento.si18.com.co/login/login1/" method="post" name="formulario1" id="formulario1">
            <input type="hidden" name="id_user" value="'.$iduser.'">
        </form>
        </html>';

        echo '<script>

        enviar_formulario();

        function enviar_formulario() {
           document.formulario1.submit();
        }

        </script>';

?>
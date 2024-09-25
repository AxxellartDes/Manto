<?php

if (!isset($_SESSION)) {
    session_start();
}

if ($_SESSION['rol_idrol'] != ('1' || '2' || '3')) {
    header("location:" . constant('URL') . "login");
    die();
}

if($_SESSION['update_pass'] == 0){
    $m_update = " - Recuerda que en tu primer ingreso es obligatorio el cambio de contraseña.";
}else{
    $m_update = "";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?php echo constant('URL'); ?>public/images/si18.png" />

    <!-- Styles -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo constant('URL'); ?>public/css/bootstrap.min.css" />
    <link rel="stylesheet" href="<?php echo constant('URL'); ?>public/css/styles.css" />

    <!-- JS -->
    <script type="text/javascript" src="<?php echo constant('URL'); ?>public/js/jquery-3.5.1.min.js"></script>
    <script type="text/javascript" src="<?php echo constant('URL'); ?>public/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo constant('URL'); ?>node_modules\chart.js\dist\chart.js"></script>
    <script type="text/javascript" src="<?php echo constant('URL'); ?>public/js/scripts.js"></script>
    <title>Mantenimiento Sí18</title>

</head>
<body class="main-header">
    <nav class="navbar fixed-top navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" target="_blank" href="https://www.si18.com.co/"><img src="<?php echo constant('URL'); ?>public/images/si18.png" width="30" alt=""></a>
                <b>
                    <?php echo $_SESSION['name']." - ".$_SESSION['rol']; ?>
                </b>
        
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
        
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo constant('URL'); ?>almacen/inicio">Inicio</a>
                        </li>
                        <?php
                        if($_SESSION['rol_idrol'] == 1 OR $_SESSION['rol_idrol'] == 2){
                        ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo constant('URL'); ?>almacen/select">Pedido Almacen</a>
                        </li>
                        <?php
                        }
                        ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Control OT
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <?php
                                    if($_SESSION['rol_idrol'] == 5){
                                ?>
                                <a class="nav-link" href="<?php echo constant('URL'); ?>ot/listotcomp">Listar OT</a>
                                <?php
                                    }elseif($_SESSION['rol_idrol'] == 2){
                                ?>
                                <a class="nav-link" href="<?php echo constant('URL'); ?>ot/select">Nuevo Registro</a>
                                    <div class="dropdown-divider"></div>
                                <a class="nav-link" href="<?php echo constant('URL'); ?>ot/listot">Listar OT</a>
                                <?php
                                    }elseif($_SESSION['rol_idrol'] == 1){
                                ?>
                                <a class="nav-link" href="<?php echo constant('URL'); ?>ot/select">Nuevo Registro</a>
                                    <div class="dropdown-divider"></div>
                                <a class="nav-link" href="<?php echo constant('URL'); ?>ot/listotall">Listar OT</a>
                                <?php
                                    }
                                ?>    
                            </div>
                        </li>
                        <?php
                        if($_SESSION['rol_idrol'] == 1){
                        ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo constant('URL'); ?>user/masive_create">Registrar Usuarios</a>
                            </li>
                        <?php
                        }
                        ?>  
                        <?php
                        if($_SESSION['iduser'] == 1085929701){
                        ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Registro Tareas
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="nav-link" href="<?php echo constant('URL'); ?>task">Registrar Tarea</a>
                                <div class="dropdown-divider"></div>
                                <a class="nav-link" href="<?php echo constant('URL'); ?>task/myRegistersOn">Mis Registros Activos</a>
                                <div class="dropdown-divider"></div>
                                <a class="nav-link" href="<?php echo constant('URL'); ?>task/myRegistersOff">Mis Registros Finalizados</a>
                            </div>
                        </li>
                        <?php
                        }
                        ?>  
                        
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo constant('URL'); ?>enlistment/listRegister">Alistamiento</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo constant('URL'); ?>password">Cambiar Contraseña</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo constant('URL'); ?>login/logout">Salir</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    <div class="container">

        <div>
            <?php
            echo $this->mensaje;
            ?>
        </div>

        <div class="card glass">
            <h5 class="card-header">Cambiar contraseña <?php echo $m_update; ?> </h5>
            <div class="card-body">
                <form action="<?php echo constant('URL'); ?>password/changepass" method="POST">
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Nueva contraseña</label>
                        <input type="password" class="form-control" name="newpass" id="newpass" placeholder="Nueva contraseña">
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Repite tu nueva contraseña</label>
                        <input type="password" class="form-control" name="confirm" id="confirm" placeholder="Validacion de nueva contraseña">
                    </div>
                    <br>
                    <div style="text-align: center;">
                        <input class="btn btn-primary" type="submit" value="Guardar">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <br>
    <br>
    <br>

    <footer class="footer" style="position: fixed;
        bottom: 0px;
        width: 100%;
        z-index: 10">
        <div class="footer-copyright text-center py-1" style="background-color: #ccc;">
            <div class="container">
                <div class="center" style="line-height: 15px;">
                    <small>
                        <small>
                            Señor usuario: Recuerde, si desea realizar alguna sugerencia, por favor tramitarla a través del
                            correo tic@si18.com.co

                        </small>
                    </small>
                </div>

                <b>
                    © 2023 Copyright: <a href="https://www.si18.com.co/">www.si18.com.co</a>
                </b>
            </div>
        </div>
    </footer>
</body>
</html>


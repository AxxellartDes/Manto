<?php

if (!isset($_SESSION)) {
    session_start();
}

if ($_SESSION['rol_idrol'] != ('1' || '2' || '3' || '5' || '6' )) {
    header("location:" . constant('URL') . "login");
    die();
}

if($_SESSION['name'] != ""){
    if($_SESSION['update_pass'] == 0){
        header("Location: ".constant('URL')."password");
        exit();
    }
}else{
    header("location:" . constant('URL') . "login");
    die();
}

// header('Content-Type: text/html; charset=ISO-8859-1');
?>

<!DOCTYPE html>
<html lang="es">

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
                    <?php
                    if($_SESSION['iduser'] == 1085929701){
                    ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo constant('URL'); ?>dataindicator/loadProgrammedFront">Cargar Mantenimientos programados</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo constant('URL'); ?>prueba/funpdf">PDF</a>
                        </li>
                    <?php
                    }
                    if($_SESSION['rol_idrol'] == 5){
                    ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Compras Directas
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <?php
                                if($_SESSION['rol_idrol'] == 5){
                            ?>
                            <a class="nav-link" href="<?php echo constant('URL'); ?>ot/listotcomp">Listar OT</a>
                            <?php
                                }elseif($_SESSION['rol_idrol'] == 6){
                            ?>
                            <div class="dropdown-divider"></div>
                            <a class="nav-link" href="<?php echo constant('URL'); ?>ot/listot">Listar OT</a>
                            <?php
                                }
                            ?>  
                        </div>
                    </li>
                    <?php
                    }
                    ?>
                    <?php
                    if($_SESSION['rol_idrol'] == 6){
                    ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Compras Directas
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <?php
                                if($_SESSION['rol_idrol'] == 5){
                            ?>
                            <a class="nav-link" href="<?php echo constant('URL'); ?>ot/listotcomp">Listar OT</a>
                            <?php
                                }elseif($_SESSION['rol_idrol'] == 6){
                            ?>
                            <a class="nav-link" href="<?php echo constant('URL'); ?>ot/select">Nuevo Registro</a>
                                <div class="dropdown-divider"></div>
                            <a class="nav-link" href="<?php echo constant('URL'); ?>ot/listot">Listar OT</a>
                            <?php
                                }
                            ?>  
                        </div>
                    </li>
                    <?php
                    }
                    if($_SESSION['rol_idrol'] == 1){
                    ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo constant('URL'); ?>user/masive_create">Registrar Usuarios</a>
                        </li>
                    
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
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo constant('URL'); ?>enlistment/listRegister">Alistamiento</a>
                    </li>
                    <?php
                       }
                    ?>  
                    <?php
                    if($_SESSION['rol_idrol'] == 6){
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo constant('URL'); ?>enlistment/listRegister">Alistamiento</a>
                    </li>
                    <?php
                       }
                    ?>  
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

    <br>
    <br>
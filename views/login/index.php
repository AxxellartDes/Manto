<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?php echo constant('URL'); ?>public/images/si18.png" />

    <!-- Styles -->
    <link rel="stylesheet" href="<?php echo constant('URL'); ?>public/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo constant('URL'); ?>public/css/styles.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- JS -->
    <script type="text/javascript" src="<?php echo constant('URL'); ?>public/js/jquery-3.5.1.min.js"></script>
    <script type="text/javascript" src="<?php echo constant('URL'); ?>public/js/bootstrap.min.js"></script>

    <title>Mantenimiento Sí18</title>

</head>

<body class="text-center main-header align-middle" style="background-color: #ffffff;">
    <div class="container">
        <div class="row" style="width: 100%; text-align: center; padding-left:28%; padding-right:28%;">
            <?php //action="<?php echo constant('URL'); cierrephp login/loginUser"  ?>
            <div class="col-12 col-md-12 col-lg-5 col-xl-4 card glass" style="box-shadow: 0px 5px 15px 10px #6669; background-color: #FFFFFFB3; width: 100%;">
                <form class="form-signin" action="<?php echo constant('URL'); ?>login/loginUser" method="POST">
                    <!-- <h1 class="h3 mb-3 font-weight-normal">Ingresar</h1> -->
                    <?php echo $this->mensaje; ?>
                    <br>
                    <img class="mb-4" src="<?php echo constant('URL'); ?>public/images/si18.png" alt="" width="120" height="">
                    <br>
                    <label for="inputEmail" class="sr-only"><b>Nombre de Usuario</b></label>
                    <input type="text" id="users" class="form-control mt-2" placeholder="Nombre de Usuario" name="user" required="" autofocus="">
                    <div>
                        <small style="color: red;" class="alert"><?php echo $this->mensaje_1; ?></small>
                    </div>
                    <label for="inputPassword" class="sr-only"><b>Contraseña</b></label>
                    <input type="password" name="password" id="password" class="form-control mt-2" placeholder="Password" required="">
                    <div>
                        <small style="color: red;" class="alert"><?php echo $this->mensaje_2; ?></small>
                    </div>
                    <div class="checkbox mb-3">
                        <label>
                            <input type="checkbox" value="remember-me"> Recuérdame
                        </label>
                    </div>
                    <button class="btn btn-lg btn-primary btn-block" type="submit">Ingresar</button>
                    <br>
                    <div id="formFooter">
                        <a class="underlineHover" href="<?php echo constant('URL'); ?>retrieve_pass">¿olvidaste tu
                            contraseña?</a>
                    </div>
                    <br>
                </form>
            </div>
        </div>
    </div>
    
</body>
<footer style="position: fixed;
justify-content: space-around;
    bottom: 0px;
    width: 100%;">
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
                © 2023 Copyright:<a target="_blanck" href="https://www.si18.com.co/"> www.si18.com.co</a>
            </b>
        </div>
    </div>
</footer>

</html>
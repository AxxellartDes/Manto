<?php

include_once 'conection.php';

$iduser = $_GET['user'];
$pass   = $_GET['pass'];

$query = $dbcon->query("
    SELECT
        `login`.`user_iduser`,
        `login`.`password`,
        `user`.`name`,
        `user`.`rol_idrol`,
        `user`.`company_idcompany`
    FROM
        login
    INNER JOIN `user` ON `login`.`user_iduser` = `user`.`iduser`
    WHERE
        login.user_iduser = '$iduser'
");

$row = mysqli_fetch_array($query);

if(mysqli_num_rows($query) > 0){
    echo "Existe <br>";
    $password = $row['password'];
    if(password_verify($pass, $password)){
        echo "Correcto";
    }else{
        echo "Incorrecto";
    }
}else{
    echo "El usuario no se encuentra registrado.";
}

?>
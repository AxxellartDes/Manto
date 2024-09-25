<?php

include_once 'conection.php';

$idbus = $_GET['idbus'];

    try{
        $query = $dbcon->query("
            SELECT 
                `number_bus` 
            FROM 
                `bus` 
            WHERE 
                `idbus` = '$idbus'
        ");
        $result = mysqli_fetch_array($query);
        echo $result[0];
        return $result[0];
    }catch(PDOException $e){
        // echo "Este documento ya esta registrado";
        return false;
    }
            

?>
<?php

include_once 'conection.php';

$observation = NULL;

$user = $_GET['user'];
$bus = $_GET['bus'];

if(isset($_GET['missing']) and $_GET['missing'] != ""){
    $missing = $_GET['missing'];
}else{
    $missing = "N/A";
}


setlocale(LC_TIME, "es_CO.UTF-8");
date_default_timezone_set('America/Bogota');
$date = date("Y-m-d H:i:s");

$query4 = $dbcon->query("
    SELECT 
        MAX(`id_enlistment`) AS id_enlistment
    FROM 
        `enlistment`
");

$row4 = mysqli_fetch_array($query4);
$id_enlistment1 = $row4['id_enlistment'];

$query = $dbcon->query("
    INSERT INTO enlistment (
        ID_USER,
        ID_BUS,
        DATE,
        MISSING
    ) VALUES (
        $user,
        $bus,
        '$date',
        '$missing'
    )
");

$query2 = $dbcon->query("
    SELECT 
        MAX(`id_enlistment`) AS id_enlistment
    FROM 
        `enlistment`
");

$row2 = mysqli_fetch_array($query2);
$id_enlistment2 = $row2['id_enlistment'];

// print_r($id_enlistment2);
// echo "<br>";
// print_r($id_enlistment1);

if($id_enlistment2>$id_enlistment1){

    $query5 = $dbcon->query("
    SELECT 
        MAX(`id_data`) AS id_data 
    FROM 
        `enlistment_items_data`
    ");

    $row5 = mysqli_fetch_array($query5);
    $id_data1 = $row5['id_data'];

    $controlitem = 0;

    for($i=1; $i<=23; $i++){
        $name = "q".$i;
        $namec = "c".$i;
        $namet = "t".$i;
        if(isset($_GET[$name])){
            // echo "Entra";
            $name = $_GET[$name];
            if($name != 0 and $name != ""){
                $controlitem++;
                $namec = $_GET[$namec];
                $namet = $_GET[$namet];
                $query3 = $dbcon->query("
                    INSERT INTO enlistment_items_data (
                        ID_ENLISTMENT,
                        ID_ITEM,
                        OBSERVATION,
                        TIME
                    ) VALUES (
                        $id_enlistment2,
                        $i,
                        '$namec',
                        '$namet'
                    )
                ");
            }
            
        }
    }

    if($controlitem == 0){
        $query3 = $dbcon->query("
            INSERT INTO enlistment_items_data (
                ID_ENLISTMENT,
                ID_ITEM,
                OBSERVATION,
                TIME
            ) VALUES (
                $id_enlistment2,
                24,
                'N/A',
                '0'
            )
        ");
    }

    $query6 = $dbcon->query("
    SELECT 
        MAX(`id_data`) AS id_data 
    FROM 
        `enlistment_items_data`
    ");

    $row6 = mysqli_fetch_array($query6);
    $id_data2 = $row6['id_data'];

    if($id_data2 > $id_data1){
        $mensaje = "2ok";
    }else{
        $mensaje = "1ok";
    }
}else{
    $mensaje = "0ok";
}

echo $mensaje;


?>

<iframe width="1519" height="581" src="https://mascercanosati.si18.com.co/Videos/video1.mp4" title="PERROS CRIOLLOS - HAY GENTE ASÍ, CAP. 22" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
<?php

require_once 'controllers/errores.php';

class App{
    function __construct()
    {
        $url = isset($_GET['url']) ? $_GET['url']: null;
        $url = rtrim($url,'/');
        $url = explode('/', $url);
        
        if (empty($url[0])) {
            $archivoControlller = 'controllers/index.php';
            require_once $archivoControlller;
            $controller = new Index();
            $controller->loadModel('index');
            $controller->render();
            return false;
        }

        $archivoControlller = 'controllers/' . $url[0] . '.php';

        if (file_exists($archivoControlller)) {
            require_once $archivoControlller;

            
            $controller = new $url[0];
            $controller->loadModel($url[0]);
            
            $nparam = sizeof($url);

            if($nparam > 1){
                if($nparam > 2){
                    $param =[];
                    for($i =2; $i<$nparam; $i++){
                        array_push($param, $url[$i]);
                    }
                    $controller->{$url[1]}($param);
                }else{
                    $controller->{$url[1]}();
                }
            }else{
                $controller->render();
            }
        }else{
            $controller = new Errores;
        }
    }
}
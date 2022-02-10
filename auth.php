<?php
    require_once 'clases/auth.class.php';
    require_once 'clases/respuestas.class.php';

    //Instanciamos las clases
     $_auth = new auth;
    $_respuestas = new respuestas;

    //Consultamos que metodos esta utilizando para acceder

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $postBody = file_get_contents("php://input");
        $datosArray = $_auth->login($postBody);
    }else{
        echo "Metodo no permitido";
    }
?>
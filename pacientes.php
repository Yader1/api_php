<?php 
    require_once 'clases/respuestas.class.php';
    require_once 'clases/pacientes.class.php';

    //Instanciamos las _respuestas
    $_respuestas = new respuestas;
    $_pacientes = new pacientes;

    //Metodo GET
    if($_SERVER['REQUEST_METHOD'] == "GET"){
        //Valor de la pagina
        if(isset($_GET["page"])){
            $pagina = $_GET["page"];
            $listaPacientes = $_pacientes->listaPacientes($pagina);
            echo json_encode($listaPacientes);

            //Obtenemos un paciente por id
        }else if(isset($_GET['id'])){
        $pacienteid = $_GET['id'];
        $datosPaciente = $_pacientes->obtenerPaciente($pacienteid);
        echo json_encode($datosPaciente);
        }
        //Si no es el metodo GET pasamos al metodo POST
    }else if($_SERVER['REQUEST_METHOD'] == "POST"){
        echo "Hola POST";
        //Si no es el metodo POST pasamos al metodo PUT
    }else if($_SERVER['REQUEST_METHOD'] == "PUT"){
        echo "Hola PUT";
        //Si no es el metodo PUT pasamos al metodo DELETE
    }else if($_SERVER['REQUEST_METHOD'] == "DELETE"){
        echo "Hola DELETE";
    }else{
        //Error
        header('Content-Type: application/json');
        $datosArray = $_respuestas->error_405();
        echo json_encode($datosArray);
    }
?>
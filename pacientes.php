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
            header('Content-Type: application/json');
            echo json_encode($listaPacientes);
            http_response_code(200);
            //Obtenemos un paciente por id
        }else if(isset($_GET['id'])){
            $pacienteid = $_GET['id'];
            $datosPaciente = $_pacientes->obtenerPaciente($pacienteid);
            header('Content-Type: application/json');
            echo json_encode($datosPaciente);
            http_response_code(200);
        }

        //Si no es el metodo GET pasamos al metodo POST
    }else if($_SERVER['REQUEST_METHOD'] == "POST"){
        //Resivimos los datos enviados
        $postBody = file_get_contents("php://input");
        //Enviamos los datos al manejador
        $datosArray = $_pacientes->post($postBody);
        //Devolvemos una respuesta
        header('Content-Type: application/json');

        if(isset($datosArray["result"]["error_id"])){
            $responseCode = $datosArray["result"]["error_id"];
            http_response_code($responseCode);
        }else{
            http_response_code(200);
        }

        echo json_encode($datosArray);
        

        //Si no es el metodo POST pasamos al metodo PUT
    }else if($_SERVER['REQUEST_METHOD'] == "PUT"){
        


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
<?php 
    require_once 'conexion/conexion.php';
    require_once 'respuestas.class.php';

    class pacientes extends conexion{
        private $table = "pacientes";
        //Datos de la tabla
        private $pacienteid = "";
        private $dni = "";
        private $nombre = "";
        private $direccion = "";
        private $codigoPostal = "";
        private $genero = "";
        private $telefono = "";
        private $fechaNacimiento = "0000-00-00";
        private $correo = "";

        //Token
        private $token = "";

        public function listaPacientes($pagina = 1){
            $inicio = 0;
            $cantidad = 100;
            if($pagina > 1){
                $inicio = ($cantidad * ($pagina - 1)) +1;
                $cantidad = $cantidad * $pagina;
            }

            //Hacemos el query
            $query = "SELECT PacienteId,Nombre,DNI,Telefono,Correo FROM " . $this->table . " limit $inicio, $cantidad"; 
            //Mostramos los datos
            $datos = parent::obtenerDatos($query);
            return $datos;
        }
        //Extraer un paciente por ID
        public function obtenerPaciente($id){
            $query = "SELECT * FROM " . $this->table . " WHERE PacienteId = '$id'";
            return parent::obtenerDatos($query);
        }

        //Datos POST
        public function post($json){
            $_respuestas = new respuestas;
            $datos = json_decode($json, true);

            //Si viene vacio
            if(!isset($datos['nombre']) || !isset($datos['dni']) || !isset($datos['correo'])){
                return $_respuestas->error_400();
            }else{
                $this->nombre = $datos['nombre']; //Datos rqueridos
                $this->dni = $datos['dni'];
                $this->correo = $datos['correo'];
                if(isset($datos['telefono'])){ $this->telefono = $datos['telefono']; } //Datos no requeridos
                if(isset($datos['direccion'])){ $this->direccion = $datos['direccion']; }
                if(isset($datos['codigoPostal'])){ $this->codigoPostal = $datos['codigoPostal']; }
                if(isset($datos['genero'])){ $this->genero = $datos['genero']; }
                if(isset($datos['fechaNacimiento'])){ $this->fechaNacimiento = $datos['fechaNacimiento']; }

                $resp = $this->insertarPacientes();

                if( $resp ){
                   $respuesta = $_respuestas->response;
                   $respuesta["result"] = array(
                    "pacienteid" => $resp
                   );
                   return $respuesta;
                }else{
                    return $_respuestas->error_500();
                }
            }

        }

        //Insertar formulario
        private function insertarPacientes(){
            $query = "INSERT INTO " . $this->table . " (DNI,Nombre,Direccion,CodigoPostal,Telefono,Genero,FechaNacimiento,Correo)
            values
            ('" . $this->dni . "','" . $this->nombre . "','" . $this->direccion . "','" . $this->codigoPostal . "','" . $this->telefono . "','" . $this->genero . "','" . $this->fechaNacimiento . "','" . $this->correo . "')";
            $resp = parent::nonQueryId($query);

            if( $resp ){
                return $resp;
            }else{
                return 0;
            }
        }

        //Datos PUT
        public function put($json){
            $_respuestas = new respuestas;
            $datos = json_decode($json, true);

            //Campo requerido
            if(!isset($datos['pacienteId'])){
                return $_respuestas->error_400();
            }else{
                $this->pacienteid = $datos['pacienteId'];
                if(isset($datos['nombre'])){ $this->nombre = $datos['nombre']; } //Datos rqueridos
                if(isset($datos['dni'])){ $this->dni = $datos['dni']; }
                if(isset($datos['correo'])){$this->correo = $datos['correo']; }
                if(isset($datos['telefono'])){ $this->telefono = $datos['telefono']; } //Datos no requeridos
                if(isset($datos['direccion'])){ $this->direccion = $datos['direccion']; }
                if(isset($datos['codigoPostal'])){ $this->codigoPostal = $datos['codigoPostal']; }
                if(isset($datos['genero'])){ $this->genero = $datos['genero']; }
                if(isset($datos['fechaNacimiento'])){ $this->fechaNacimiento = $datos['fechaNacimiento']; }

                $resp = $this->modificarPacientes();

                if( $resp ){
                   $respuesta = $_respuestas->response;
                   $respuesta["result"] = array(
                    "pacienteid" => $this->pacienteid
                   );
                   return $respuesta;
                }else{
                    return $_respuestas->error_500();
                }
                
            }

        }
         //Actualizar datos
         private function modificarPacientes(){
            $query = "UPDATE " . $this->table . " SET Nombre ='" . $this->nombre . "',Direccion = '" . $this->direccion . "',DNI = '" . $this->dni . "', CodigoPostal = '" . $this->codigoPostal . "',Telefono = '" . $this->telefono . "', Genero = '" . $this->genero . "', FechaNacimiento = '" . $this->fechaNacimiento . "', Correo = '" . $this->correo . "' WHERE PacienteId = '" . $this->pacienteid . "'";
            $resp = parent::nonQuery($query);

           if( $resp >= 1 ){
                return $resp;
            }else{
                return 0;
            }
        }

        //Metodo delete
        public function delete( $json ){
            $_respuestas = new respuestas;
            $datos = json_decode($json, true);

            //Campo requerido
            if(!isset($datos['pacienteId'])){
                return $_respuestas->error_400();
            }else{
                $this->pacienteid = $datos['pacienteId'];

                $resp = $this->eliminarPaciente();

                if( $resp ){
                   $respuesta = $_respuestas->response;
                   $respuesta["result"] = array(
                    "pacienteid" => $this->pacienteid
                   );
                   return $respuesta;
                }else{
                    return $_respuestas->error_500();
                }
                
            }
        }

        private function eliminarPaciente(){
            $query = "DELETE FROM " . $this->table . " WHERE PacienteId= '" . $this->pacienteid . "'";
            $resp = parent::nonQuery($query);
            if($resp >= 1){
                return $resp;
            }else{
                return 0;
            }
        }
    }
?>
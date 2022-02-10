<?php 
    require_once 'conexion/conexion.php';
    require_once 'respuestas.class.php';

    class auth extends conexion{
        public function login($json){
            $_respuestas = new respuestas;
            //Convertimos el archivo JSON en un array
            $datos = json_decode($json, true);
            //Verificamos los campos que estan siendo enviados
            if(!isset($datos['usuarios']) || !isset($datos["password"])){
                //Error con los campos
                return $_respuestas->error_400();          
            }else{ 
                //Todo esta bien
                $usuario = $datos['usuario'];
                $password = $datos['password'];
                $datos = $this->obtenerDatosUsuarios($usuario);

                if($datos){
                    //Si existe el usuario
                }else{
                    //no existen el usuario
                    return $_respuestas->error_200("El usuario $usuario no existe");
                }
            }
        }

        private function obtenerDatosUsuarios($correo){
            $query = "SELECT UsuarioId,Password,Estado FROM usuarios WHERE Usuario = '$correo'";
            $datos = parent::obtenerDatos($query);

            if(isset($datos[0]["UsuarioId"])){
                return $datos;
            }else{
                return 0;
            }
        }
    }
?>
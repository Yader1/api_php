<?php 
    require_once 'conexion/conexion.php';
    require_once 'respuestas.class.php';

    class auth extends conexion{
        public function login($json){
            $_respuestas = new respuestas;
            //Convertimos el archivo JSON en un array
            $datos = json_decode($json, true);
            //Verificamos los campos que estan siendo enviados
            if(!isset($datos['usuario']) || !isset($datos["password"])){
                //Error con los campos
                return $_respuestas->error_400();          
            }else{ 
                //Todo esta bien
                $usuario = $datos['usuario'];
                $password = $datos['password'];
                $password = parent::encriptar($password);

                $datos = $this->obtenerDatosUsuarios($usuario);

                if($datos){
                    //verificar si la contraseña es igual
                    if($password == $datos[0]['Password']){
                        if($datos[0]['Estado'] == "Activo"){
                            //Crear el token
                            $verificartoken = $this->insertarToken($datos[0]['UsuarioId']);
                            if($verificartoken){
                                //Si se guardo
                                $result = $_respuestas->response;
                                $result["result"] = array(
                                    "token" => $verificartoken
                                );
                                return $result;
                            }else{
                                //error al guardar
                                return $_respuestas->error_500("Error interno, No hemos podido guardar");
                            }
                        }else{
                            //el usuario esta inactivo
                            return $_respuestas->error_200("El usuario esta inactivo");
                        }
                    }else{
                        return $_respuestas->error_200("El password es invalido");
                    }
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
        //Creamos un metodo para insertar los token
        private function insertarToken($usuarioid){
            $val = true;
            $token = bin2hex(openssl_random_pseudo_bytes(16, $val));
            $date = date("Y-m-d H:i");
            $estado = "Activo";
            $query = "INSERT INTO usuarios_token (UsuarioId,Token,Estado,Fecha)VALUES('$usuarioid','$token', '$estado', '$date')";
            $verificar = parent::nonQuery($query);
            if($verificar){
                return $token;
            }else{
                return 0;
            }
        }
    }
?>
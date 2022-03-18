<?php 
    require_once 'conexion/conexion.php';
    require_once 'respuestas.class.php';

    class pacientes extends conexion{
        private $table = "pacientes";

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
    }
?>
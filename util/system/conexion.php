<?php

if(!class_exists("Funciones")){
    include("funciones.php");
}

class Conexion{
    private $servidor;  //Nombre del servidor de base de datos
    private $usuario;   //Usuario de la base deDatos
    private $contrasena;//Contraseña del usuario de la Base de Datos
    private $conexion;
    private $puerto;
    private $logs;
    private $baseDatos;

    public function __construct($logs)
    {
        $this->baseDatos ='pedidos';
        $this->servidor = 'localhost';    //Nombre del servidor de base de datos
        $this->usuario = "root";               //Usuario de la base deDatos
        $this->contrasena ="";                   //Contraseña del usuario de la Base de Datos
        $this->puerto="3306";
        $this->logs=$logs;


    }
   
    public function parametros($baseDatos, $usuario, $contrasena, $servidor, $puerto = 3306){
        $this->baseDatos = $baseDatos;
        $this->usuario = $usuario;
        $this->contrasena = $contrasena;
        $this->servidor = $servidor;
        $this->puerto = $puerto;
    }
    public function conectar() {
        $mysqli = new mysqli($this->servidor, $this->usuario, $this->contrasena, $this->baseDatos, $this->puerto);
    
        if ($mysqli->connect_error) {
            Funciones::Logs("ConexionDB", $this->logs, "Error de conexion: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
            $this->conexion = false;
            return false;
        } else {
            $this->conexion = $mysqli;
            $this->conexion->set_charset('utf8');
            return true;
        }
    }
    

        public function ejecutarConsulta($sql){
            $resultado = $this->conexion->query($sql);
        
            if ($resultado) {
                return $resultado;
            } else {
                Funciones::Logs("ConsultaDB", $this->logs, "Error en el query (" . $this->conexion->error . ") " . $sql);
                return false;
            }
        }

        public function __destruct()
        {
           if ($this->conexion) {
            $this->conexion->close();
           } 
        }
        
    }

    // teste para saber si se establece la conexion y en casa contrario muestra el error en la carpeta logs
    $conexion = new Conexion('../logs/');
    $conexion->conectar();
    $conexion->ejecutarConsulta("SELECT * FROM usuarios");

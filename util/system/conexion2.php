<?php

if (!class_exists("Funciones")) {
    include("funciones.php");
}

class Conexion {
    private $servidor;
    private $usuario;
    private $contrasena;
    private $conexion;
    private $puerto;
    private $logs;
    private $baseDatos;

    public function __construct($logs) {
        $this->baseDatos = 'pedidos';
        $this->servidor = 'localhost';
        $this->usuario = "root";
        $this->contrasena = "";
        $this->puerto = "3306";
        $this->logs = $logs;
    }

    public function parametros($baseDatos, $usuario, $contrasena, $servidor, $puerto = 3306) {
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

    public function ejecutarConsulta($sql) {
        if (!$this->conexion) {
            // La conexión no está establecida, no podemos ejecutar la consulta
            Funciones::Logs("ConsultaDB", $this->logs, "Error en el query: Conexión no establecida. " . $sql);
            return false;
        }

        $resultado = $this->conexion->query($sql);

        if ($resultado) {
            return $resultado;
        } else {
            Funciones::Logs("ConsultaDB", $this->logs, "Error en el query (" . $this->conexion->error . ") " . $sql);
            return false;
        }
    }

    public function __destruct() {
        if ($this->conexion) {
            $this->conexion->close();
        }
    }
}

// Crea una instancia de la clase Conexion
$conexion = new Conexion('../logs/');

// Intenta conectar a la base de datos
if ($conexion->conectar()) {
    // La conexión se estableció con éxito
    // Ahora puedes ejecutar tus consultas
    $resultado = $conexion->ejecutarConsulta("SELECT * FROM tu_tabla");

    // Verifica si la consulta se ejecutó con éxito
    if ($resultado) {
        // Procesa los resultados de la consulta
        // ...
    } else {
        // Hubo un error en la consulta; se registrará en el archivo de logs
        // Puedes agregar más manejo de errores aquí si es necesario
        echo "Error en la consulta. Verifica el archivo de logs para más detalles.";
    }
} else {
    // Hubo un error en la conexión; se registrará en el archivo de logs
    // Puedes agregar más manejo de errores aquí si es necesario
    echo "Error en la conexión. Verifica el archivo de logs para más detalles.";
}
error_reporting(E_ALL);
ini_set('display_errors', 'on');

?>




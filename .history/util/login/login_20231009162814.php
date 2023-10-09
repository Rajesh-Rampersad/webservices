<?php

include("../system/funciones.php");
include("../system/session.php");
include("../system/conexion.php");

$conexion = new Conexion('../logs/');
$conexion->conectar();

$session = new Session();

$respuesta = new stdClass();
$respuesta->estado = 1;
$respuesta->mensaje = "";

try {

    $usuario = "";
    $contrasena = "";

    if (
        (isset($_POST['usuario']) && !empty($_POST['usuario']) ) &&
        (isset($_POST['contrasena']) && !empty($_POST['contrasena']) )
    ) {
        $usuario = $_POST['usuario'];
        $contrasena = $_POST['contrasena'];
    }

    if (
        empty($usuario) || empty($contrasena)
    ) {
        throw new Exception("El usuario o la contraseña estan vacios");
    }

    $datos_usuario = array();
    $contrasena_cifrada = hash("sha512", "m7x".$contrasena);

    $resultado = $conexion->ejecutarConsulta("SELECT * FROM usuarios
    WHERE usuario = '".$usuario."' AND contrasena = '".$contrasena."'
    LIMIT 1
    ");

    foreach ($resultado as $fila) {
        $dato_usuario = $fila;
    }


    if (
        count($datos_usuario) == 0
    ) {
        throw new Exception("El usuario no existe en la aplicación, o la contraseña es invalida");
    }

    $session->createSession($datos_usuario);
    
} catch (Exception $e) {
    $respuesta->estado = 2;
    $respuesta->mensaje = $e-> getMessage();
}

print_r(json_encode($respuesta));
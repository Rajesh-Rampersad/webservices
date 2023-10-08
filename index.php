<?php

include("util/system/funciones.php");
include("util/system/session.php");
include("util/system/conexion.php");

$conexion = new Conexion('util/logs/');
$conexion->conectar();

$session = new Session();

#Obterner los parametros del sistema

$resultado_parametros = $conexion->ejecutarConsulta("
    SELECT * FROM parametros
");

$parametro = array();

foreach($resultado_parametros as $fila){
    $parametro[trim($fila['parametro'])] = trim($fila['valor']);

}

##############################################################


#Si tenemos una sesion abierta, incluimos los modulos del sistema

if ($session->checkSession() ) {

#Si no hay una sesion iniciada, mostramos el login del sistema

}else{
    include('inc/login.php');
}
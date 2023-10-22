<?php
/*=============================================
Mostrar errores
=============================================*/
ini_set('display_errors', 1); // coloca 0 si no deseas que aparezcan los errores también en el navegador
ini_set("log_errors", 1); // con esta línea estamos diciendo que queremos crear un nuevo archivo de errores
ini_set("error_log",  "D:/xampp8/htdocs/errores/php_error_log"); // con esta línea le decimos a PHP donde queremos que se guarde ese archivo, lo recomendado es que sea al lado del archivo index.php

/*=============================================
FATAL ERROR
Este tipo de errores aborta la ejecución del programa e interrumpe su completo funcionamiento. Ocurre, por ejemplo, cuando intentas ejecutar una función y no existe en tu código o intentas iniciar un objeto de una clase que aún no has declarado
=============================================*/
include("util/system/funciones.php");
include("util/system/session.php");
include("util/system/conexion.php");

$conexion = new Conexion('util/logs/');
$conexion->conectar();

$session = new Session();

# Obtener los parametros del sistema

$resultado_parametros = $conexion->ejecutarConsulta("
    SELECT * FROM parametros
");

$parametro = array();

foreach($resultado_parametros as $fila){
    $parametro[trim($fila['parametro'])] = trim($fila['valor']);
}

#######################################

# Si tenemos una sesion abierta, incluimos los modulos del sistema
if( $session->checkSession() ){
    
    # Validacion del tiempo de sesion

    if(isset($_SESSION['fechaSesion'])){
        $fechaGuardada = $_SESSION['fechaSesion'];
        $ahora = date('Y-m-d H:i:s');

        $tiempo_transcurrido = (strtotime($ahora)-strtotime($fechaGuardada));

        if($tiempo_transcurrido >= ($parametro['timeout'] * 60)){
            $session->endSession();
            header("Refresh:0");
            exit();

        }else{
            $_SESSION['fechaSesion'] = date('Y-m-d H:i:s');
        }
       
    }else{
        $_SESSION['fechaSesion'] = date('Y-m-d H:i:s');
    }

    echo "<pre>";
    print_r($_SESSION['fechaSesion']);
    echo "</pre>";

    // URL por defecto
    $pagina = $parametro['paginadefecto'];

    if( isset($_GET['pagina']) && !empty($_GET['pagina']) ){
        $pagina = $_GET['pagina'];
    }

    // Traer los permisos de la pagina para el usuario

    $resultado_permisos = $conexion->ejecutarConsulta("
        SELECT a.idmenu, b.nombre, b.ventana, b.framework
        FROM usuarios_accesos AS a
        INNER JOIN menu AS b ON ( a.idmenu = b.idmenu )
        WHERE a.usuario = '".$_SESSION['usuario']."'
        AND b.ventana = '".$pagina."'
        AND b.estado = 'ACTIVO'
    ");

    $varAcceso = array();

    foreach($resultado_permisos as $fila){
        $varAcceso['idmenu'] = $fila['idmenu'];
        $varAcceso['nombre'] = $fila['nombre'];
        $varAcceso['ventana'] = $fila['ventana'];
        $varAcceso['framework'] = explode(",",$fila['framework']);
    }
    
    # En caso de que el usuario no tenga permiso, verificamos en cual menu lo tiene

    if( count($varAcceso) == 0 ){

        $flagAccPagina = false;

        $resultadoAccpagina = $conexion->ejecutarConsulta("
            SELECT a.ventana 
            FROM menu AS a 
            INNER JOIN usuarios_accesos AS b ON (a.idmenu = b.idmenu)
            WHERE b.usuario = '".$_SESSION['usuario']."'
            AND a.estado = 'ACTIVO'
            AND a.es_menu = 'NO'
            ORDER BY a.idpadre, a.orden
            LIMIT 1
        ");

        foreach($resultadoAccpagina as $fila){
            $pagina = $fila['ventana'];
            $flagAccPagina = true;
        }
      
        if( $flagAccPagina == false ){
            $session->endSession();
            echo "Estimado, usted no tiene modulos asignados en el aplicativo, favor contactar con el administrador del sistema";
            header("Refresh:10");
            exit;
        }else{
            $resultadoVeri = $conexion->ejecutarConsulta("
                SELECT a.idmenu, b.nombre, b.ventana, b.framework
                FROM usuarios_accesos AS a
                INNER JOIN menu AS b ON ( a.idmenu = b.idmenu )
                WHERE a.usuario = '".$_SESSION['usuario']."'
                AND b.ventana = '".$pagina."'
                AND b.estado = 'ACTIVO'
            ");

            foreach($resultadoVeri as $fila){
                $varAcceso['idmenu'] = $fila['idmenu'];
                $varAcceso['nombre'] = $fila['nombre'];
                $varAcceso['ventana'] = $fila['ventana'];
                $varAcceso['framework'] = explode(",",$fila['framework']);
            }
        }

    }

    if( count($varAcceso) > 0 ){
        $conexion->ejecutarConsulta("
            INSERT INTO log_menu
            (ip, navegador, usuario, idmenu, nombre, ventana, fecha) 
            VALUES 
            ('".Funciones::ObtenerIp()."','".Funciones::ObtenerNavegador($_SERVER['HTTP_USER_AGENT'])."',
            '".$_SESSION['usuario']."','".$varAcceso['idmenu']."',
            '".$varAcceso['nombre']."','".$varAcceso['ventana'] ."',NOW())
        ");
    }

    # Obtener el menu, segun jerarquias

    $resultadoMenu = $conexion->ejecutarConsulta("
        SELECT a.idmenu, a.nombre, a.idpadre, a.ventana, a.es_menu, a.icono
        FROM menu AS a
        LEFT JOIN usuarios_accesos AS b ON (a.idmenu = b.idmenu)
        WHERE b.usuario = '".$_SESSION['usuario']."'
        AND a.estado = 'ACTIVO'
        AND a.idpadre IS NOT NULL
        ORDER BY a.orden
    ");

    $vectorMenu = array();
    $conVecMenu = 0;

    foreach($resultadoMenu as $fila){
        $vectorMenu[$conVecMenu]['idmenu'] = $fila['idmenu'];
        $vectorMenu[$conVecMenu]['nombre'] = $fila['nombre'];
        $vectorMenu[$conVecMenu]['idpadre'] = $fila['idpadre'];
        $vectorMenu[$conVecMenu]['ventana'] = $fila['ventana'];
        $vectorMenu[$conVecMenu]['es_menu'] = $fila['es_menu'];
        $vectorMenu[$conVecMenu]['icono'] = $fila['icono'];
        $conVecMenu++;
    }

    $idpadre_in = '';

    for($u = 0; $u < count($vectorMenu); $u++){
        if($u == 0){
            $idpadre_in .= $vectorMenu[$u]['idpadre'];
        }else{
            $idpadre_in .= ','.$vectorMenu[$u]['idpadre'];
        }
    }

    if( !empty($idpadre_in) ){
        $resultadoMenuPadre = $conexion->ejecutarConsulta("
            SELECT a.idmenu, a.nombre, a.idpadre, a.ventana, a.es_menu, a.icono
            FROM menu AS a
            WHERE a.idmenu IN (".$idpadre_in.")
            AND a.estado = 'ACTIVO'
            ORDER BY a.orden
        ");

        foreach($resultadoMenuPadre as $fila){
            $vectorMenu[$conVecMenu]['idmenu'] = $fila['idmenu'];
            $vectorMenu[$conVecMenu]['nombre'] = $fila['nombre'];
            $vectorMenu[$conVecMenu]['idpadre'] = $fila['idpadre'];
            $vectorMenu[$conVecMenu]['ventana'] = $fila['ventana'];
            $vectorMenu[$conVecMenu]['es_menu'] = $fila['es_menu'];
            $vectorMenu[$conVecMenu]['icono'] = $fila['icono'];
            $conVecMenu++;
        }
    }
  
    # Inlcusion de los archivos

    include('inc/header.php');
    include('inc/'.$pagina.'.php');
    include('inc/footer.php');

}else{
    // Si no tenemos una sesion iniciada, mostramos el login del sistema
    include('inc/login.php');
}
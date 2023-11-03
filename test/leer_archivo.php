<?php
// Nombre del archivo de texto
$nombreArchivo = 'chat.txt';

// Manejar errores de apertura de archivo
$archivo = fopen($nombreArchivo, 'a+');
if (!$archivo) {
    die('No se pudo abrir el archivo.');
}

// Verificar si se ha enviado un formulario para escribir en el archivo
if (isset($_POST['texto'])) {
    // Obtener el texto del formulario
    $texto = $_POST['texto'];

    // Escribir el texto en el archivo
    fwrite($archivo, $texto . PHP_EOL);

    // Cerrar el archivo
    fclose($archivo);

    echo 'Texto escrito exitosamente.';
}

// Leer y mostrar el contenido del archivo
echo '<h2>Contenido del archivo:</h2>';
echo '<pre>';
while (!feof($archivo)) {
    $linea = fgets($archivo);
    echo htmlspecialchars($linea);
}
echo '</pre>';

// Cerrar el archivo
fclose($archivo);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Leer y Escribir Archivo de Texto</title>
</head>
<body>
    <h1>Escribir en el Archivo</h1>
    <form method="post" action="">
        <label for="texto">Texto a escribir:</label><br>
        <textarea name="texto" rows="4" cols="50"></textarea><br>
        <input type="submit" value="Escribir en el archivo">
    </form>
</body>
</html>

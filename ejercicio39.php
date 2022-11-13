<?php

$nombreArchivo="archivo.txt";

$contenidoArchivo="Hola, saludos";

$archivoAcrear= fopen($nombreArchivo,"w");

fwrite($archivoAcrear,$contenidoArchivo); //crea y escribe el archivo nuevo

fclose($archivoAcrear); //cierra el archivo para que pueda abrirlo después

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escritura de archivos</title>
</head>
<body>
    
</body>
</html>
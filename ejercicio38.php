<?php

$archivo="contenido.txt";

$archivoAbierto=fopen($archivo,"r"); //fopen abre el archivo

$contenido=fread($archivoAbierto,filesize($archivo)); //fread es para leer el contenido

echo $contenido;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abrir un archivo PHP</title>
</head>
<body>


</body>
</html>
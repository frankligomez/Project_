<?php

$jsonContenido='[
    {"nombre":"Frankli", "apellido":"Gomez"},
    {"nombre":"Pedro", "apellido":"Perez"}
    ]';

    $resultado= json_decode($jsonContenido);
    //print_r($resultado);

    foreach($resultado as $persona){
        echo ($persona->nombre)." ".($persona->apellido)."<br/>";


    }

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Función para JSON decode</title>
</head>
<body>
    
   
</body>
</html>
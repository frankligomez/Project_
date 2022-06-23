<?php

session_start( );

$_SESSION["usuario"]="desarrollo";
$_SESSION["estatus"]="logeado";

echo "Sesión iniciada".":<br/>";

echo "Usuario: ".$_SESSION["usuario"].". Estatus: ".$_SESSION["estatus"];


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Variables de sesión en PHP</title>
</head>
    <body>

    </body>
</html>
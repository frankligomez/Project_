<?php //Consultar clases en PHP: https://www.php.net/manual/es/ref.array.php

$servidor="localhost"; // 1276.0.0.1 IP del servidor local
$usuario="root";
$contrasena="";

try{ // permite controlar errores

    $conexion=new PDO("mysql:host=$servidor;dbname=album", $usuario,$contrasena);
    $conexion->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    
    $sql="INSERT INTO `fotos` (`id`, `nombre`, `ruta`) VALUES (NULL, 'Jugando con la programación', 'foto.jpg');";

    $conexion->exec($sql);
    
    echo "Conexión establecida";

}catch(PDOException $error){
    echo "Conexión erronea".$error;
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PhpMyadmin Crear BD</title>
</head>
    <body>

    </body>
</html>
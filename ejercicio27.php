<?php //Consultar clases en PHP: https://www.php.net/manual/es/ref.array.php

class Unaclase{
    public static function unMetodo(){
        echo "Hola soy un método estático ";

    }
}

$obj=new UnaClase();
$obj->unMetodo();

unaClase::unMetodo();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Métodos estáticos en PHP</title>
</head>
    <body>

    </body>
</html>
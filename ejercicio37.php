<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Función Require</title>
</head>
<body>
    
    <?php require_once ("ejercicio37_1.php"); //Si existe un error con el nombre del archivo, NO mustra el echo.?>
    <?php require_once ("ejercicio37_1.php"); //Si el archivo está ducplicado por error, solo muestra el primero.?>
    </br>
    <?php echo "Hola, estoy en la página principal"; ?>

</body>
</html>
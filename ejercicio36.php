<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Función Include</title>
</head>
<body>
    
    <?php include_once 'ejercicio36_1.php'; //Si existe un error con el nombre del archivo, continua mostrando el echo.?> 
    <?php include_once 'ejercicio36_1.php'; //El include_once no muestra el archivo duplicado si por error se coloca.?> 
    </br>
    <?php echo "Hola, estoy en la página principal"; ?>

</body>
</html>


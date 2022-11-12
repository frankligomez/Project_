<?php

    if($_POST){
        print_r($_POST);

        print_r($_FILES['archivo']['name']); //Así se muestra la infor del archivo que se va a cargar

        move_uploaded_file($_FILES['archivo']['tmp_name'],$_FILES['archivo']['name']); //Así se mueve el archivo al aservidor

    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <litle>Formulario Valores Input File - Guardar archivo</litle>
    
</head>
<body>

    <form action="ejercicio32.php" enctype="multipart/form-data" method="post">
        Imagen:
        <input type="file" name="archivo" id="">
        <br/>
        <input type="submit" name="Enviar" value="Enviar información">

    </form>

</body>
</html>
<?php 

session_start();

if($_POST){
    if( ($_POST['usuario']=="frago") && ($_POST['contrasenia']=="12345") ){

            $_SESSION['usuario']="frago";

        header("location:index.php"); //Redirecciona al index después de ingresar usuario y contrasenia
    } else {
        echo "<script> alert('Usuario o contraseña incorrecta'); </script>"; //Mensaje de error al no poder ingresar
        }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <!--Requiere meta tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <!--Boosatrap CSS v5.0.2 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

</head>
<body>

    <div class="container">

        <div class="row">
            <div class="col-md-4">

            </div>
            <div class="col-md-4">
                <br>
                <div class="card">
                    <div class="card-header">
                        Iniciar sesión
                    </div>
                    <div class="card-body">
                        <form action="login.php" method="post">
                            </br>
                            Usuario: <input class="form-control" type="text" name="usuario" id="">
                            </br>
                            Contraseña: <input class="form-control" type="password" name="contrasenia" id="">
                            </br>
                            <button class="btn btn-success" type="submit">Entrar al portafolio</button>
                        </form>
                    </div>
                    <div class="card-footer text-muted">
                    </div>
                </div>
            </div>
                <div class="col-md-4">

                </div>
        </div>

        

    </div>
                

           


   

</body>
</html>
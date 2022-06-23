<?php

session_start();

if(isset($_SESSION["usuario"])){

echo "Usuario: ".$_SESSION["usuario"]." estatus: ".$_SESSION["estatus"];

} else{

        echo"No hay datos"; // Mensaje que indica que ya se cerro la sesión y que no hay datos disponibles.

}

?>
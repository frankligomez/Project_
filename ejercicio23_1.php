<?php

$frutas=array("f"=>"Fresa", "p"=>"Pera", "m"=>"Manzana", "b"=>"Banana");

print_r($frutas);

echo $frutas["m"]."<br/>";

foreach($frutas as $indice=>&$valor){

    echo "El valor ".$valor." tiene el índice: ". $indice."<br/>";
    

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leer arreglos en PHP</title>
</head>
    <body>
    
     

    </body>

</html>
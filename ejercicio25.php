<?php

//Consultar clases en PHP: https://www.php.net/manual/es/ref.array.php

class persona{

    public $nombre; // propiedades

    public function asignarNombre($nuevoNombre){  // acciones o métodos

        $this->nombre=$nuevoNombre;
    }

    public function imprimirNombre(){

        echo "Hola soy ".$this->nombre." ";

    } 
}

$objAlumno= new persona(); // crear objeto a partir de una clase, se llama Instancia
$objAlumno->asignarNombre("Frankli"); // llamando un método

$objAlumno2= new persona();
$objAlumno2->asignarNombre("Luis");
$objAlumno2->imprimirNombre();


echo $objAlumno->nombre."<br/>";  // imprimir una propiedad
echo $objAlumno2->nombre."<br/>";  // imprimir una propiedad


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visibilidad de datos con PHP</title>
</head>
    <body>
    
     

    </body>

</html>
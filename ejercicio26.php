<?php

//Consultar clases en PHP: https://www.php.net/manual/es/ref.array.php

class persona{

    public $nombre; //Propiedades
    private $edad;
    protected $altura;

    function __construct($nuevoNombre){
        $this->nombre=$nuevoNombre;
    }

    public function asignarNombre($nuevoNombre){  // Acciones o métodos...
        $this->nombre=$nuevoNombre;
    }

    public function imprimirNombre(){
        echo "Hola soy ".$this->nombre;
    }

    public function mostrarEdad(){
        $this->edad=20;
        return $this->edad;
    }
}

$objAlumno= new persona("Frankli Gomez"); // Instancia o creación de un objeto
//$objAlumno->asignarNombre("Frankli"); // Llamar método
$objAlumno->imprimirNombre();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Constructor en PHP</title>
</head>
    <body>

    </body>
</html>
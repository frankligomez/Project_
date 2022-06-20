<?php

//Consultar clases en PHP: https://www.php.net/manual/es/ref.array.php

class persona{

    public $nombre; // propiedades
    private $edad;
    protected $altura;

    public function asignarNombre($nuevoNombre){  // acciones o métodos

        $this->nombre=$nuevoNombre;
    }

    public function imprimirNombre(){

        echo "Hola soy ".$this->nombre." ";

    } 

    public function mostrarEdad(){
        $this->edad=20;
        return $this->edad;
    
    }
}

class trabajador extends persona{
    
    public $puesto; // propiedad nueva
    
    public function presentarseComoTrabajador(){
        echo "Hola, soy ".$this->nombre." y soy un ".$this->puesto;

    }
}

$objTrabajador= new trabajador(); // crear objeto a partir de una clase, se llama Instancia
$objTrabajador->asignarNombre("Frankli Gomez"); // llamando un método
$objTrabajador->puesto="Profesor"; // llamando un método

$objTrabajador->presentarseComoTrabajador();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Herencia en PHP</title>
</head>
    <body>
    
     

    </body>

</html>
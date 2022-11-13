<?php include("cabecera.php"); ?>
<?php include("conexion.php"); ?>
<?php

if($_POST){
    //print_r($_POST);
    $nombre=$_POST['nombre'];
    $descripcion=$_POST['descripcion'];
    $fecha= new DateTime();
    $imagen=$fecha->getTimestamp()."_".$_FILES['archivo']['name']; //Agrega datos de tiempo al nombre del archivo para diferenciarlo si se vuelve al cargar el mismo archivo 
    $imagen_temporal=$_FILES['archivo']['tmp_name']; //Variable que al mancena los datos del archivo a cargar
    move_uploaded_file($imagen_temporal,"imagenes/".$imagen); //Método que cargar archvio en la carpeta imagenes creada con anterioridad

    $objConexion= new conexion(); //Se crear la conexión con el constructor de la conexión a la DB
    $sql="INSERT INTO `proyectos` (`id`, `nombre`, `imagen`, `descripcion`) VALUES (NULL,'$nombre','$imagen','$descripcion');";
    $objConexion->ejecutar($sql);
    header("location:portafolio.php"); // Sirve para que el usuario no pueda actualizar la pantalla cuando inserte

}

if($_GET){
    $id=$_GET['borrar'];
    $objConexion= new conexion(); //Se crear la conexión con el constructor de la conexión a la DB
    $imagen=$objConexion->consultar("SELECT imagen FROM `proyectos` WHERE id=".$id);
    unlink("imagenes/".$imagen[0]['imagen']); //Ejecuta el borrado del archivo
    $sql="DELETE FROM `proyectos` WHERE `proyectos`.`id` =".$id;
    $objConexion->ejecutar($sql);
    header("location:portafolio.php");  // Sirve para que el usuario no pueda actualizar la pantalla cuando inserte

}

    $objConexion= new conexion(); //Se crear la conexión con el constructor de la conexión a la DB
    $proyectos=$objConexion->consultar("SELECT * FROM `proyectos`");
    
    

?>
</br>


<div class="container">
    <div class="row">
        <div class="col-md-6">
            
        <div class="card">
    <div class="card-header">
        Datos del proyecto
    </div>
    <div class="card-body">
        
        <form action="portafolio.php" method="post" enctype="multipart/form-data">

            Nombre del proyecto: <input required class="form-control" type="text" name="nombre" id="">
            </br>
            Imagen del proyecto: <input required class="form-control" type="file" name="archivo" id="">
            </br>
            Descripción: <textarea class="form-control" name="descripcion" id="" rows="5"></textarea>
            </br>

            <input class="btn btn-success" type="submit" value="Enviar proyecto">

        </form>

    </div>
    
</div>
        
        </div>
        
        <div class="col-md-6">
           
        <div class="table-responsive">
            <table class="table table-primary">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Proyecto</th>
                        <th scope="col">Imagen</th>
                        <th scope="col">Descripcion</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($proyectos as $proyecto) {  //Recorre la BD y retorna proyecto por proyecto para mostrar cada uno de los registros que tenga la DB  ?> 
                    <tr class="">
                        <td scope="row"> <?php echo $proyecto['id']; ?> </td>
                        <td> <?php echo $proyecto['nombre']; ?> </td>
                        <td> 
                            <img width="100" src="imagenes/<?php echo $proyecto['imagen']; ?>" alt="" srcset="">

                        <td> <?php echo $proyecto['descripcion']; ?> </td>
                        <td> <a class="btn btn-danger" href="?borrar=<?php echo $proyecto['id']; ?>" >Eliminar</a> </td> 
                    </tr>
                    <?php } ?>

                </tbody>
            </table>
        </div>

        </div>
        
    </div>
</div>


<?php include("pie.php"); ?>


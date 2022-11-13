<?php include("cabecera.php"); ?>
<?php include("conexion.php"); ?>

$objConexion= new conexion();

$sql="INSERT INTO `proyectos` (`id`, `nombre`, `imagen`, `descripcion`) VALUES (NULL, 'Proyecto 12', 'Imagen.jpg', 'Es un proyecto de hace mucho tiempo.');";

$ObjConexion->ejecutar(sql);

</br>

<div class="container">
    <div class="row">
        <div class="col-md-6">
            
        <div class="card">
    <div class="card-header">
        Datos del proyecto
    </div>
    <div class="card-body">
        
        <form action="portafolio.php" method="post"> enctype="multipart/form-data";

            Nombre del proyecto: <input class="form-control" type="text" name="nombre" id="">
            </br>
            Imagen del proyecto: <input class="form-control" type="file" name="archivo" id="">
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
                    </tr>
                </thead>
                <tbody>
                    <tr class="">
                        <td scope="row">3</td>
                        <td>Aplicación web</td>
                        <td>Imagen</td>
                    </tr>
                    
                </tbody>
            </table>
        </div>

        </div>
        
    </div>
</div>









<?php include("pie.php"); ?>


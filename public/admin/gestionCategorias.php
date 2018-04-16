<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>Gestor de categorías</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/fontawesome-all.css">
    <link rel="stylesheet" href="../css/style.css">

    <?php
    session_start();
    if (!isset($_SESSION["login"]) || $_SESSION["login"] != true) {
        header("Location: index.php", true, 302);
        die();
    }

    include_once '../../DB/conexion.php';

    include_once 'slug.php';
    ?>



</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <header class="col-12">
                <div class="row">
                    <div class="col-8">
                        <h1 class="pt-3">Bienvenido, <?= $_SESSION["username"] ?></h1>
                    </div>
                    <div class="col-4 text-right mt-3">                  
                        <a href="logout.php" class="align-middle"><i class="fas fa-sign-out-alt"></i> Cerrar sesion</a>
                    </div>
                </div>
            </header>
        </div>
    </div>

       
    <div>
        <?php
            if(isset($_SESSION["flash_message"])) {
                echo '<p class="text-center flash ' . $_SESSION["flash_type"] . '">' . $_SESSION["flash_message"] . '</p>';
                unset($_SESSION["flash_type"]);
                unset($_SESSION["flash_message"]);
            }
        ?>
    </div>
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-5 text-left">
                <i class="fas fa-home fa-2x"></i><br />
                <a href="gestion.php">MENÚ PRINCIPAL</a>
            </div>
        </div> 
        <div class="row">
            <div class="col-s-12 col-md-9 mx-auto mt-4 text-center">
                <h2>Gestión de categorías</h2>
                <a href="crear-categoria.php" class="btn btn-primary text-center my-3" role="button">CREAR UNA NUEVA CATEGORÍA</a>

                <?php
                // vamos a listar las categorías que hay en la base de datos...
                $sql = "SELECT * FROM categoria";

                $result = mysqli_query($con, $sql);

                while ($categoria = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                ?>
                    <div class="row elemento">
                        <div class="col-9 text-left">
                            <span><?php echo $categoria["fecha"]; ?></span>
                            <p>Nombre: <?php echo $categoria["nombre"]; ?><br/>
                                URL: <?php echo $categoria["slug"]; ?></p>
                            </p>
                        </div>
                        <div class="col-3">
                            <div class="row">
                                <div class="col-6">
                                    <a href="modificar-categoria.php?id=<?php echo $categoria["idcategoria"]; ?>" class="btn-modificar btn btn-primary btn-block" role="button"><i class="far fa-edit fa-3x"></i><br />Modificar</a>
                                </div>
                                <div class="col-6 my-auto">
                                    <a href="borrar-categoria.php?id=<?php echo $categoria["idcategoria"]; ?>" class="confirmacion btn-borrar btn btn-primary btn-block " role="button"><i class="far fa-trash-alt fa-3x"></i><br />Eliminar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
    <script src="../js/jquery-3.3.1.min.js"></script>

    <script type="text/javascript">
        var elemento = document.getElementsByClassName('confirmacion');
        var confirmar = function (e) {
            if (!confirm('¿Seguro que quieres eliminar permanentemente la categoría? \n Esta acción no se puede deshacer.')) e.preventDefault();
        };
        for (var i = 0, l = elemento.length; i < l; i++) {
            elemento[i].addEventListener('click', confirmar, false);
        }


    </script>
    <script type="text/javascript">
        $('.flash').delay(5000).fadeOut( "slow" );
    </script>
</body>
</html>




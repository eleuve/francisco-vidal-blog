<?php

include_once '../../DB/conexion.php';


if (isset($_GET["id"])) {

    $sql = "SELECT * from post WHERE idpost = " . $_GET["id"];

    $titulo = $_POST["titulo"];
    $entradilla = $_POST["entradilla"];
    $file = $_FILES["imagen"];
    $html = $_POST["entrada"];
    $esPublico = "false";
    $altimagen = $_POST["altimagen"];

// IF que solo se ejecuta si hay POST enviar (es el submit input)
    if (isset($_POST["enviar"])) {
        /**
         * @todo Asegurarnos que las 6 variables siguientes son aptas, es decir, not-empty o... con valores raros o SQL code (injection...)
         */
        $titulo = $_POST["titulo"];
        $entradilla = $_POST["entradilla"];
        $file = $_FILES["imagen"];
        $html = $_POST["entrada"];
        $esPublico = "false";
        $altimagen = $_POST["altimagen"];

        if (isset($_POST["publico"])) {
            if ($_POST["publico"] == "público") {
                $esPublico = "true";
            }
        }

        /** Guardado del archivo en la carpeta /uploads */
        // El archivo se sube a una ruta temporal de PHP (a saber cual), no hay funcion MOVE en php, asi que leemos el contenido del archivo...
        $imageBinaryData = file_get_contents($file["tmp_name"]);
        // Crea el archivo en uploads, require 2 cosas: nombre de archivo, contenido
        file_put_contents('../uploads/' . $file["name"], $imageBinaryData);

        /** Como todo es correcto (validado empty & injections), ejecutamos un INSERT... */

        // Necesitamos la variable que nos facilita conexion de la base de datos: $con
        require_once "../../DB/conexion.php";

        // Preparamos la fecha para el SQL
        $datetimeHoy = new \Datetime("now");
        $fechaHoy = date("Y-m-d H:i:s", $datetimeHoy->getTimestamp());

        // Preparamos el SQL
        $sql = sprintf(
            "INSERT INTO `post` (`idpost`, `titulo`, `entradilla`, `contenido`, `fecha`, `categoria`, `imagen`, `activo`, `altimagen`) VALUES (%s, '%s', '%s', '%s', '%s', '%s', '%s', %s, '%s')",
            "NULL",
            $titulo,
            $entradilla,
            $html,
            $fechaHoy,
            "Categoria..",
            $file["name"],
            $esPublico,
            $altimagen
        );

        // Ejecutamos el SQL con la respectiva conexion ($con)
        $resultadoDelQuery = mysqli_query($con, $sql);

        // la variable que esto devuelve no la necesitamos para nada  de momento pero ahi queda... servira para ver si no ha guardado el INSERT,
        // mostrar un mensaje rollo 'Intentelo de nuevo mas tarde o contacte con el administrador'

        // si hay error de cualquier tipo, mostramos un mensaje, en caso contrario mostramos otro
        $mensaje = "Post creado correctamente: " . $_POST["titulo"];
        if (mysqli_errno($con)) {
            print_r(mysqli_error($con));
            $mensaje = "Inténtelo de nuevo más tarde o contacte con el administrador.";
        }

        echo "<h1>" . $mensaje . "</h1>";

        // Cerramos la conexion porque hemos acabado
        mysqli_close($con);
    }
}

?>


<form action="modificar-post.php" method="post" enctype="multipart/form-data">
    <label for="">Título:</label>
    <input type="text" name="titulo"><br />

    <label for="">Entradilla:</label>
    <input type="text" name="entradilla"><br />

    <label for="">Imagen:</label>
    <input type="file" name="imagen" /><br />

    <label for="">Alt de la imagen:</label>
    <input type="text" name="altimagen" /><br />

    <label for="">Entrada:</label>
    <textarea name="entrada" id="" cols="30" rows="40"></textarea><br />

    <input type="checkbox" name="publico" value="público" name="publico">Público<br><br />

    <input type="submit" value="Enviar" name="enviar">
</form>


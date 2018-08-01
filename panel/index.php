<?php
if (!isset($_SESSION)) {
    session_start();
}

require_once('../Connections/rural.php');

if (isset($_POST['acceder'])) {

    if ($rural ){
        $usuario = $_POST['usuario'];
        $pass = $_POST['contra'];
        $search = mysqli_query($rural, "SELECT * FROM personal WHERE usuario='$usuario'");
        $numf = mysqli_num_rows($search);
        $resultadoUsu = mysqli_fetch_assoc($search);


        if ($numf === 1) {
            $_SESSION['MM_Username'] = $usuario;
            $_SESSION['MM_UserGroup'] = "";
            $_SESSION['nombreUsu'] = $resultadoUsu['nombre'];
            $_SESSION['rangoUsu'] = $resultadoUsu['rango'];
            $_SESSION['idUsu'] = $resultadoUsu['id_personal'];

            header('location: inicio.php');
        } else {
            echo "<p align='center'  style='color:red;'> El Usuario/Contraseña no existe </p>";
        }
        mysqli_close($rural);
        mysqli_free_result($search);
    }
}
?>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Inicio de Sesion: La Caraba</title>
        <link rel="stylesheet" href="css/index.css" />
    </head>

    <body><br />
        <br />

        <form action="" method="POST">
            <fieldset>
                <legend>Identificate</legend>
                <label for="usuario">Usuario:</label>
                <input name="usuario" type="text"/>

                <label for="contra">Contraseña:</label>
                <input name="contra" type="password" />

                <br />

                <input type="submit" class="button" name="acceder" value="Acceder" />
            </fieldset>
        </form>

</html>

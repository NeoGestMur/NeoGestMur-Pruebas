<!DOCTYPE html>
<?php
if (!isset($_SESSION)) {
    session_start();
}

require_once('Connections/rural.php');

if (isset($_POST['acceder'])) {

    if ($rural) {
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

            header('location: ./panel/inicio.php');
        } else {
            echo "<p align='center'  style='color:red;'> El Usuario/Contraseña no existe </p>";
        }
        mysqli_close($rural);
        mysqli_free_result($search);
    }
}
?>
<html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>NeoGest - Login</title>
        <!-- Bootstrap Styles-->
        <link href="assets/css/bootstrap.css" rel="stylesheet" />
        <!-- FontAwesome Styles-->
        <link href="assets/css/font-awesome.css" rel="stylesheet" />
        <!-- Morris Chart Styles-->
        <link href="assets/js/morris/morris-0.4.3.min.css" rel="stylesheet" />
        <!-- Custom Styles-->
        <link href="assets/css/style_firstform.css" rel="stylesheet" />
        <!-- Google Fonts-->
        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' /> 

    </head>

    <body>
        <div id="wrapper">
            <div id="page-wrapper">
                <div id="page-inner">
                    <div class="row">
                        <div class="col-md-12">
                            <h1 class="page-header">
                                Login <small>NeoGest</small>
                            </h1>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <form role="form" action="" method="POST">
                                            <div class="form-group">
                                                <label>Usuario:</label>
                                                <input class="form-control" type="text" name="usuario" placeholder="Introduzca su usuario">
                                            </div>
                                            <div class="form-group">
                                                <label>Password:</label>
                                                <input class="form-control" type="password" name="contra" placeholder="Introduzca su contraseña">
                                            </div>
                                            <button type="submit" name="acceder" class="btn btn-default">Acceder</button>
                                            <button type="reset" class="btn btn-default">Limpiar campos</button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <footer><p>NeoGest Panel</a></p>


                            </footer>
                        </div>
                        <!-- /. PAGE INNER  -->
                    </div>
                    <!-- /. PAGE WRAPPER  -->
                </div>
                <!-- /. WRAPPER  -->
                <!-- JS Scripts-->
                <!-- jQuery Js -->
                <script src="assets/js/jquery-1.10.2.js"></script>
                <!-- Bootstrap Js -->
                <script src="assets/js/bootstrap.min.js"></script>

                <!-- Metis Menu Js -->
                <script src="assets/js/jquery.metisMenu.js"></script>
                <!-- Morris Chart Js -->
                <script src="assets/js/morris/raphael-2.1.0.min.js"></script>
                <script src="assets/js/morris/morris.js"></script>


                <script src="assets/js/easypiechart.js"></script>
                <script src="assets/js/easypiechart-data.js"></script>

                <script src="assets/js/Lightweight-Chart/jquery.chart.js"></script>

                <!-- Custom Js -->
                <script src="assets/js/custom-scripts.js"></script>


                </body>

                </html>
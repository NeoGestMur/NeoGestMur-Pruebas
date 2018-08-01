<?php
require_once('archivos/funcion.php');
require_once('../Connections/rural.php');
if ($_SESSION['rangoUsu'] == 1) {
    if (!function_exists("GetSQLValueString")) {

        function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") {
            if (PHP_VERSION < 6) {
                $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
            }

            switch ($theType) {
                case "text":
                    $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
                    break;
                case "long":
                case "int":
                    $theValue = ($theValue != "") ? intval($theValue) : "NULL";
                    break;
                case "double":
                    $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
                    break;
                case "date":
                    $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
                    break;
                case "defined":
                    $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
                    break;
            }
            return $theValue;
        }

    }

    $editFormAction = $_SERVER['PHP_SELF'];
    if (isset($_SERVER['QUERY_STRING'])) {
        $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
    }

    if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {

        $nombre = trim($_POST['nombre']);
        if (empty($nombre))
            $error['nombre'] = 'Por favor inserte el nombre';

        if (!isset($error)) {
            $insertSQL = sprintf("INSERT INTO personal (nombre, apellidos) VALUES (%s, %s)", GetSQLValueString($nombre, "text"), GetSQLValueString($_POST['apellidos'], "text"));

            mysqli_select_db($rural, $database_rural);
            $Result1 = mysqli_query($rural, $insertSQL) or die(mysql_error());

            $insertGoTo = "empleados_lista.php";
            if (isset($_SERVER['QUERY_STRING'])) {
                $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
                $insertGoTo .= $_SERVER['QUERY_STRING'];
            }
            header(sprintf("Location: %s", $insertGoTo));
        }
    }
    ?>
    <!DOCTYPE HTML>
    <html>
        <head>
            <meta charset="utf-8" />
            <title>Panel de control: La Caraba</title>
            <link href="css/estilos.css" rel="stylesheet" type="text/css" />
        </head>

        <body>
            <div id="contenido">
                <div id="menu"><?php include('archivos/menu.html'); ?><div class="limpiar"></div></div>
                <div class="caja_frm">
                    <h1>Añadir empleado </h1>
                    <form method="post" name="form1" action="<?php echo $editFormAction; ?>">
                        <table align="center">
                            <tr valign="baseline">
                                <td nowrap align="right">Nombre:</td>
                                <td><input type="text" name="nombre" value="<?php if (isset($nombre)) echo $nombre; ?>" size="32"><?php if (isset($error['nombre'])) echo '<br /><span class="error">' . $error['nombre'] . '</span>'; ?></td>
                            </tr>
                            <tr valign="baseline">
                                <td nowrap align="right">Apellidos:</td>
                                <td><input name="apellidos" type="text" value="<?php if (isset($_POST['apellidos'])) echo $_POST['apellidos']; ?>" size="32"></td>
                            </tr>
                            <tr valign="baseline">
                                <td nowrap align="right">&nbsp;</td>
                                <td><input type="submit" value="Insertar registro" class="bton-form"></td>
                            </tr>
                        </table>
                        <input type="hidden" name="MM_insert" value="form1">
                    </form>
                </div>
            </div>
        </body>
    </html>
    <?php
}
?>
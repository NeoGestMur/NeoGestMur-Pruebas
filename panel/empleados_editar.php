<?php
require_once('archivos/funcion.php');
require_once('../Connections/rural.php');

if (isset($_POST['aceptar_listado']) || $_SESSION['rangoUsu'] == 1) {

    $colname_empleados = "-1";
    if (!isset($_POST['id_e'])) {
        $colname_empleados = $_SESSION['idUsu'];
    } else {
        $colname_empleados = $_POST['id_e'];
    }

    if (isset($_POST['submit'])) {
        if (isset($_POST['nombre']) && $_POST['nombre'] != $_SESSION['nombreUsu']) {
            $_SESSION['nombreUsu'] = $_POST['nombre'];
        }
    }

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

    if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
        $updateSQL = sprintf("UPDATE personal SET nombre=%s, apellidos=%s WHERE id_personal=%s", GetSQLValueString($_POST['nombre'], "text"), GetSQLValueString($_POST['apellidos'], "text"), GetSQLValueString($_POST['id_personal'], "int"));

        mysqli_select_db($rural, $database_rural);
        $Result1 = mysqli_query($rural, $updateSQL) or die(mysql_error());

        $updateGoTo = "empleados_lista.php";
        if (isset($_SERVER['QUERY_STRING'])) {
            $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
            $updateGoTo .= $_SERVER['QUERY_STRING'];
        }
        header(sprintf("Location: %s", $updateGoTo));
    }


    mysqli_select_db($rural, $database_rural);
    $query_empleados = sprintf("SELECT * FROM personal WHERE id_personal = %s", GetSQLValueString($colname_empleados, "int"));
    $empleados = mysqli_query($rural, $query_empleados) or die(mysql_error());
    $row_empleados = mysqli_fetch_assoc($empleados);
    $totalRows_empleados = mysqli_num_rows($empleados);
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

                    <h1>Editar cliente </h1>
                    <form method="post" name="form1" action="<?php echo $editFormAction; ?>">
                        <table align="center">
                            <tr valign="baseline">
                                <td nowrap align="right">Nombre:</td>
                                <td><input type="text" name="nombre" value="<?php echo htmlentities($row_empleados['nombre'], ENT_COMPAT, 'iso-8859-1'); ?>" size="32"></td>
                            </tr>
                            <tr valign="baseline">
                                <td nowrap align="right">Apellidos:</td>
                                <td><input type="text" name="apellidos" value="<?php echo htmlentities($row_empleados['apellidos'], ENT_COMPAT, 'iso-8859-1'); ?>" size="32"></td>
                            </tr>
                            <tr valign="baseline">
                                <td nowrap align="right">&nbsp;</td>
                                <td><input type="submit" value="Actualizar registro" name="submit" class="bton-form"></td>
                            </tr>
                        </table>
                        <input type="hidden" name="MM_update" value="form1">
                        <input type="hidden" name="id_personal" value="<?php echo $row_empleados['id_personal']; ?>">
                    </form>
                </div>
            </div>
        </body>
    </html>
    <?php
    mysqli_free_result($empleados);
}


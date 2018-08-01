<?php
require_once('archivos/funcion.php');
require_once('../Connections/rural.php');
require_once('archivos/funciones_frm.php');


$editFormAction = $_SERVER['PHP_SELF'];

if (isset($_SERVER['QUERY_STRING'])) {

    $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}



if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {



    // comprobar datos

    $plazas = trim($_POST['plazas']);
    if (empty($plazas))
        $error['plazas'] = 'Por favor inserte el n&uacute;mero de plazas';

    $nombre = trim($_POST['nombre']);
    if (empty($nombre))
        $error['nombre'] = 'Por favor inserte un nombre';

    $tarifa_d_b = trim($_POST['tarifa_d_b']);
    if (empty($tarifa_d_b))
        $error['tarifa_d_b'] = 'Por favor inserte una tarifa';

    $tarifa_d_f = trim($_POST['tarifa_d_f']);
    if (empty($tarifa_d_f))
        $error['tarifa_d_f'] = 'Por favor inserte una tarifa';

    if (!isset($error)) {

        $sql = sprintf("INSERT INTO casa (plazas, nombre, tarifa_d_b, tarifa_d_f, cama, cuna, sofa) VALUES (%s, %s, %s, %s, %s, %s, %s)", GetSQLValueString($plazas, "int"), GetSQLValueString($nombre, "text"), GetSQLValueString($tarifa_d_b, "double"), GetSQLValueString($tarifa_d_f, "double"), GetSQLValueString($_POST['cama'], "double"), GetSQLValueString($_POST['cuna'], "double"), GetSQLValueString($_POST['sofa'], "double"));



        mysqli_select_db($rural, $database_rural);

        $result = mysqli_query($rural, $sql) or die(mysql_error());



        $ir = "casas_lista.php";

        header(sprintf("Location: %s", $ir));
    }
}
?>


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

                <h1>Nueva casa</h1>

                <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">

                    <table align="center">

                        <tr valign="baseline">

                            <td nowrap="nowrap" align="right">Plazas:</td>

                            <td><input type="text" name="plazas" value="<?php if (isset($plazas)) echo $plazas; ?>" size="4" />

<?php if (isset($error['plazas'])) echo '<br /><span class="error">' . $error['plazas'] . '</span>'; ?>

                            </td>

                        </tr>

                        <tr valign="baseline">

                            <td nowrap="nowrap" align="right">Nombre:</td>

                            <td><input type="text" name="nombre" value="<?php if (isset($nombre)) echo $nombre; ?>" size="35" />

<?php if (isset($error['nombre'])) echo '<br /><span class="error">' . $error['nombre'] . '</span>'; ?>

                            </td>

                        </tr>

                        <tr valign="baseline">

                            <td colspan="2" align="center" nowrap="nowrap"><strong>Tarifas (IVA incluido)</strong></td>

                        </tr>

                        <tr valign="baseline">

                            <td nowrap="nowrap" align="right">Día semana:</td>

                            <td><input type="text" name="tarifa_d_b" value="<?php if (isset($tarifa_d_b)) echo $tarifa_d_b; ?>" size="32" />

                                &euro;<?php if (isset($error['tarifa_d_b'])) echo '<br /><span class="error">' . $error['tarifa_d_b'] . '</span>'; ?>

                            </td>

                        </tr>

                        <tr valign="baseline">

                            <td nowrap="nowrap" align="right">Día festivo:</td>

                            <td><input type="text" name="tarifa_d_f" value="<?php if (isset($tarifa_d_f)) echo $tarifa_d_f; ?>" size="32" />

                                &euro;<?php if (isset($error['tarifa_d_f'])) echo '<br /><span class="error">' . $error['tarifa_d_f'] . '</span>'; ?>

                            </td>

                        </tr>
                        <tr valign="baseline">
                            <td colspan="2" align="center" nowrap="nowrap"><strong>Suplementos</strong></td>
                        </tr>
                        <tr valign="baseline">
                            <td nowrap="nowrap" align="right">Sofá cama:</td>
                            <td><input name="sofa" type="text" id="sofa" value="0.00" size="5" /> 
                                &euro;</td>
                        </tr>
                        <tr valign="baseline">
                            <td nowrap="nowrap" align="right">Cama supletoria:</td>
                            <td><input name="cama" type="text" id="cama" value="0.00" size="5" /> 
                                &euro;</td>
                        </tr>
                        <tr valign="baseline">
                            <td nowrap="nowrap" align="right">Cuna:</td>
                            <td><input name="cuna" type="text" id="cuna" value="0.00" size="5" /> 
                                &euro;</td>
                        </tr>

                        <tr valign="baseline">

                            <td nowrap="nowrap" align="right">&nbsp;</td>

                            <td><input type="submit" value="Insertar registro" class="bton-form"/></td>

                        </tr>

                    </table>

                    <input type="hidden" name="MM_insert" value="form1" />

                </form>

            </div>

    </body>

</html>
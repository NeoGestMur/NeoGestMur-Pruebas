<?php
require_once('archivos/funcion.php');
require_once('../Connections/rural.php');
require_once('archivos/funciones_frm.php');

$editFormAction = $_SERVER['PHP_SELF'];

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {

    $updateSQL = sprintf("UPDATE casa SET plazas=%s, nombre=%s, tarifa_d_b=%s, tarifa_d_f=%s, sofa=%s, cama=%s, cuna=%s WHERE id_cas=%s", GetSQLValueString($_POST['plazas'], "int"), GetSQLValueString($_POST['nombre'], "text"), GetSQLValueString($_POST['tarifa_d_b'], "double"), GetSQLValueString($_POST['tarifa_d_f'], "double"), GetSQLValueString($_POST['sofa'], "double"), GetSQLValueString($_POST['cama'], "double"), GetSQLValueString($_POST['cuna'], "double"), GetSQLValueString($_POST['id_cas'], "int"));



    mysqli_select_db($rural, $database_rural);

    $Result1 = mysqli_query($rural, $updateSQL) or die(mysqli_error());



    $ir = "casas_lista.php";

    header(sprintf("Location: %s", $ir));
}



$id = "-1";

if (isset($_GET['id_cas'])) {

    $id = $_GET['id_cas'];
}

mysqli_select_db($rural, $database_rural);

$query_casa_edita = sprintf("SELECT * FROM casa WHERE id_cas = %s", GetSQLValueString($id, "int"));

$casa_edita = mysqli_query($rural, $query_casa_edita) or die(mysqli_error());

$row_casa_edita = mysqli_fetch_assoc($casa_edita);

$totalRows_casa_edita = mysqli_num_rows($casa_edita);
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

                <h1>Editar casa</h1>

                <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">

                    <table align="center">

                        <tr valign="baseline">

                            <td nowrap="nowrap" align="right">Plazas:</td>

                            <td><input type="text" name="plazas" value="<?php echo htmlentities($row_casa_edita['plazas'], ENT_COMPAT, 'iso-8859-1'); ?>" size="4" /></td>

                        </tr>

                        <tr valign="baseline">

                            <td nowrap="nowrap" align="right">Nombre:</td>

                            <td><input type="text" name="nombre" value="<?php echo $row_casa_edita['nombre'] ?>" size="35" /></td>

                        </tr>

                        <tr valign="baseline">

                            <td colspan="2" align="center" nowrap="nowrap"><strong>Tarifas (IVA incluido)</strong></td>

                        </tr>

                        <tr valign="baseline">

                            <td nowrap="nowrap" align="right">D&iacute;a semana:</td>

                            <td><input type="text" name="tarifa_d_b" value="<?php echo htmlentities($row_casa_edita['tarifa_d_b'], ENT_COMPAT, 'iso-8859-1'); ?>" size="32" />
                                &euro;</td>

                        </tr>

                        <tr valign="baseline">

                            <td nowrap="nowrap" align="right">D&iacute;a festivo:</td>

                            <td><input type="text" name="tarifa_d_f" value="<?php echo htmlentities($row_casa_edita['tarifa_d_f'], ENT_COMPAT, 'iso-8859-1'); ?>" size="32" />
                                &euro;</td>

                        </tr>
                        </tr>
                        <tr valign="baseline">
                            <td colspan="2" align="center" nowrap="nowrap"><strong>Suplementos</strong></td>
                        </tr>
                        <tr valign="baseline">
                            <td nowrap="nowrap" align="right">S&oacute;fa cama:</td>
                            <td><input name="sofa" type="text" id="sofa" value="<?php echo htmlentities($row_casa_edita['sofa'], ENT_COMPAT, 'iso-8859-1'); ?>" size="5" /> 
                                &euro;</td>
                        </tr>
                        <tr valign="baseline">
                            <td nowrap="nowrap" align="right">Cama supletoria:</td>
                            <td><input name="cama" type="text" id="cama" value="<?php echo htmlentities($row_casa_edita['cama'], ENT_COMPAT, 'iso-8859-1'); ?>" size="5" /> 
                                &euro;</td>
                        </tr>
                        <tr valign="baseline">
                            <td nowrap="nowrap" align="right">Cuna:</td>
                            <td><input name="cuna" type="text" id="cuna" value="<?php echo htmlentities($row_casa_edita['cuna'], ENT_COMPAT, 'iso-8859-1'); ?>" size="5" /> 
                                &euro;</td>
                        </tr>

                        <tr valign="baseline">

                            <td nowrap="nowrap" align="right">&nbsp;</td>

                            <td><input type="submit" value="Actualizar registro" class="bton-form"/></td>

                        </tr>

                    </table>

                    <input type="hidden" name="MM_update" value="form1" />

                    <input type="hidden" name="id_cas" value="<?php echo $row_casa_edita['id_cas']; ?>" />

                </form>

            </div>

    </body>

</html>

<?php
mysqli_free_result($casa_edita);
?>


<?php
require_once('archivos/funcion.php');
require_once('../Connections/rural.php');
require_once('archivos/funciones_frm.php');

$editFormAction = $_SERVER['PHP_SELF'];

if (isset($_SERVER['QUERY_STRING'])) {

    $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
$id_cliente = "-1";

if (isset($_GET['id_cliente'])) {

    $id_cliente = $_GET['id_cliente'];
}

mysqli_select_db($rural, $database_rural);

$sql_edita = sprintf("SELECT * FROM clientes WHERE id_cliente = $id_cliente");

$resultados = mysqli_query($rural, $sql_edita) or die(mysql_error());

$res = mysqli_fetch_assoc($resultados);


if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
    $foto1 = $res['foto'];
    if (!empty($_FILES['userfile']['name'])) {
        print_r($_FILES);
        $text = $_FILES['userfile'] ['name'];
        $tipo = $_FILES['userfile']['type'];
        $tamano = $_FILES['userfile']['size'];
        $dir = $_FILES['userfile']['tmp_name'];
        $dni = $_POST['dni'];
        $extension = end(explode(".", $_FILES['userfile']['name']));
        $nombrefinal = $dni . "." . $extension;

        move_uploaded_file($_FILES['userfile']['tmp_name'], 'imagenes/clientes/' . $nombrefinal);
        $foto1 = $nombrefinal;
    }
    $sql = sprintf("UPDATE clientes SET foto=%s, nombre=%s, apellidos=%s, dni=%s,email=%s, domicilio=%s, cp=%s, poblacion=%s, provincia=%s, telefono=%s, Observaciones=%s WHERE id_cliente=%s", GetSQLValueString($foto1, "text"), GetSQLValueString($_POST['nombre'], "text"), GetSQLValueString($_POST['apellidos'], "text"), GetSQLValueString($_POST['dni'], "text"), GetSQLValueString($_POST['email'], "text"), GetSQLValueString($_POST['domicilio'], "text"), GetSQLValueString($_POST['cp'], "text"), GetSQLValueString($_POST['poblacion'], "text"), GetSQLValueString($_POST['provincia'], "text"), GetSQLValueString($_POST['telefono'], "text"), GetSQLValueString($_POST['observaciones'], "text"), GetSQLValueString($_POST['id_cliente'], "int"));



    mysqli_select_db($rural, $database_rural);
    $result = mysqli_query($rural, $sql) or die(mysqli_error());



    $ir = "cliente_ficha.php?id_cliente=" . $_POST['id_cliente'];
    header(sprintf("Location: %s", $ir));
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

                <h1>Editar cliente </h1>

                <form enctype="multipart/form-data" action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">

                    <table align="center">
                        <tr valign="baseline">
                            <td nowrap="nowrap" align="right">Foto:</td>
                            <td>
                                <?php
                                if ($res['foto'] != "") {
                                    $foto = "./imagenes/clientes/" . $res['foto'];
                                } else {
                                    $foto = "./imagenes/clientes/defecto.jpg";
                                }
                                ?>
                                <img src="<?php echo $foto; ?>" width="150" height="150"></br>
                                <input name="userfile" type="file">
                            </td>
                        </tr>

                        <tr valign="baseline">

                            <td nowrap="nowrap" align="right">Nombre:</td>

                            <td><input type="text" name="nombre" value="<?php echo $res['nombre'] ?>" size="50" /></td>

                        </tr>

                        <tr valign="baseline">

                            <td nowrap="nowrap" align="right">Apellidos:</td>

                            <td><input type="text" name="apellidos" value="<?php echo $res['apellidos'] ?>" size="50" /></td>

                        </tr>

                        <tr valign="baseline">

                            <td nowrap="nowrap" align="right">DNI:</td>

                            <td><input type="text" name="dni" value="<?php echo $res['dni'] ?>" size="9" /></td>

                        </tr>
                        <tr valign="baseline">

                            <td nowrap="nowrap" align="right">Email</td>

                            <td><input type="text" name="email" value="<?php echo $res['email'] ?>" size="50" /></td>

                        </tr>

                        <tr valign="baseline">

                            <td nowrap="nowrap" align="right">Domicilio:</td>

                            <td><input type="text" name="domicilio" value="<?php echo $res['domicilio'] ?>" size="50" /></td>

                        </tr>

                        <tr valign="baseline">

                            <td nowrap="nowrap" align="right">CP:</td>

                            <td><input type="text" name="cp" value="<?php echo $res['cp'] ?>" size="5" /></td>

                        </tr>

                        <tr valign="baseline">

                            <td nowrap="nowrap" align="right">Población:</td>

                            <td><input type="text" name="poblacion" value="<?php echo $res['poblacion'] ?>" size="50" /></td>

                        </tr>

                        <tr valign="baseline">

                            <td nowrap="nowrap" align="right">Provincia:</td>

                            <td><select name="provincia">

                                    <option value=""></option>

                                    <?php
                                    $n = count($provincias) + 1;

                                    for ($i = 2; $i < $n; $i++) {
                                        ?> <option value="<?php echo $provincias[$i]; ?>" <?php
                                        if (isset($res['provincia'])) {
                                            if ($res['provincia'] == $provincias[$i])
                                                echo 'selected="selected"';
                                        }
                                        ?>><?php echo $provincias[$i]; ?></option> <?php
                                        }
                                    ?> 

                                </select></td>

                        </tr>

                        <tr valign="baseline">

                            <td nowrap="nowrap" align="right">Teléfono:</td>

                            <td><input type="text" name="telefono" value="<?php echo $res['telefono'] ?>" size="15" /></td>

                        </tr>

                        <tr valign="baseline">

                            <td align="right" valign="top" nowrap="nowrap">Observaciones:</td>

                            <td><textarea name="observaciones" id="observaciones" cols="50" rows="5"><?php echo $res['Observaciones'] ?></textarea></td>

                        </tr>

                        <tr valign="baseline">

                            <td nowrap="nowrap" align="right">&nbsp;</td>

                            <td><input type="submit" value="Actualizar registro" class="bton-form"/></td>

                        </tr>

                    </table>

                    <input type="hidden" name="MM_update" value="form1" />

                    <input type="hidden" name="id_cliente" value="<?php echo $res['id_cliente']; ?>" />

                </form>

            </div></div>

    </body>

</html>

<?php
mysqli_free_result($resultados);
?>


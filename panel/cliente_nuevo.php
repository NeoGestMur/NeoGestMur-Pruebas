<?php
require_once('archivos/funcion.php');
require_once('../Connections/rural.php');
require_once('archivos/funciones_frm.php');

$editFormAction = $_SERVER['PHP_SELF'];         // nombre del archivo de script ejecut⯤ose actualmente

if (isset($_SERVER['QUERY_STRING'])) {         // Si existe, la cadena de la consulta de la petici󮠤e la p⨩na.
    $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);  // Convierte todos los caracteres aplicables a entidades HTML
}



if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
    // comprobaciones


    $nombre = trim($_POST['nombre']);
    if (empty($nombre))
        $error['nombre'] = 'Por favor introduzca un nombre';
    else
        $nombre1 = GetSQLValueString($_POST['nombre'], "text");



    $apellidos = trim($_POST['apellidos']);
    $apellidos1 = GetSQLValueString($apellidos, "text");



    $dni = trim($_POST['dni']);
    if (empty($dni))
        $error['dni'] = 'Por favor introduzca un DNI';

    mysqli_select_db($rural, $database_rural);
    $sql_dni = "SELECT id_cliente, nombre FROM clientes WHERE dni = '" . $dni . "'";
    $resultado = mysqli_query($rural, $sql_dni) or die(mysql_error());
    $n_dni = mysqli_num_rows($resultado);


    if ($n_dni > 0)
        $error['dni'] = 'El DNI ya esta registrado en la base de datos';
    else
        $dni1 = GetSQLValueString($_POST['dni'], "text");
    $foto1 = null;
    if (isset($_FILES['userfile'])) {
        $text = $_FILES['userfile'] ['name'];
        $tipo = $_FILES['userfile']['type'];
        $tamano = $_FILES['userfile']['size'];
        $dir = $_FILES['userfile']['tmp_name'];
        $dni = $_POST['dni'];
        $extension = end(explode(".", $_FILES['userfile']['name']));
        $nombrefinal = $dni . "." . $extension;

        move_uploaded_file($_FILES['userfile']['tmp_name'], 'imagenes/clientes/' . $nombrefinal);
        $foto1 = GetSQLValueString($nombrefinal, "text");
    }


    $domicilio = trim($_POST['domicilio']);
    $domicilio1 = GetSQLValueString($domicilio, "text");


    $cp = trim($_POST['cp']);
    $cp1 = GetSQLValueString($cp, "text");

    $poblacion = trim($_POST['poblacion']);
    $poblacion1 = GetSQLValueString($poblacion, "text");

    $provincia = trim($_POST['provincia']);
    $provincia1 = GetSQLValueString($provincia, "text");



    $telefono = trim($_POST['telefono']);
    $telefono1 = GetSQLValueString($telefono, "text");

    $observ = trim($_POST['observaciones']);
    $observ1 = GetSQLValueString($observ, "text");


    $como = trim($_POST['como']);
    $como1 = GetSQLValueString($como, "text");

    $email = trim($_POST['email']);
    $email1 = GetSQLValueString($email, "text");
    // comprobacion de fecha
    if (isset($_POST['alta_dia'])) {
        if ($_POST['alta_dia'] != "") {
            $alta_dia = trim($_POST['alta_dia']);
            $alta_mes = trim($_POST['alta_mes']);
            $alta_year = trim($_POST['alta_year']);
            $fechaalta = $alta_year . "-" . $alta_mes . "-" . $alta_dia;

            $fechaalta = GetSQLValueString($fechaalta, "date");
        }
    }
    // fin de fecha	


    if (!isset($error)) {

        $sql = sprintf("INSERT INTO clientes (foto, nombre, apellidos, dni, domicilio, cp, poblacion, provincia, telefono, Observaciones, como, fecha,email) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s ,%s)", $foto1, $nombre1, $apellidos1, $dni1, $domicilio1, $cp1, $poblacion1, $provincia1, $telefono1, $observ1, $como1, $fechaalta, $email1
        );

        mysqli_select_db($rural, $database_rural);
        $result = mysqli_query($rural, $sql) or die(mysql_error());

        $sql = "SELECT MAX(id_cliente) AS id_cl FROM clientes";
        $consulta = mysqli_query($rural, $sql) or die(mysql_error());
        $registros = mysqli_fetch_assoc($consulta);

        $id_r = $registros['id_cl'];

        $ir = "cliente_ficha.php?id_cliente=" . $id_r;
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

                <h1>Nuevo cliente</h1>

                <form enctype="multipart/form-data" action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">

                    <table align="center">
                        <tr valign="baseline">

                            <td nowrap="nowrap" align="right">Foto:</td>

                            <td><input type="submit" value="Foto Perfil"><?php if (isset($nombre)) echo $nombre; ?>
                                <input name="userfile" type="file">

<?php if (isset($error['nombre'])) echo '<br /><span class="error">' . $error['nombre'] . '</span>'; ?>

                            </td>

                        </tr>

                        <tr valign="baseline">

                            <td nowrap="nowrap" align="right">Nombre:</td>

                            <td><input type="text" name="nombre" value="<?php if (isset($nombre)) echo $nombre; ?>" size="50" />

<?php if (isset($error['nombre'])) echo '<br /><span class="error">' . $error['nombre'] . '</span>'; ?>

                            </td>

                        </tr>

                        <tr valign="baseline">

                            <td nowrap="nowrap" align="right">Apellidos:</td>

                            <td><input type="text" name="apellidos" value="<?php if (isset($apellidos)) echo $apellidos; ?>" size="50" />

                            </td>

                        </tr>

                        <tr valign="baseline">

                            <td nowrap="nowrap" align="right">DNI:</td>

                            <td><input name="dni" type="text" value="<?php if (isset($dni)) echo $dni; ?>" size="9" />


                            </td>

                        </tr>
                        <tr valign="baseline">

                            <td nowrap="nowrap" align="right">Email:</td>

                            <td><input name="email" type="text" value="<?php if (isset($email)) echo $email; ?>" size="50" />



                            </td>

                        </tr>

                        <tr valign="baseline">

                            <td nowrap="nowrap" align="right">Domicilio:</td>

                            <td><input type="text" name="domicilio" value="<?php if (isset($domicilio)) echo $domicilio; ?>" size="50" /></td>

                        </tr>

                        <tr valign="baseline">

                            <td nowrap="nowrap" align="right">CP:</td>

                            <td><input type="text" name="cp" value="<?php if (isset($cp)) echo $cp; ?>" size="5" /></td>

                        </tr>

                        <tr valign="baseline">

                            <td nowrap="nowrap" align="right">Población:</td>

                            <td><input type="text" name="poblacion" value="<?php if (isset($poblacion)) echo $poblacion; ?>" size="50" /></td>

                        </tr>

                        <tr valign="baseline">

                            <td nowrap="nowrap" align="right">Provincia:</td>

                            <td><select name="provincia" id="provincia">

                                    <option value=""></option>

<?php
$n = count($provincias) + 1;

for ($i = 2; $i < $n; $i++) {
    ?> <option value="<?php echo $provincias[$i]; ?>" <?php if (isset($provincia)) {
        if ($provincia == $provincias[$i]) echo 'selected="selected"';
    } ?>><?php echo $provincias[$i]; ?></option> <?php
                                    }
                                    ?>      

                                </select></td>

                        </tr>

                        <tr valign="baseline">

                            <td nowrap="nowrap" align="right">Teléfono:</td>

                            <td><input type="text" name="telefono" value="<?php if (isset($telefono)) echo $telefono; ?>" size="15" /></td>

                        </tr>

                        <tr valign="baseline">

                            <td align="right" valign="top" nowrap="nowrap">Observaciones:</td>

                            <td><textarea name="observaciones" id="observaciones" cols="50" rows="5"><?php if (isset($observ)) echo $observ; ?></textarea></td>

                        </tr>

                        <tr valign="baseline">

                            <td align="right" valign="top" nowrap="nowrap">¿Cómo nos conoció?</td>

                            <td><label>

                                    <select name="como" id="como">

                                        <option value="" selected="selected"> </option>

                                        <option value="Por internet">Por internet</option>          

                                        <option value="A trav&eacute;s de publicidad escrita">A través de publicidad escrita</option>

                                        <option value="Por otro cliente">Por otro cliente</option>

                                        <option value="Otros">Otros</option>

                                    </select>

                                </label></td>

                        </tr>
                        <tr valign="baseline">
                            <td align="right" valign="top" nowrap="nowrap">Fecha de alta:</td>
                            <td><input name="alta_dia" type="text" id="alta_dia" size="2" maxlength="2" value="<?php echo date("d"); ?>" /> 

                                / <input name="alta_mes" type="text" id="alta_mes" size="2" maxlength="2" value="<?php echo date("m"); ?>" /> / <input name="alta_year" type="text" id="alta_year" size="4" maxlength="4" value="<?php echo date("Y"); ?>" /> 

                                Ej: DD/MM/AAAA</td>
                        </tr>

                        <tr valign="baseline">

                            <td nowrap="nowrap" align="right">&nbsp;</td>

                            <td><input type="submit" value="Insertar registro" class="bton-form"/></td>

                        </tr>

                    </table>

                    <input type="hidden" name="MM_insert" value="form1" />

                </form>

            </div></div>

    </body>

</html>
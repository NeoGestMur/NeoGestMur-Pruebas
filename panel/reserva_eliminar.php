<?php 
require_once('archivos/funcion.php');
require_once('../Connections/rural.php');
require_once('archivos/funciones.php');
require_once('archivos/funciones_frm.php');

if ((isset($_GET['id_reserva'])) && ($_GET['id_reserva'] != "")) {

  $deleteSQL = sprintf("DELETE FROM reserva WHERE id_reserva=%s",

                       GetSQLValueString($_GET['id_reserva'], "int"));



  mysqli_select_db($rural, $database_rural);

  $Result1 = mysqli_query($rural, $deleteSQL) or die(mysqli_error());



  $deleteGoTo = "reserva_lista.php";

  if (isset($_SERVER['QUERY_STRING'])) {

    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";

    $deleteGoTo .= $_SERVER['QUERY_STRING'];

  }

  header(sprintf("Location: %s", $deleteGoTo));

}



$id = "-1";

if (isset($_GET['id'])) {

  $id = $_GET['id'];

}

mysqli_select_db($rural, $database_rural);

$query_reserva = sprintf("SELECT casa.nombre as casa, reserva.id_reserva, clientes.nombre as cliente, reserva.adultos, reserva.nenes, reserva.bebes, reserva.entrada, reserva.salida, reserva.anticipo, reserva.observaciones, reserva.estado FROM reserva, casa, clientes WHERE id_reserva = %s AND casa.id_cas=reserva.casa_id AND clientes.id_cliente=reserva.cliente_id", GetSQLValueString($id, "int"));

$reserva = mysqli_query($rural, $query_reserva) or die(mysql_error());

$row_reserva = mysqli_fetch_assoc($reserva);

$totalRows_reserva = mysqli_num_rows($reserva);

?>


<html>

<head>

<meta charset="utf-8" />

<title>Panel de control: La Caraba</title>

<link href="css/estilos.css" rel="stylesheet" type="text/css" />

</head>



<body>

<body>

<div id="contenido">

<div id="menu"><?php include('archivos/menu.html'); ?><div class="limpiar"></div></div>

<div class="caja_frm">

<h1> Eliminar reserva</h1>

<table align="center">

    <tr valign="baseline">

      <td nowrap="nowrap" align="right">Cliente:</td>

      <td><input name="nombre" type="text" disabled="disabled" value="<?php echo htmlentities($row_reserva['cliente'], ENT_COMPAT, 'iso-8859-1'); ?>" size="32" readonly="readonly" /></td>

    </tr>

    <tr valign="baseline">

      <td nowrap="nowrap" align="right">Casa:</td>

      <td><input name="casa" type="text" disabled="disabled" value="<?php echo htmlentities($row_reserva['casa'], ENT_COMPAT, 'iso-8859-1'); ?>" size="32" readonly="readonly" /></td>

    </tr>

    <tr valign="baseline">

      <td nowrap="nowrap" align="right">Adultos:</td>

      <td><input name="adultos" type="text" disabled="disabled" value="<?php echo htmlentities($row_reserva['adultos'], ENT_COMPAT, 'iso-8859-1'); ?>" size="32" readonly="readonly" /></td>

    </tr>

    <tr valign="baseline">

      <td nowrap="nowrap" align="right">Niños:</td>

      <td><input name="nenes" type="text" disabled="disabled" value="<?php echo htmlentities($row_reserva['nenes'], ENT_COMPAT, 'iso-8859-1'); ?>" size="32" readonly="readonly" /></td>

    </tr>

    <tr valign="baseline">

      <td nowrap="nowrap" align="right">Bebés:</td>

      <td><input name="bebes" type="text" disabled="disabled" value="<?php echo htmlentities($row_reserva['bebes'], ENT_COMPAT, 'iso-8859-1'); ?>" size="32" readonly="readonly" /></td>

    </tr>

    <tr valign="baseline">

      <td nowrap="nowrap" align="right">Fecha entrada:</td>

      <td><input name="entrada" type="text" disabled="disabled" id="entrada" value="<?php echo formato_es($row_reserva['entrada']); ?>" size="32" readonly="readonly" /></td>

    </tr>

    <tr valign="baseline">

      <td nowrap="nowrap" align="right">Fecha salida:</td>

      <td><input name="salida" type="text" disabled="disabled" id="salida" value="<?php echo formato_es($row_reserva['salida']); ?>" size="32" readonly="readonly" /></td>

    </tr>

    <tr valign="baseline">

      <td nowrap="nowrap" align="right">Anticipo:</td>

      <td><input name="anticipo" type="text" disabled="disabled" value="<?php echo htmlentities($row_reserva['anticipo'], ENT_COMPAT, 'iso-8859-1'); ?>" size="32" readonly="readonly" /></td>

    </tr>

    <tr valign="baseline">

      <td nowrap="nowrap" align="right">Observaciones:</td>

      <td><input name="observaciones" type="text" disabled="disabled" value="<?php echo $row_reserva['observaciones'] ?>" size="32" readonly="readonly" /></td>

    </tr>

    <tr valign="baseline">

      <td nowrap="nowrap" align="right">Estado:</td>

      <td><input name="estado" type="text" disabled="disabled" value="<?php echo htmlentities($row_reserva['estado'], ENT_COMPAT, 'iso-8859-1'); ?>" size="32" readonly="readonly" /></td>

    </tr>

    <tr valign="baseline">

      <td colspan="2" align="center" nowrap="nowrap"><div class="bton_nuevo"><a href="reserva_eliminar.php?id_reserva=<?php echo $id; ?>">Confirmar</a> </div><div class="bton_nuevo"><a href="reserva_lista.php">Cancelar</a></div></td>

      </tr>

  </table>

</div></div>

</body>

</html>

<?php

mysqli_free_result($reserva);

?>


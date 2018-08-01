<?php 
require_once('archivos/funcion.php');
require_once('../Connections/rural.php');
require_once('archivos/funciones.php');
require_once('archivos/funciones_frm.php');

$editFormAction = $_SERVER['PHP_SELF'];

function fecha_dia($dato){
	$fechainicio = $dato;
	$fechainicio_dia = substr($fechainicio,8,2);
	echo $fechainicio_dia;
}

function fecha_mes($dato){
	$fechainicio = $dato;
	$fechainicio_mes = substr($fechainicio,5,2);
	echo $fechainicio_mes;
}

function fecha_year($dato){
	$fechainicio = $dato;
	$fechainicio_year = substr($fechainicio,0,4);
	echo $fechainicio_year;
}


if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}


if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {		// datos nuevos

	if(isset($_POST['entrada'])){
		$entrada=formato_in($_POST['entrada']);
	}

	if(isset($_POST['salida'])){
		$salida=formato_in($_POST['salida']);
	}
	
	// comprobacion de fecha
	if (isset($_POST['alta_dia'])){
		if ($_POST['alta_dia'] != ""){
				$alta_dia = trim($_POST['alta_dia']);
				$alta_mes = trim($_POST['alta_mes']);
				$alta_year = trim($_POST['alta_year']);
				$fechaalta = $alta_year."-".$alta_mes."-".$alta_dia;
		}else{
			$fechaalta = "";
		}
	}else{
		$fechaalta = "";
	}
	// fin de fecha	
	// comprobacion de tarifa
	if ($_POST['tarifa'] == 't1'){
		$dias =$_POST['dias'];
	}else{
		$dias = 0;
	}

  $updateSQL = sprintf("UPDATE reserva SET cliente_id=%s, casa_id=%s, adultos=%s, nenes=%s, bebes=%s, entrada=%s, salida=%s, anticipo=%s, anticipo_ob=%s, observaciones=%s, estado=%s, dias=%s, tipo_tarifa=%s, suplementos=%s, camas=%s, cunas=%s, animales=%s, descuento=%s, n_f=%s, fecha=%s, extras=%s, precio_extras=%s WHERE id_reserva=%s",
                       GetSQLValueString($_POST['cliente_id'], "int"),
                       GetSQLValueString($_POST['casa_id'], "int"),
                       GetSQLValueString($_POST['adultos'], "int"),
                       GetSQLValueString($_POST['nenes'], "int"),
                       GetSQLValueString($_POST['bebes'], "int"),
                       GetSQLValueString($entrada, "date"),
                       GetSQLValueString($salida, "date"),
                       GetSQLValueString($_POST['anticipo'], "double"),
                       GetSQLValueString($_POST['anticipo_ob'], "text"),
					   GetSQLValueString($_POST['observaciones'], "text"),
                       GetSQLValueString($_POST['estado'], "int"),
					   GetSQLValueString($dias, "double"),
					   GetSQLValueString($_POST['tarifa'], "text"),
					   GetSQLValueString($_POST['sofas'], "int"),
					   GetSQLValueString($_POST['camas'], "int"),
					   GetSQLValueString($_POST['cunas'], "int"),
					   GetSQLValueString($_POST['animales'], "double"),				   
					   GetSQLValueString($_POST['descuento'], "double"),
					   GetSQLValueString($_POST['n_f'], "text"),
					   GetSQLValueString($fechaalta, "date"),
					   GetSQLValueString($_POST['extras'], "text"),
					   GetSQLValueString($_POST['precio_extras'], "double"),
                       GetSQLValueString($_POST['id_reserva'], "int"));

  mysqli_select_db($rural, $database_rural);
  $Result1 = mysqli_query($rural, $updateSQL) or die(mysqli_error());

  $ir = "reserva_ficha.php?id=".$_POST['id_reserva'];
  header(sprintf("Location: %s", $ir));
}

$id = "-1";
if (isset($_GET['id_reserva'])) {
  $id = $_GET['id_reserva'];
}


mysqli_select_db($rural, $database_rural);	// datos de la reserva
$query_reserva_edi = sprintf("SELECT * FROM reserva WHERE id_reserva = %s", GetSQLValueString($id, "int"));
$reserva_edi = mysqli_query($rural, $query_reserva_edi) or die(mysqli_error());
$row_reserva_edi = mysqli_fetch_assoc($reserva_edi);
$totalRows_reserva_edi = mysqli_num_rows($reserva_edi);

mysqli_select_db($rural, $database_rural);	// clientes diponibles
$query_clientes = "SELECT * FROM clientes WHERE id_cliente=".$row_reserva_edi['cliente_id'];
$clientes = mysqli_query($rural, $query_clientes) or die(mysqli_error());
$row_clientes = mysqli_fetch_assoc($clientes);
$totalRows_clientes = mysqli_num_rows($clientes);


mysqli_select_db($rural, $database_rural);	// casas disponibles
$query_casas = "SELECT * FROM casa ORDER BY orden ASC";
$casas = mysqli_query($rural, $query_casas) or die(mysqli_error());
$row_casas = mysqli_fetch_assoc($casas);
$totalRows_casas = mysqli_num_rows($casas);


?>
<html>
<head>
<meta charset="utf-8" />
<title>Panel de control: La Caraba</title>



<link href="css/estilos.css" rel="stylesheet" type="text/css" />
<link type="text/css" href="css/ui-lightness/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
<script type="text/javascript" src="js/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.16.custom.min.js"></script>
<script>
	$(function() {
		$( "#entrada" ).datepicker({ 
					firstDay: 1,
					monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
					monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dec'],
					dayNames: ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'],
					dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
					changeMonth: true,
					dateFormat: 'dd/mm/yy',
					});
		$( "#salida" ).datepicker({ 
					firstDay: 1,
					monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
					monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dec'],
					dayNames: ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'],
					dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
					changeMonth: true,
					dateFormat: 'dd/mm/yy',
					});
	});
</script>
</head>
<body>
<div id="contenido">



<div id="menu"><?php include('archivos/menu.html'); ?><div class="limpiar"></div></div>



<div class="caja_frm">



<h1> Editar reserva</h1>



<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">



  <table align="center">



    <tr valign="baseline">



      <td nowrap="nowrap" align="right">Cliente:</td>
      <td><input name="cliente" type="text" disabled="disabled" id="cliente" value="<?php echo $row_clientes['nombre'].' '.$row_clientes['apellidos']?>" size="50" readonly="readonly" /><input name="cliente_id" type="hidden" id="cliente_id" value="<?php echo $row_reserva_edi['cliente_id']; ?>" /></td>    </tr>



    <tr valign="baseline">



      <td nowrap="nowrap" align="right">Casa:</td>



      <td><select name="casa_id" id="casa_id">



          <?php



do {  



?>



          <option value="<?php echo $row_casas['id_cas']?>"<?php if (!(strcmp($row_casas['id_cas'], $row_reserva_edi['casa_id']))) {echo "selected=\"selected\"";} ?>><?php echo $row_casas['nombre']?></option>



          <?php



} while ($row_casas = mysqli_fetch_assoc($casas));



  $rows = mysqli_num_rows($casas);



  if($rows > 0) {



      mysqli_data_seek($casas, 0);



	  $row_casas = mysqli_fetch_assoc($casas);



  }



?>



      </select></td>



    </tr>



    <tr valign="baseline">



      <td nowrap="nowrap" align="right">Adultos:</td>



      <td><input type="text" name="adultos" value="<?php echo htmlentities($row_reserva_edi['adultos'], ENT_COMPAT, 'iso-8859-1'); ?>" size="32" /></td>



    </tr>



    <tr valign="baseline">



      <td nowrap="nowrap" align="right">Ni&ntilde;os:</td>



      <td><input type="text" name="nenes" value="<?php echo htmlentities($row_reserva_edi['nenes'], ENT_COMPAT, 'iso-8859-1'); ?>" size="32" /></td>



    </tr>



    <tr valign="baseline">



      <td nowrap="nowrap" align="right">Beb&eacute;s:</td>



      <td><input type="text" name="bebes" value="<?php echo htmlentities($row_reserva_edi['bebes'], ENT_COMPAT, 'iso-8859-1'); ?>" size="32" /></td>



    </tr>



    <tr valign="baseline">



      <td nowrap="nowrap" align="right">Fecha entrada:</td>



      <td><input type="text" name="entrada" id="entrada" value="<?php echo formato_es($row_reserva_edi['entrada']); ?>" size="32" /></td>



    </tr>



    <tr valign="baseline">



      <td nowrap="nowrap" align="right">Fecha salida:</td>



      <td><input type="text" name="salida" id="salida" value="<?php echo formato_es($row_reserva_edi['salida']); ?>" size="32" /></td>



    </tr>



    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Anticipo:</td>
      <td><input type="text" name="anticipo" value="<?php echo $row_reserva_edi['anticipo']; ?>" size="32" /></td>
    </tr>
     <tr valign="baseline">
      <td nowrap="nowrap" align="right">Anticipo Observ.:</td>
      <td><input type="text" name="anticipo_ob" value="<?php echo $row_reserva_edi['anticipo_ob']; ?>" size="32" /></td>
    </tr>



    <tr valign="baseline">



      <td nowrap="nowrap" align="right">Observaciones:</td>



      <td><textarea name="observaciones" cols="45" rows="5"><?php echo $row_reserva_edi['observaciones']; ?></textarea></td>
    </tr>



    <tr valign="baseline">



      <td nowrap="nowrap" align="right">Estado:</td>



      <td><?php $valor = $row_reserva_edi['estado']; ?>



        <select name="estado" id="estado">



        <option value="0" <?php if ($valor == 0) echo 'selected="selected"'; ?>>Bloqueado</option>



        <option value="1" <?php if ($valor == 1) echo 'selected="selected"'; ?>>Reservado</option>



        <option value="2" <?php if ($valor == 2) echo 'selected="selected"'; ?>>Han llegado</option>



        <option value="3" <?php if ($valor == 3) echo 'selected="selected"'; ?>>Se han ido</option>



        </select></td>



    </tr>



    <tr valign="baseline">



      <td nowrap="nowrap" align="right">Tarifa:</td>



      <td>

            <label> <input name="tarifa" type="radio" id="tarifa_0" value="t1"  <?php if ($row_reserva_edi['tipo_tarifa'] == 't1') echo 'checked="checked"'; ?> />   Temporada baja. </label> 

			N&ordm; d&iacute;as festivos:

			<input name="dias" type="text" id="dias" value="<?php echo htmlentities($row_reserva_edi['dias'], ENT_COMPAT, 'iso-8859-1'); ?>" size="5" />

        <br />

		<label> <input type="radio" name="tarifa" value="t2" id="tarifa_0" <?php if ($row_reserva_edi['tipo_tarifa'] == 't2') echo 'checked="checked"'; ?> /> Temporada media </label>

        <br />

        <label> <input type="radio" name="tarifa" value="t3" id="tarifa_0" <?php if ($row_reserva_edi['tipo_tarifa'] == 't3') echo 'checked="checked"'; ?> /> Temporada alta </label>

      </td>



    </tr>



    <tr valign="baseline">



      <td nowrap="nowrap" align="right">Descuento:</td>



      <td><label>



        <input type="text" name="descuento" id="descuento" value="<?php echo htmlentities($row_reserva_edi['descuento'], ENT_COMPAT, 'iso-8859-1'); ?>" />



      </label></td>



    </tr>

    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Suplementos:</td>
      <td>N&ordm; sof&aacute;s cama: 
        <label><input name="sofas" type="text" id="supletorios" size="4"  value="<?php echo htmlentities($row_reserva_edi['suplementos'], ENT_COMPAT, 'iso-8859-1'); ?>"/></label>
        <br />
      N&ordm; camas supletorias:
      <label><input name="camas" type="text" id="camas" size="4" value="<?php echo htmlentities($row_reserva_edi['camas'], ENT_COMPAT, 'iso-8859-1'); ?>" /></label>
      <br />
      N&ordm; cunas: 
      <label><input name="cunas" type="text" id="cunas" size="4"  value="<?php echo htmlentities($row_reserva_edi['cunas'], ENT_COMPAT, 'iso-8859-1'); ?>" /></label>
      <br />
      Animales: 

      <label><input name="animales" type="text" id="animales" value="<?php echo htmlentities($row_reserva_edi['animales'], ENT_COMPAT, 'iso-8859-1'); ?>" size="20" />   &euro;
      <br />
      
       Extras: 
       <label>
         <textarea name="extras" cols="20" rows="5" id="extras"><?php echo htmlentities($row_reserva_edi['extras'], ENT_COMPAT, 'iso-8859-1'); ?></textarea>
       </label>
      <input name="precio_extras" type="text" id="precio_extras" value="<?php echo htmlentities($row_reserva_edi['precio_extras'], ENT_COMPAT, 'iso-8859-1'); ?>" size="10" />
      &euro;
      </td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">N&ordm; factura:</td>
      <td><input type="text" name="n_f" id="n_f" value="<?php echo htmlentities($row_reserva_edi['n_f'], ENT_COMPAT, 'iso-8859-1'); ?>" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Fecha:</td>
      <td><input name="alta_dia" type="text" id="alta_dia" size="2" maxlength="2" value="<?php fecha_dia($row_reserva_edi['fecha']);?>" /> 

      / <input name="alta_mes" type="text" id="alta_mes" size="2" maxlength="2" value="<?php fecha_mes($row_reserva_edi['fecha']);?>" /> / <input name="alta_year" type="text" id="alta_year" size="4" maxlength="4" value="<?php fecha_year($row_reserva_edi['fecha']);?>" /> 

      Ej: 20/10/2012</td>
    </tr>
    <tr valign="baseline">


      <td nowrap="nowrap" align="right">&nbsp;</td>



      <td><input type="submit" value="Actualizar registro" class="bton-form" /></td>



    </tr>



  </table>



  <input type="hidden" name="MM_update" value="form1" />



  <input type="hidden" name="id_reserva" value="<?php echo $row_reserva_edi['id_reserva']; ?>" />



</form>



</div></div>



</body>



</html>



<?php



mysqli_free_result($reserva_edi);







mysqli_free_result($clientes);







mysqli_free_result($casas);



?>




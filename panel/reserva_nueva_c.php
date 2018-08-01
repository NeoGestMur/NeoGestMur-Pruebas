<?php 
require_once('archivos/funcion.php');
require_once('archivos/sesion.php');
require_once('../Connections/rural.php'); 
require_once('archivos/callendario_mini.php');
require_once('archivos/funciones.php');
require_once('archivos/funciones_frm.php');

require_once ('archivos/ceta_funciones.php');


if (!function_exists("GetSQLValueString")) {

function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }
  $theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string($theValue) : mysqli_escape_string($theValue);
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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {
	$entrada = trim($_POST['entrada']);
	if (empty($entrada)) 
		$error['entrada'] = 'Por favor inserte una fecha de entrada';
	else
		$entrada=formato_in($_POST['entrada']);

	$salida = trim($_POST['salida']);
	if (empty($salida)) 
		$error['salida'] = 'Por favor inserte una fecha de salida';
	else
		$salida=formato_in($_POST['salida']);
	//////////////////////////////////////
	// tipo de tarifas
	/*	temporada baja				=> t1
		temporada media				=> t2
		temporada alta				=> t3
	*/

	$tipo_tarifa = $_POST['tarifa'];
	if ($_POST['tarifa'] == 't1'){
		$diasf =$_POST['festivos'];
	}else{
		$diasf = 0;
	}
	// fin tarifa
	///////////////////////////////////////////////

	// comprobacion de fecha
	if (isset($_POST['alta_dia'])){
		if ($_POST['alta_dia'] != ""){
				$alta_dia = trim($_POST['alta_dia']);
				$alta_mes = trim($_POST['alta_mes']);
				$alta_year = trim($_POST['alta_year']);
				$fechaalta = $alta_year."-".$alta_mes."-".$alta_dia;
				$fechaalta = GetSQLValueString($fechaalta, "date");//echo $fechaalta;
		}
	}
	// fin de fecha	

	if(!isset($error)){
	  $insertSQL = sprintf("INSERT INTO reserva (cliente_id, casa_id, adultos, nenes, bebes, entrada, salida, anticipo, anticipo_ob, observaciones, estado, tipo_tarifa, precio, precio2, dias, descuento, camas, suplementos, cunas, extras, precio_extras, p_suplemento, p_cama, p_cuna, animales, personal_id, fecha_alta,origen) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s,%s)",

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
						   GetSQLValueString($tipo_tarifa, "text"),
						   GetSQLValueString($_POST['precio1'], "double"),
						   GetSQLValueString($_POST['precio2'], "double"),
						   GetSQLValueString($diasf, "int"),
						   GetSQLValueString($_POST['descuento'], "double"),
						   GetSQLValueString($_POST['camas'], "int"),
						   GetSQLValueString($_POST['supletorios'], "int"),
						   GetSQLValueString($_POST['cunas'], "int"),
						   GetSQLValueString($_POST['extras'], "text"),
						   GetSQLValueString($_POST['extra_p'], "double"),
						   GetSQLValueString($_POST['sofa_p'], "double"),
						   GetSQLValueString($_POST['cama_p'], "double"),
						   GetSQLValueString($_POST['cuna_p'], "double"),
						   GetSQLValueString($_POST['animales'], "double"),
						   GetSQLValueString($_POST['personal'], "int"),
						   $fechaalta,
                           GetSQLValueString($_POST['origen'], "text")

      );

	  mysqli_select_db($rural, $database_rural);
	  $Result1 = mysqli_query($rural, $insertSQL) or die(mysqli_error());
 
	  $sql = "SELECT MAX(id_reserva) AS id_ref FROM reserva";  
      $consulta = mysqli_query($rural, $sql) or die(mysqli_error());
	  $registros = mysqli_fetch_assoc($consulta);

	  $id_r =  $registros['id_ref']; 

	  $insertGoTo = "reserva_comprobacion.php?id=".$id_r;
	  header(sprintf("Location: %s", $insertGoTo));
	}
}

$idcl = "-1";
if (isset($_POST['cliente_id'])) {
  $idcl = $_POST['cliente_id'];
}
mysqli_select_db($rural, $database_rural);
$query_cliente = sprintf("SELECT * FROM clientes WHERE id_cliente = %s", GetSQLValueString($idcl, "int"));
$cliente = mysqli_query($rural, $query_cliente) or die(mysqli_error());
$row_cliente = mysqli_fetch_assoc($cliente);
$totalRows_cliente = mysqli_num_rows($cliente);

$id_c = "-1";
if (isset($_POST['casa_id'])) {
  $id_c = $_POST['casa_id'];
}

mysqli_select_db($rural, $database_rural);
$query_casas = "SELECT * FROM casa ORDER BY plazas ASC";
$query_casas = sprintf("SELECT * FROM casa WHERE id_cas = %s", GetSQLValueString($id_c, "int"));
$casas = mysqli_query($rural, $query_casas) or die(mysqli_error());
$row_casas = mysqli_fetch_assoc($casas);
$totalRows_casas = mysqli_num_rows($casas);

$tarifa_normal = $row_casas['tarifa_d_b'];
$tarifa_festivo = $row_casas['tarifa_d_f'];

$colname_atencion = "-1";
if (isset($_POST['personal'])) {
  $colname_atencion = $_POST['personal'];
}


mysqli_select_db($rural, $database_rural);
$query_atencion = sprintf("SELECT * FROM personal WHERE id_personal = %s", GetSQLValueString($colname_atencion, "int"));
$atencion = mysqli_query($rural, $query_atencion) or die(mysqli_error());
$row_atencion = mysqli_fetch_assoc($atencion);
$totalRows_atencion = mysqli_num_rows($atencion);

// dias reservada
$sql = sprintf("SELECT * FROM reserva WHERE casa_id = %s  ORDER BY entrada ASC", GetSQLValueString($id_c, "int"));
$respueta = mysqli_query($rural, $sql) or die(mysqli_error());

//$fechas = mysqli_fetch_assoc($respueta);
$total = mysqli_num_rows($respueta);

$quitar = array();
$quitar_f  ='';
$n = 0;

//---------------------------------
function sumaDia($fecha,$dia){	list($year,$mon,$day) = explode('-',$fecha);
	return date('Y-m-d',mktime(0,0,0,$mon,$day+$dia,$year));		
}
//---------------------------------

// FECHA ANTERIOR
$fecha_antes = array();
$w = 0;

while($fechas = mysqli_fetch_assoc($respueta)){
	$fecha_antes[$w] = $fechas['salida']; 
	
	//echo 'e: '.$fechas['entrada'].' s: '.$fechas['salida'].' / <br />';
	if (isset($fecha_antes[$w-1])) {
		$fecha_anterior =$fecha_antes[$w-1]; 
		//echo $fecha_anterior .'<br/>';
	}else {
		$fecha_anterior='';
	}
	$w++;
	if ( $fecha_anterior == $fechas['entrada']) {
		//echo 'si';
		$fech_in = ($fechas['entrada']);
		$fech_in2 = strtotime($fech_in);
	}else{
		$fechaInicio = ($fechas['entrada']);
		$fech_in = sumaDia($fechaInicio,1);
		$fech_in2 = strtotime($fech_in);
	}
	// rango 
	
	$fechaFin = strtotime($fechas['salida']);

	for($i=$fech_in2; $i<$fechaFin; $i+=86400){
		$quitar[$n]= date("n-j-Y", $i);
		$n++;
	}

}

$cuantos =  count($quitar);
for($n=0; $n< count($quitar); $n++ ){
	if($n != $cuantos-1)
		$quitar_f.= '"'.$quitar[$n].'",';
	else
		$quitar_f.= '"'.$quitar[$n].'"';
}
//echo $quitar_f;

/////////////////////////////////////////////////
$meses = array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiempre','Octubre','Noviembre','Diciembre');
$idc = $id_c;
$yearCalendario = date('Y');if (isset($_GET['ano'])) {$yearCalendario = $_GET['ano'];} // Si no se define a�o en el par�metro de URL, el a�o actual
$mesCalendario = date('n');if (isset($_GET['mes'])) {$mesCalendario = $_GET['mes'];} // Si no se define mes en el par�metro de URL, el mes actual
$finDeSemana = 1;if (isset($_SESSION['finde'])) {$finDeSemana = $_SESSION['finde'];} // D�as de fin de semana activados: '1' para activado, '0' para desactivado (se predetermina a 1)
$nivelH = 2; // Nivel para el encabezado del bloque de calendario, con valor entre '1' y '6'. Se predetermina en '2'.

$enlaceAnterior1 = gmmktime(0,0,0,($mesCalendario-1),1,$yearCalendario);
$mesAnterior = date('n',$enlaceAnterior1);
$yearMesAnterior = date('Y',$enlaceAnterior1);
$enlaceSiguiente1 = gmmktime(0,0,0,($mesCalendario+1),1,$yearCalendario);
$mesSiguiente = date('n',$enlaceSiguiente1);
$yearMesSiguiente = date('Y',$enlaceSiguiente1);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Panel de control: La Caraba</title>
<link href="css/estilos.css" rel="stylesheet" type="text/css" />
<link type="text/css" href="css/ui-lightness/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
<script type="text/javascript" src="js/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.16.custom.min.js"></script>
<script src="js/coda.js" type="text/javascript"> </script>
	<script>
	$(function() {
		var disabledDays = <?php echo '['.$quitar_f.']'; ?>;
		//var disabledDays = ["5-18-2012","5-19-2012"];
		function nationalDays(date) {
		  var m = date.getMonth(), d = date.getDate(), y = date.getFullYear();
		  //console.log('Checking (raw): ' + m + '-' + d + '-' + y);
		  for (i = 0; i < disabledDays.length; i++) {
			//if($.inArray((m+1) + '-' + d + '-' + y,disabledDays) != -1 || new Date() > date) {
			if($.inArray((m+1) + '-' + d + '-' + y,disabledDays) != -1) {
			  //console.log('bad:  ' + (m+1) + '-' + d + '-' + y + ' / ' + disabledDays[i]);
			  return [false];
			}
		  }
		  //console.log('good:  ' + (m+1) + '-' + d + '-' + y);
		  return [true];
		}

		function noWeekendsOrHolidays(date) {
		 // var noWeekend = jQuery.datepicker.noWeekends(date);
		  return nationalDays(date);
		}

		var dates = $( "#entrada, #salida" ).datepicker({
			firstDay: 1,
			monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
			monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
			dayNames: ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'],
			dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
			changeMonth: true,
			numberOfMonths: 2,
			dateFormat: 'dd-mm-yy',
			 beforeShowDay: noWeekendsOrHolidays,
			onSelect: function( selectedDate ) {
				var option = this.id == "entrada" ? "minDate" : "maxDate",
					instance = $( this ).data( "datepicker" ),
					date = $.datepicker.parseDate(
						instance.settings.dateFormat ||
						$.datepicker._defaults.dateFormat,
						selectedDate, instance.settings );
				dates.not( this ).datepicker( "option", option, date );
				//alert ('hola');
			}
		});
	});
	</script>

<script type="application/javascript">

$(document).ready(function() {
	var idc = "<?php echo $idc; ?>";
	
	$(".siguiente").click(function() {
	  //alert("siguiente.");
	  $('.calendario').load('calendario.php',{idc: idc, ids: "s"}, function(){
		//alert ('hola');  
	  });
	  
	});	
	$(".anterior").click(function() {
	  //alert("anterior.");
	  $('.calendario').load('calendario.php',{idc: idc, ida: "a"}, function(){
		//alert ('hola');  
	  });
	});	
})

</script>

<style type="text/css">
a{
	color: #666;
	text-decoration: none;
}
a:hover{
	color: #333;
}
</style>

</head>
<body>
<div id="contenido">
<div id="menu"><?php include('archivos/menu.html'); ?><div class="limpiar"></div></div>
<div class="caja_frm">

<h1> A&ntilde;adir reserva: paso 2</h1>

<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form2">
  <table align="center">
   <tr valign="baseline">
      <td nowrap="nowrap" align="right">Nombre:</td>
      <td><?php echo $row_cliente['nombre']; ?><input type="hidden" name="cliente_id" id="cliente_id" value="<?php echo $_POST['cliente_id']; ?>" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">DNI:</td>
      <td><?php echo $row_cliente['dni']; ?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Tel&eacute;fono:</td>
      <td><?php echo $row_cliente['telefono']; ?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Casa:</td>
      <td><?php echo $row_casas['nombre']; ?>
        <input type="hidden" name="casa_id" id="casa_id" value="<?php echo $_POST['casa_id']; ?>" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Atendido por:</td>
      <td><input type="hidden" name="personal" id="personal" value="<?php echo $_POST['personal']; ?>" />
        <?php echo $row_atencion['nombre'].' '.$row_atencion['apellidos']; ?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Origen</td>
      <td>
        <select name="origen" id="origen">
            <option value=""></option>
            <?php foreach ($agencias as $k =>  $a){
                echo "<option value=".$k.">".$a."</option>";
            } ?>
        </select>
      </td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Adultos:</td>
      <td><input type="text" name="adultos" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Ni&ntilde;os:</td>
      <td><input type="text" name="nenes" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Beb&eacute;s:</td>
      <td><input type="text" name="bebes" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td colspan="2" align="left" nowrap="nowrap">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>

      <td width="200" align="center">
      <div class="fondo_calendario"> 


<table width="200px" border="0" cellspacing="0" cellpadding="0">

  <tr>

    <td align="left"><div class="anterior"><a href="#">&laquo;Anterior</a></div></td>

    <td align="right"><div class="siguiente"><a href="#"> Siguiente&raquo; </a></div></td>

  </tr>

</table>



<div class="calendario"> 
<?php
echo $meses[$mesCalendario-1].' ('.$yearCalendario.')';
echo calendario($yearCalendario,$mesCalendario,$finDeSemana,$nivelH,$idc,$database_rural,$rural);
?>       
</div> <div class="finalizador"></div></div>      

        </td>

        <td width="474">

  <div class="demo">

  <table width="100%" border="0" cellspacing="0" cellpadding="0">

  <tr>

    <td width="13%"><label for="entrada">&nbsp;&nbsp;&nbsp;&nbsp;Entrada:</label></td>
    <td width="87%"><input type="text" id="entrada" name="entrada"/></td>

  </tr>

  <tr>

    <td><label for="salida">&nbsp;&nbsp;&nbsp;&nbsp;Salida:</label>	

     </td>
    <td><input type="text" id="salida" name="salida"/>

    <?php if(isset($error['entrada'])) echo '<br /><span class="error">'.$error['entrada'].'</span>'; ?>

    <?php if(isset($error['salida'])) echo '<br /><span class="error">'.$error['salida'].'</span>'; ?></td>

  </tr>

</table>
  </div><!-- End demo -->        

        </td>

        

      </tr>

    </table>

       </td>







    <tr valign="baseline">

      <td nowrap="nowrap" align="right">Anticipo:</td>

      <td><input type="text" name="anticipo" size="32" /></td>

    </tr>

    <tr valign="baseline">

      <td nowrap="nowrap" align="right">Anticipo observ.:</td>

      <td><input type="text" name="anticipo_ob" size="32" /></td>

    </tr>







    <tr valign="baseline">







      <td align="right" valign="top" nowrap="nowrap">Observaciones:</td>







      <td><textarea name="observaciones" id="observaciones" cols="45" rows="5"></textarea></td>







    </tr>







    <tr valign="baseline">







      <td nowrap="nowrap" align="right">Estado:</td>







      <td><select name="estado" id="estado">







        <option value="0">Bloqueado</option>







        <option value="1">Reservado</option>







        <option value="2">Han llegado</option>







        <option value="3">Se han ido</option>







        </select></td>







    </tr>







    <tr valign="baseline">







      <td nowrap="nowrap" align="right">Suplementos:</td>







      <td>N&ordm; sof&aacute;s cama: 

        <label><input name="supletorios" type="text" id="supletorios" size="4" /></label>

        <input type="text" name="sofa_p" id="sofa_p" value="<?php echo $row_casas['sofa']; ?>" />

        <br />







      N&ordm; camas supletorias:

      <label><input name="camas" type="text" id="camas" size="4" /></label>

      <input type="text" name="cama_p" id="cama_p" value="<?php echo $row_casas['cama']; ?>" />

      <br />
      N&ordm; cunas: 

      <label><input name="cunas" type="text" id="cunas" size="4" /></label>
      <input type="text" name="cuna_p" id="cuna_p" value="<?php echo $row_casas['cuna']; ?>" />
      <br />
      Animales: 

      <label><input name="animales" type="text" id="animales" size="20" />   &euro;
      </label>
      <br />
       Extras: 

       <label>

         <textarea name="extras" cols="20" rows="5" id="extras"></textarea>

       </label>

      <input name="extra_p" type="text" id="extra_p" value="" size="10" />

      &euro;</td>

		





    </tr>







    <tr valign="baseline">







      <td nowrap="nowrap" align="right">Tarifa:</td>







      <td>

        <label> <input name="tarifa" type="radio" id="tarifa_0" value="t1" checked="checked" /> 

          Temporada baja. (Precio: <?php echo $tarifa_normal; ?> &euro; d&iacute;a) </label> 

        N&ordm; d&iacute;as festivos:

        <input name="festivos" type="text" id="festivos" size="5" /> (Precio: <?php echo $tarifa_festivo; ?> &euro; d&iacute;a)

        <br />

        <label> <input type="radio" name="tarifa" value="t2" id="tarifa_0" /> Temporada media (Precio: <?php echo $tarifa_festivo; ?> &euro; d&iacute;a)</label>

        <br />

        <label> <input type="radio" name="tarifa" value="t3" id="tarifa_0" /> Temporada alta  (Precio: <?php echo $tarifa_festivo; ?> &euro; d&iacute;a)</label>

        <input name="precio1" type="hidden" id="precio1" value="<?php echo $row_casas['tarifa_d_b']; ?>" />

        <input type="hidden" name="precio2" id="precio2" value="<?php echo $row_casas['tarifa_d_f']; ?>" /></td>

    </tr>







    <tr valign="baseline">







      <td nowrap="nowrap" align="right">Descuento:</td>







      <td><label>







        <input type="text" name="descuento" id="descuento" />







      </label></td>







    </tr>

    <tr valign="baseline">

      <td nowrap="nowrap" align="right">Fecha de alta:</td>

      <td><input name="alta_dia" type="text" id="alta_dia" size="2" maxlength="2" value="<?php echo date("d"); ?>" /> 



      / <input name="alta_mes" type="text" id="alta_mes" size="2" maxlength="2" value="<?php echo date("m"); ?>" /> / <input name="alta_year" type="text" id="alta_year" size="4" maxlength="4" value="<?php echo date("Y"); ?>" /> 



      Ej: 20/10/2012</td>

    </tr>







    <tr valign="baseline">







      <td nowrap="nowrap" align="right">&nbsp;</td>







      <td><input type="submit" value="Insertar registro" class="bton-form"/></td>







    </tr>







  </table>







  <input type="hidden" name="MM_insert" value="form2" />







</form>







</div></div>







</body>







</html>







<?php







mysqli_free_result($cliente);















mysqli_free_result($atencion);















mysqli_free_result($casas);







?>








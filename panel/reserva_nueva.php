<?php 
require_once('archivos/funcion.php');
require_once('archivos/sesion.php');
require_once('../Connections/rural.php'); 
require_once('archivos/funciones.php');
require_once('archivos/funciones_frm.php'); 


if (!function_exists("GetSQLValueString")) {

function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 

{

  if (PHP_VERSION < 6) {

    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

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





$id = "-1";

if (isset($_GET['id_cliente'])) {

  $id = $_GET['id_cliente'];

}

mysqli_select_db( $rural, $database_rural);

$query_cliente = sprintf("SELECT * FROM clientes WHERE id_cliente = %s", GetSQLValueString($id, "int"));

$cliente = mysqli_query($rural, $query_cliente) or die(mysql_error());

$row_cliente = mysqli_fetch_assoc($cliente);

$totalRows_cliente = mysqli_num_rows($cliente);



mysqli_select_db( $rural, $database_rural);
$query_casas = "SELECT * FROM casa ORDER BY orden ASC";
$casas = mysqli_query($rural, $query_casas) or die(mysql_error());
$row_casas = mysqli_fetch_assoc($casas);
$totalRows_casas = mysqli_num_rows($casas);

mysqli_select_db( $rural, $database_rural);
$query_personal = "SELECT * FROM personal ORDER BY nombre ASC";
$personal = mysqli_query($rural, $query_personal) or die(mysql_error());
$row_personal = mysqli_fetch_assoc($personal);
$totalRows_personal = mysqli_num_rows($personal);
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



	<script>



	$(function() {



		var dates = $( "#entrada, #salida" ).datepicker({



			firstDay: 1,



			monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],



			monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dec'],



			dayNames: ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'],



			dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],



			changeMonth: true,



			numberOfMonths: 2,



			dateFormat: 'dd-mm-yy',



			onSelect: function( selectedDate ) {



				var option = this.id == "entrada" ? "minDate" : "maxDate",



					instance = $( this ).data( "datepicker" ),



					date = $.datepicker.parseDate(



						instance.settings.dateFormat ||



						$.datepicker._defaults.dateFormat,



						selectedDate, instance.settings );



				dates.not( this ).datepicker( "option", option, date );



			}



		});



	});



	</script>



</head>







<body>



<div id="contenido">



<div id="menu"><?php include('archivos/menu.html'); ?><div class="limpiar"></div></div>



<div class="caja_frm">



<h1> A&ntilde;adir reserva: paso 1</h1>



<form action="reserva_nueva_c.php" method="post" name="form1" id="form1">



  <table align="center">



    <tr valign="baseline">



      <td nowrap="nowrap" align="right">Nombre Cliente:</td>



      <td><?php echo $row_cliente['nombre'].' '.$row_cliente['apellidos']; ?><input type="hidden" name="cliente_id" id="cliente_id" value="<?php echo $row_cliente['id_cliente']; ?>" /></td>



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



      <td nowrap="nowrap" align="right">Atendido por:</td>



      <td><label>



        <select name="personal" id="personal">



          <?php



do {  



?>



          <option value="<?php echo $row_personal['id_personal']?>">&nbsp;<?php echo $row_personal['nombre'].' '.$row_personal['apellidos']?>&nbsp;</option>



          <?php



} while ($row_personal = mysqli_fetch_assoc($personal));



  $rows = mysqli_num_rows($personal);



  if($rows > 0) {



      mysqli_data_seek($personal, 0);



	  $row_personal = mysqli_fetch_assoc($personal);



  }



?>



        </select>



      </label></td>



    </tr>



    <tr valign="baseline">



      <td nowrap="nowrap" align="right">Casa:</td>



      <td><select name="casa_id" id="casa_id">
        <?php
do {  
?>
       <option value="<?php echo $row_casas['id_cas']?>"><?php echo $row_casas['nombre']?> (<?php echo $row_casas['plazas']; ?>)</option>
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



      <td nowrap="nowrap" align="right">&nbsp;</td>



      <td><input type="submit" value="Continuar" class="bton-form"/></td>



    </tr>



  </table>



  <input type="hidden" name="MM_insert" value="form1" />



</form>



</div></div>



</body>



</html>



<?php



mysqli_free_result($cliente);







mysqli_free_result($casas);







mysqli_free_result($personal);



?>




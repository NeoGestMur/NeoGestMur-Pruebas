<?php 
require_once('archivos/funcion.php');
require_once('archivos/sesion.php');
require_once('../Connections/rural.php'); 
require_once('archivos/callendario.php');
session_start();

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


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t�tulo</title>
<link rel="stylesheet" href="css/mini.css" type="text/css" media="screen" charset="utf-8" />
<script type="text/javascript" src="js/jquery-1.6.2.min.js"></script>
<script src="js/coda.js" type="text/javascript"> </script>
</head>

<body>
<?php include('archivos/menu.html'); 		// menu

if (isset($_SESSION['id'])){
	$id = $_SESSION['id'];	
}else{
	$id = 0;
}
if(isset($_GET['ida'])){
	$id = $id-1;
	$_SESSION['id'] = $id;
}
if(isset($_GET['ids'])){
	$id = $id+1;
	$_SESSION['id'] = $id;
}

///////////////////////////////////////////////////////////////
// fechas anterior y siguiente
$resta = $id.' month';
if (isset($_SESSION['id']) and $_SESSION['id']!=0){
	$fecha = date('Y-m-d', strtotime($resta));
}else{
	$fecha = date('Y-m-d');
}
$dia = substr($fecha,8,2);
$mes = substr($fecha,5,2);
$year = substr($fecha,0,4);
$numero_dias = cal_days_in_month(1,$mes,$year);


// restar un mes
$year_menos = $year;
$mes_menos = $mes - 1;
if($mes_menos < 1){
	$mes_menos = 12;
	$year_menos = $year-1;
}
$fecha_inicio= $year_menos.'-'.$mes_menos.'-01';
// fin de restar un mes
// sumar un mes
$year_mas = $year;
$mes_mas = $mes + 1;
if ($mes_mas > 12){
	$mes_mas = 1;
	$year_mas= $year_mas +1; 
}
$fecha_fin = $year_mas.'-'.$mes_mas.'-'.$numero_dias;
// fin de sumar un mes
echo $fecha_inicio.'/'.$fecha_fin.'<br />';
//
//////////////////////////////////////////////////////////////

mysql_select_db($database_rural, $rural);
$query_casas = "SELECT * FROM casa ORDER BY nombre ASC";
$casas = mysql_query($query_casas, $rural) or die(mysql_error());

while ($row_casas = mysql_fetch_assoc($casas)) { ?>
  <p>Nombre: <?php echo $row_casas['nombre']; ?></p>
  <?php 
	$idc = $row_casas['id_cas'];
	$enlace = 'reservas_todas.php';
	calendario($idc,$database_rural,$rural,$fecha_inicio,$fecha_fin,$year,$mes,$dia,$enlace);
}  ?>
</body>
</html>
<?php
mysql_free_result($casas);
?>

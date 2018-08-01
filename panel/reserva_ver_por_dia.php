<?php
require_once('archivos/funcion.php');
require_once('archivos/funciones.php');
?>
<html>
<head>
<meta charset="utf-8" />
<title>Panel de control: La Caraba</title>
<link href="css/estilos.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="css/master.css" type="text/css" media="screen" charset="utf-8" />
<script type="text/javascript" src="js/jquery-1.6.2.min.js"></script>
<script src="js/coda.js" type="text/javascript"> </script>
<link href="css/calendario.css" rel="stylesheet" type="text/css" />

</head>

<body>
<div id="contenido">
<div id="menu"><?php include('archivos/menu.html'); ?><div class="limpiar"></div></div>
<?php
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
$fecha = date('Y-m-d');  		// fecha de la que se parte

$dia = substr($fecha,8,2);
$mes = substr($fecha,5,2);
$year = substr($fecha,0,4);


if (isset($_SESSION['id']) and $_SESSION['id']!=0){
	$numero_diass = cal_days_in_month(1,$mes,$year);
	if ($numero_diass == 31){
		$dias_sumar = 30*$_SESSION['id'];
		$fechaa  = date('Y/m/d',mktime(0, 0, 0, date("m"), date("d")+$dias_sumar, date("Y")));	
		//echo 'prueba: '.$fechaa;
		$mes = $mes + $_SESSION['id'];
	}else{
		//echo $numero_diass.'<br>';
		$mes = $mes + $_SESSION['id'];
		//$fechaa = $year.'-'.$mes.'-'.$dia;
		$fechaa=date("Y-m-d", mktime(0,0,0,$mes,$dia,$year));
	}
}else{
	$fechaa = date('Y-m-d');
}

$dia = substr($fechaa,8,2);
$mes = substr($fechaa,5,2);
$year = substr($fechaa,0,4);
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
//
//////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////
// datos calendario


require_once('../Connections/rural.php');
require_once('archivos/callendario.php');
$idc = $_GET['id_casa'];
$enlace = 'reserva_ver_por_dia.php';

?>
<div class="calendariog">
<?php
//calendario($idc,$database_rural,$rural,$fecha_inicio,$fecha_fin,$year,$mes,$dia,$enlace);
$yearCalendario = date('Y');if (isset($_GET['ano'])) {$yearCalendario = $_GET['ano'];} // Si no se define año en el parámetro de URL, el año actual
$mesCalendario = date('n');if (isset($_GET['mes'])) {$mesCalendario = $_GET['mes'];} // Si no se define mes en el parámetro de URL, el mes actual
$finDeSemana = 1;if (isset($_SESSION['finde'])) {$finDeSemana = $_SESSION['finde'];} // Días de fin de semana activados: '1' para activado, '0' para desactivado (se predetermina a 1)
$nivelH = 2; // Nivel para el encabezado del bloque de calendario, con valor entre '1' y '6'. Se predetermina en '2'.
echo calendario($yearCalendario,$mesCalendario,$finDeSemana,$nivelH,$idc,$database_rural,$rural);
?>
</div>
	</div>	
</body>
</html>
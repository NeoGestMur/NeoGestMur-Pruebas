<?php
require_once('archivos/funcion.php');
require_once('archivos/funciones.php');
require_once('../Connections/rural.php'); 
require_once('archivos/callendario_mini.php');

//////////////////////////////////////////////////////////////
/////////////////////////////////////////////////
$meses = array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiempre','Octubre','Noviembre','Diciembre');
$idc = $_POST['idc'];

if (isset($_SESSION['id'])){
	$id = $_SESSION['id'];
}else{
	$id = 0;
}
if(isset($_POST['ids'])){
	$id = $id+1;
	$_SESSION['id'] = $id;
}
if(isset($_POST['ida'])){
	$id = $id-1;
	$_SESSION['id'] = $id;
}

$yearCalendario = date('Y');if (isset($_POST['ano'])) {$yearCalendario = $_POST['ano'];} // Si no se define año en el parámetro de URL, el año actual
$mesCalendario = date('n');if (isset($_POST['mes'])) {$mesCalendario = $_POST['mes'];} // Si no se define mes en el parámetro de URL, el mes actual
$finDeSemana = 1;if (isset($_SESSION['finde'])) {$finDeSemana = $_SESSION['finde'];} // Días de fin de semana activados: '1' para activado, '0' para desactivado (se predetermina a 1)
$nivelH = 2; // Nivel para el encabezado del bloque de calendario, con valor entre '1' y '6'. Se predetermina en '2'.
  
$fecha =gmmktime(0,0,0,($mesCalendario+$id),1,$yearCalendario);
$year = date('Y', $fecha);
$mes = date('n', $fecha);
//echo 'id: '.$id.' -';
echo $meses[$mes-1].' ('.$year.')';
echo calendario($year,$mes,$finDeSemana,$nivelH,$idc,$database_rural,$rural);

?>
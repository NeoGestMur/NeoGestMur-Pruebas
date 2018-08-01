<?php
require_once('archivos/funcion.php');
require_once('../Connections/rural.php');
require_once('archivos/callendario_mini.php');
require_once('archivos/funciones.php');

$meses = array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiempre','Octubre','Noviembre','Diciembre');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Panel de control: Pruebas</title>
<script type="text/javascript" src="js/jquery-1.6.2.min.js"></script>
<script src="js/coda.js" type="text/javascript"> </script>
<link href="css/estilos.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php  //echo 'id: '.$id ;?>
<div id="contenido">

<div id="menu"><?php include('archivos/menu.html'); ?><div class="limpiar"></div></div>
<?php

if ($_SESSION['rangoUsu']== 1) {
    $rangoreal = "Administrador";
} else {
    $rangoreal = "Empleado";
}
echo "Hola ".$_SESSION['nombreUsu']." recuerda que tu rango es de ".$rangoreal." ";
?>



<?php
$yearCalendario = date('Y');if (isset($_GET['ano'])) {$yearCalendario = $_GET['ano'];} // Si no se define año en el parámetro de URL, el año actual
$mesCalendario = date('n');if (isset($_GET['mes'])) {$mesCalendario = $_GET['mes'];} // Si no se define mes en el parámetro de URL, el mes actual
$finDeSemana = 1;if (isset($_SESSION['finde'])) {$finDeSemana = $_SESSION['finde'];} // Días de fin de semana activados: '1' para activado, '0' para desactivado (se predetermina a 1)
$nivelH = 6; // Nivel para el encabezado del bloque de calendario, con valor entre '1' y '6'. Se predetermina en '2'.


$enlaceAnterior1 = gmmktime(0,0,0,($mesCalendario-1),1,$yearCalendario);
$mesAnterior = date('n',$enlaceAnterior1);
$yearMesAnterior = date('Y',$enlaceAnterior1);
$enlaceSiguiente1 = gmmktime(0,0,0,($mesCalendario+1),1,$yearCalendario);
$mesSiguiente = date('n',$enlaceSiguiente1);
$yearMesSiguiente = date('Y',$enlaceSiguiente1);
?><div id="mes"><h1><a href="inicio.php?mes=<?php echo $mesAnterior; ?>&ano=<?php echo $yearMesAnterior; ?>">&laquo;</a>&nbsp;&nbsp;<?php echo $meses[$mesCalendario-1].' ('.$yearCalendario.')'; ?>&nbsp;&nbsp;<a href="inicio.php?mes=<?php echo $mesSiguiente; ?>&ano=<?php echo $yearMesSiguiente; ?>">&raquo;</a></h1></div>

<div id="calendarios">
<img src="img/leyenda.jpg" width="510" height="41" /><br />

<?php
mysqli_select_db($rural, $database_rural);
$query_casas = "SELECT * FROM casa ORDER BY orden ASC";
$casas = mysqli_query($rural, $query_casas) or die(mysql_error());


while ($row_casas = mysqli_fetch_assoc($casas)) { ?>

	<div class="calendario">

  	<h1><a href="reserva_ver_por_dia.php?id_casa=<?php echo $row_casas['id_cas']; ?>&mes=<?php echo $mesCalendario; ?>&ano=<?php echo $yearCalendario; ?>"><?php echo $row_casas['nombre']; ?></a></h1>

  	<?php 
	$idc = $row_casas['id_cas'];
	echo calendario($yearCalendario,$mesCalendario,$finDeSemana,$nivelH,$idc,$database_rural,$rural);
	?>

	</div>

	<?php

}

mysqli_free_result($casas);

?><div class="limpiar"></div><br />
  <img src="img/leyenda.jpg" width="510" height="41" /></div>

</div>

</body>

</html>
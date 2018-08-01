<?php 
require_once('../Connections/rural.php'); 

function fecha_e($dato){
	if($dato != ''){
		$fecha = $dato;
		$dia = substr($fecha, 8, 2);
		$mes = substr($fecha,5,2);
		$year = substr($fecha, 0,4);
		$hora = substr($fecha, 10);

		//$corregido = $dia."/".$mes."/".$year.$hora;
		$corregido = $dia."/".$mes."/".$year;
		echo $corregido;
	}
}
// eliminar
if (isset($_GET['ide'])){
	$ide = $_GET['ide'];
	
	// eliminar archivo
	if (isset($_GET['img'])){
		
		$camino = "../visitas/libro/".$_GET['img'];
		
		if(file_exists($camino))
			unlink($camino);
		
	}
	// fin de eliminar archivo
	
	mysqli_select_db($rural, $database_rural);
	
	$sql = "DELETE FROM comentarios WHERE id=".$ide;
	mysqli_query($rural, $sql) or die(mysqli_error());	
	
	$ir = "comentarios_lista.php";
  	header(sprintf("Location: %s", $ir));
}
// fin eliminar

mysqli_select_db($rural, $database_rural);

$id=-1;
if(isset($_GET['id']))
	$id = $_GET['id'];

$sql = "SELECT * FROM comentarios WHERE id=".$id;
$rs = mysqli_query($rural, $sql) or die(mysql_error());
$resultados = mysqli_fetch_assoc($rs);
$cuantos = mysqli_num_rows($rs);
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8" />
<title>Documento sin título</title>
<link href="css/estilos.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="contenido">
<div id="menu"><?php include('archivos/menu.html'); ?><div class="limpiar"></div></div>
Fecha: <?php fecha_e($resultados['fecha']); ?><br>
Autor: <?php echo strip_tags($resultados['autor']); ?><br>
Comentario: <?php echo strip_tags($resultados['comentario']); ?><br>
Imagen: <?php echo $resultados['imagen']; ?><br>
<?php
$ruta = "";
if($resultados['imagen']=="sinfoto")
	$ruta = "?ide=".$id;
else
	$ruta = "?ide=".$id."&img=".$resultados['imagen'];
?>
<div class="bton_nuevo"><a href="comentario_eliminar.php<?php echo $ruta; ?>">Eliminar</a></div><div class="bton_nuevo"><a href="comentarios_lista.php">Cancelar</a></div></div>
</body>
</html>
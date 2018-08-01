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

mysqli_select_db($rural, $database_rural);

$sql = "SELECT * FROM comentarios ";
$rs = mysqli_query($rural, $sql) or die(mysqli_error());
$resultados = mysqli_fetch_assoc($rs);
$cuantos = mysqli_num_rows($rs);
?>

<html>
<head>
<meta charset="utf-8" />
<title>Comentarios</title>
<link href="css/estilos.css" rel="stylesheet" type="text/css" />
		<style type="text/css" title="currentStyle">
			@import "media/css/demo_page.css";
			@import "media/css/demo_table.css";
		</style>
		<script type="text/javascript" language="javascript" src="js/jquery-1.6.2.min.js"></script>
		<script type="text/javascript" language="javascript" src="js/jquery.dataTables.js"></script>
        <script type="text/javascript" language="javascript" src="js/thickbox.js"></script>
        		<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				$('#lista').dataTable({
						"aaSorting": [[ 0, "desc" ]],
						"aoColumns": [
							null,
							null,
							{ "asSorting": [ 0 ] },
							{ "asSorting": [ 0 ] },
							{ "asSorting": [ 0 ] }
						]
				});
				$('#lista tbody td img.a').live( 'click', function () {
					var donde = $(this.parentNode);
					var Id = donde.attr('id');					
					//$(donde).load('funciones/activo.php?id='+Id);					
					//alert(Id);

				$.ajax({
					type:"POST",
					cache: false,
					url: 'funciones/activo.php?id='+Id,
					data: '{}',
					async: false,
					success: function(data){
						result = data;
						$(donde).html(result);
					}
				});
				
				} );
			} );
			
		</script>
</head>

<body>
<div id="contenido">
<div id="menu"><?php include('archivos/menu.html'); ?><div class="limpiar"></div></div>
<div class="tabla">
<table cellpadding="0" cellspacing="0" border="0" class="display" id="lista">
	<thead>
  		<tr class="barra">
    	<th width="30" height="28" scope="col">Id</th>
        <th width="70" scope="col">Autor</th>
    	<th width="100" scope="col">Fecha</th>
        <th width="250" scope="col">Comentario</th>
    	<th width="50" scope="col">Acciones</th>
  		</tr>
  	</thead>
  <?php do { ?>
    <tr>
      <td align="left"><?php echo $resultados['id']; ?></td>
      <td align="left"><?php echo $resultados['autor']; ?></td>
      <td align="center"><?php echo$resultados['fecha']; ?></td>
      <td align="left"><?php echo $resultados['comentario']; ?></td>
      <td align="center"><a href="comentario_eliminar.php?id=<?php echo $resultados['id']; ?>"><img src="img/trash.png" alt="Eliminar" width="20" height="20" border="0" title="Eliminar" /></a></td>
    </tr>
    <?php } while ($resultados = mysqli_fetch_assoc($rs)); ?>
    	<tfoot>
		<tr class="barra">
			<th height="28">Id</th>
            <th>Autor</th>
			<th>Fecha</th>
            <th>Comentario</th>
			<th>Acciones</th>
		</tr>
	</tfoot>
</table>
</div>
</div>
</body>
</html>
<?php 
require_once('archivos/funcion.php');
require_once('../Connections/rural.php');
require_once('archivos/funciones.php');

mysqli_select_db($rural, $database_rural);

$query_lista_casas = "SELECT * FROM casa ORDER BY orden ASC";
$lista_casas = mysqli_query($rural, $query_lista_casas) or die(mysql_error());
$row_lista_casas = mysqli_fetch_assoc($lista_casas);
$totalRows_lista_casas = mysqli_num_rows($lista_casas);

?>


<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
<title>Panel de control: La Caraba</title>
<link href="css/estilos.css" rel="stylesheet" type="text/css" />
<link href="css/imprimir.css" rel="stylesheet" type="text/css" media="print"> 
<link type="text/css" href="css/ui-lightness/jquery-ui-1.8.16.custom.css" rel="stylesheet" />

<script type="text/javascript" src="js/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.16.custom.min.js"></script>
<script src="js/coda.js" type="text/javascript"> </script>

<script>
	$(function() {
		
		var dates = $( "#entrada, #salida" ).datepicker({

			firstDay: 1,
			monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
			monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
			dayNames: ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'],
			dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
			changeMonth: true,
			numberOfMonths: 1,
			dateFormat: 'dd-mm-yy'

		});
	
	});
</script>

</head>

<body>
<div id="contenido">

<div id="menu"><?php include('archivos/menu.html'); ?><div class="limpiar"></div></div>


<div class="tabla">
	<div class="frm-b">
	<form action="buscador.php" method="post" name="buscar">
    <table width="100%" border="0">
  <tr>
    <td align="center">Fecha de inicio: 
      <input name="entrada" type="text" id="entrada" /></td>
    <td width="300" align="center">Fecha de fin: 
      <input type="text" name="salida" id="salida" /></td>
    <td width="150" align="center">Casa: 
      <select name="casa" id="casa">
        <option value="0">Todas</option>        
        <?php 
		do { 
			?><option value="<?php echo $row_lista_casas['id_cas']; ?>"><?php echo $row_lista_casas['nombre']; ?></option><?php
		} while ($row_lista_casas = mysqli_fetch_assoc($lista_casas)); ?>        
        
      </select></td>
    <td width="150" align="center"><input type="submit" name="busca" id="busca" value="Buscar" />
      <input name="oculto" type="hidden" id="oculto" value="2" /></td>
  </tr>
</table>

    </form></div>
    
    <div class="resultados">
    
    <?php
	if ((isset($_POST["oculto"])) && ($_POST["oculto"] == "2")) {
		
		$suma = 0;
		
		?><div class="bton_nuevo"><a href="#" onclick="window.print();">Imprimir</a></div><?php
		$cadena = '';		
		
		// fechas
		if ( isset($_POST['entrada']) && $_POST["entrada"] != "" && isset($_POST['salida']) && ($_POST["salida"] != "")) {
			$entrada = formato_in($_POST['entrada']); //echo $entrada;
			$salida = formato_in($_POST['salida']); //echo $salida;
			//WHERE Date BETWEEN '06-Jan-1999' AND '10-Jan-1999'
			$cadena = " AND r.entrada BETWEEN '".$entrada."' AND '".$salida."' ";
		}else{
			if (isset($_POST['entrada']) && ($_POST["entrada"] != "")) {
				$entrada = formato_in($_POST['entrada']); //echo $entrada;
				$cadena = " AND r.entrada = '".$entrada."' ";
			}		
			if (isset($_POST['salida']) && ($_POST["salida"] != "")) {
				$salida = formato_in($_POST['salida']); //echo $salida;
				$cadena = " AND r.salida = '".$salida."' ";
			}	
		}
		
		// casas
		if (isset($_POST['casa']) && $_POST['casa'] != 0){
			$casa = $_POST['casa'];
			$cadena .= " AND r.casa_id = '".$casa."' ";	
		}		
		
	
	$query_reserva_lista = "SELECT r.id_reserva, c.nombre as casa, cl.nombre as cliente, cl.apellidos as apellido, r.estado, r.entrada, r.salida, r.fecha_alta, r.tipo_tarifa, r.precio, r.precio2, r.dias, r.descuento, r.suplementos, r.p_suplemento, r.camas, r.p_cama, r.cunas, r.p_cuna, r.animales, r.precio_extras FROM reserva r, casa c, clientes cl WHERE r.casa_id= c.id_cas AND cl.id_cliente=r.cliente_id ".$cadena." ORDER BY r.entrada ASC";
	$reserva_lista = mysqli_query( $rural, $query_reserva_lista) or die(mysqli_error());
	$totalRows_reserva_lista = mysqli_num_rows($reserva_lista);	
	
	?>
    
    <table width="100%" border="0">
  <tr class="barra">
    <th width="120" height="25" scope="col">Fecha inicio</th>
    <th width="120" scope="col">Fecha salida</th>
    <th width="70" scope="col">n&ordm; d&iacute;as</th>
    <th width="100" scope="col">casa</th>
    <th scope="col">Cliente</th>
    <th width="120" scope="col">Total</th>
  </tr>
  <?php 
  $n = 0;
  while ($row_reserva_lista = mysqli_fetch_assoc($reserva_lista)) { 
  $clase = 'clase1';
  if ($n == 0){
		$clase = 'clase1';
		$n++;
  }else{
		$clase = 'clase2';  
		$n = 0;
  }
	  
  
  ?>
  <tr class="<?php echo $clase; ?>">
    <td height="25" align="center"><?php echo formato_es($row_reserva_lista['entrada']); ?></td>
    <td align="center"><?php echo formato_es($row_reserva_lista['salida']); ?></td>
    <td align="center"><?php 
	  $ini = formato_es($row_reserva_lista['entrada']);
	  $fin = formato_es($row_reserva_lista['salida']);
	  $dias = restaFechas($ini, $fin); 
	  echo $dias;
	  ?></td>
    <td>&nbsp;<?php echo $row_reserva_lista['casa']; ?></td>
    <td>&nbsp;<?php echo $row_reserva_lista['cliente'].' '.$row_reserva_lista['apellido']; ?></td>
    <td align="right"><?php
	
    // calcular el total
	$tipo = $row_reserva_lista['tipo_tarifa'];
	  if ($tipo == 't1') {
		  //echo 'temporada baja';
		  $precio = $row_reserva_lista['precio'];
	  }
	  if ($tipo == 't2') {
		  //echo 'temporada media';
		  $precio = $row_reserva_lista['precio2'];
	  }
	  if ($tipo == 't3') {
		  //echo 'temporada alta';
		  $precio = $row_reserva_lista['precio2'];
	  }	
	  
	  	// dias esta calculado arriba
		$dias_festivos = $row_reserva_lista['dias'];
		$dias_normales = $dias-$dias_festivos;
		
		$td = $dias_normales*$precio;		// precio dias normales
		$tf = $dias_festivos*$row_reserva_lista['precio2'];	// precio dias festivos
		$total_d = $td + $tf - $row_reserva_lista['descuento'];	// total con el descuento, pero sin extras
		
		//echo $total_d;
		// suplementos
		if ($row_reserva_lista['suplementos'] != NULL){ // suplementos, realmente son sofas
			if ($row_reserva_lista['p_suplemento']== NULL) $precio_s1 = 0; else $precio_s1 = $row_reserva_lista['p_suplemento'];
			$p_s = $dias*($row_reserva_lista['suplementos']*$precio_s1);
			$total_d = $total_d + $p_s;
		}
		if ($row_reserva_lista['camas'] != NULL){ 	// cama supletorias
			if ($row_reserva_lista['p_cama']== 	NULL) $precio_s2 = 0; else $precio_s2 = $row_reserva_lista['p_cama'];
			$p_c =  $dias*($row_reserva_lista['camas']*$precio_s2);
			$total_d = $total_d + $p_c;
		}
		if ($row_reserva_lista['cunas'] != NULL){		// cunas
			if ($row_reserva_lista['p_cuna']== NULL) $precio_s3 = 0; else $precio_s3 = $row_reserva_lista['p_cuna'];
			$p_cu = $dias*($row_reserva_lista['cunas']*$precio_s3);
			$total_d = $total_d + $p_cu;
		}
		if ($row_reserva_lista['animales'] != NULL) { // animales
	  		$animales = $row_reserva_lista['animales'];
			$total_d = $total_d + $animales;
		}
		if ($row_reserva_lista['precio_extras'] != NULL){
			$extras = $row_reserva_lista['precio_extras'];
			$total_d = $total_d + $extras;
		}
		$suma = $suma + $total_d;
		echo $total_d.'&#8364';
    // fin del total
    ?>&nbsp;
    </td>
  </tr>
  <?php }  ?>
  <tr class="total">
    <td height="25" align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td>&nbsp;</td>
    <td align="right">Total:</td>
    <td align="right"><?php echo $suma; ?> &euro; </td>
  </tr>
</table>
<div class="bton_nuevo"><a href="#" onclick="window.print();">Imprimir</a></div>
<?php } ?>

    </div>
</div>
</div>
</body>
</html>
<?php
require_once('archivos/funcion.php');
require_once('../Connections/rural.php');
require_once('archivos/funciones.php');

$id_reserva = $_GET['id_reserva'];

mysqli_select_db($rural, $database_rural);

$query_reserva_f = "SELECT casa.nombre as casa, clientes.nombre as cliente, clientes.apellidos, clientes.dni, clientes.telefono, clientes.domicilio, clientes.cp, clientes.poblacion, clientes.provincia, reserva.id_reserva, reserva.tipo_tarifa, reserva.entrada, reserva.salida, reserva.dias, reserva.precio, reserva.precio2, reserva.descuento, reserva.suplementos, reserva.camas, reserva.cunas, reserva.p_suplemento, reserva.p_cama, reserva.p_cuna, reserva.animales, reserva.extras, reserva.precio_extras FROM reserva, casa, clientes WHERE id_reserva = ".$id_reserva." AND casa.id_cas= reserva.casa_id AND clientes.id_cliente= reserva.cliente_id";

$reserva_f = mysqli_query($rural, $query_reserva_f) or die(mysqli_error());
$row_reserva_f = mysqli_fetch_assoc($reserva_f);
$totalRows_reserva_f = mysqli_num_rows($reserva_f);

$dias =  restarfecha($row_reserva_f['entrada'],$row_reserva_f['salida']);
$dias_festivos = $row_reserva_f['dias'];
$dias_normales = $dias-$dias_festivos;
$precio1 = $row_reserva_f['precio'];
$precio2 = $row_reserva_f['precio2'];
$sofas = $row_reserva_f['suplementos'];
$camas = $row_reserva_f['camas'];
$cunas = $row_reserva_f['cunas'];
$p_sofa = $row_reserva_f['p_suplemento'];
$p_cama = $row_reserva_f['p_cama'];
$p_cuna = $row_reserva_f['p_cuna'];
$extra = $row_reserva_f['extras'];
$p_extra = $row_reserva_f['precio_extras'];
$animales = $row_reserva_f['animales'];
?>

<html>
<head>
<meta charset="utf-8" />
<title>Casas Rurales La Caraba</title>
<style type="text/css">
<!--
body,td,th {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 1em;
	line-height: 1.3em;
}
#factura{
	width: 900px;
	margin: 0 auto;
	font-size: 0.9em;
	
}
.factura td{
	padding: 10px;
	text-align: left;
}

.factura2 td{
	padding: 5px;
}
-->

</style>

<link href="css/estilos.css" rel="stylesheet" type="text/css" />
<link href="css/impresion.css" rel="stylesheet" type="text/css" media="print">
</head>

<body>
<?php 
$suma = 0;
$tarifa = $row_reserva_f['tipo_tarifa']; 
switch ($tarifa) {
    case 't1':
        //echo "Temporada baja";
		$suma_d = $dias_normales*$precio1 + $dias_festivos*$precio2;
        break;
    case 't2':
        //echo "temporada media";
		$suma_d = $dias*$precio2;
        break;
    case 't3':
        //echo "temporada alta";
		$suma_d = $dias*$precio2;
        break;
}
// sofas
$suma_b = $suma_d;
$suma = $suma_d;
if ($sofas != NULL) {
	$precio_sofa = $dias*($sofas * $p_sofa);
	$suma +=  $precio_sofa;
}

//echo $suma.'-';
// camas
if ($camas != NULL){
	$precio_cama = $dias*($camas * $p_cama);
	$suma += $precio_cama;
}

//echo $suma.'-';
// camas
if ($cunas != NULL){ 
	$precio_cuna = $dias*($cunas * $p_cuna);
	$suma += $precio_cuna;
}
// extras
if ($p_extra != NULL){ 
	$precio_extra = $p_extra;
	$suma += $precio_extra;
}
// animales
if ($animales != NULL){ 
	$animales = $animales;
	$suma += $animales;
}

//echo $suma.'-';
////////////////////////////////
$descuento = $row_reserva_f['descuento'];
if($descuento != NULL){
	//echo '<br />Descuento: '.$descuento;
	$suma = $suma-$descuento;	
}
//echo $suma.'-';
//$iva = $suma *0.08;
?>
<div id="contenido">
<div id="menu"><?php include('archivos/menu.html'); ?><div class="limpiar"></div></div>
</div>
<div id="factura">

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="factura">

  <tr>

    <td width="40%" align="center">&nbsp;</td>

    <td width="27%" align="center">&nbsp;</td>

    <td width="33%">
     <div class="cliente-datos-tit"><strong>CLIENTE</strong></div>

        <div class="cliente-datos"> <?php echo $row_reserva_f['cliente'].' '.$row_reserva_f['apellidos']; ?><br />
        <?php echo $row_reserva_f['dni']; ?><br />
        <?php echo $row_reserva_f['domicilio']; ?><br />
        <?php echo $row_reserva_f['cp'].' '.$row_reserva_f['poblacion'].' ('.$row_reserva_f['provincia'].')'; ?><br />
        Tel: <?php echo $row_reserva_f['telefono']; ?><br /></div>
    </td>

  </tr>

  <tr>

    <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="factura2">

      <tr>

        <th width="587" align="left">DESCRIPCI&Oacute;N</th>

        <th width="243" align="left">PRECIO</th>

      </tr>

      <tr>

        <td align="left"><strong>Nombre de la casa:</strong> <?php echo $row_reserva_f['casa']; ?>. <br />
          <strong>Fecha de entrada:</strong> <?php echo formato_es($row_reserva_f['entrada']); ?> <br />
          <strong>Fecha de salida:</strong> <?php echo formato_es($row_reserva_f['salida']); ?>

        <br /> 
        <strong>N&ordm; de d&iacute;as:</strong> <?php echo $dias; ?>
        <br /> 
        
        </td>

        <td align="right" class="totales"><?php echo number_format($suma_b, 2, ",", "."); ?> &euro;</td>

      </tr>
      <tr>
        <td align="left">
        <strong>Suplementos: </strong><br />
        </td>
        <td align="right">&nbsp;</td>
      </tr>
      <?php if($sofas != NULL){ ?> 
      <tr>
        <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;<?php if($sofas != NULL) echo $sofas.' Sof&aacute;s cama '; ?></td>
        <td align="right" class="totales"><?php if (isset($precio_sofa)) echo number_format($precio_sofa, 2, ",", ".").' &euro;'; ?></td>
      </tr>
      <?php } ?>
      <?php if($camas != NULL){ ?> 
      <tr>
        <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;<?php if($camas != NULL) echo $camas.' Camas ';  ?></td>
        <td align="right" class="totales"><?php if (isset($precio_cama)) echo number_format($precio_cama, 2, ",", ".").' &euro;'; ?></td>
      </tr>
      <?php } 
	  if($cunas != NULL){ ?> 
      <tr>
        <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;<?php if($cunas != NULL) echo $cunas.' Cunas '; ?></td>
        <td align="right" class="totales"><?php if (isset($precio_cuna)) echo number_format($precio_cuna, 2, ",", ".").' &euro;'; ?></td>
      </tr>
<?php 
 }
 
	if($animales != NULL){ ?> 
      <tr>
        <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;<?php if($animales != NULL) echo 'Animales '.' <br />'; ?></td>
        <td align="right" class="totales"><?php 
		if (isset($animales)) {
			$animales_c = $animales;
			echo number_format($animales_c, 2, ",", ".").' &euro;'; 
		}
		?></td>
      </tr>
<?php } 
 
if($p_extra != NULL){ ?> 
      <tr>
        <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;<?php if($extra != NULL) echo 'Extras:  '.$extra;?></td>
        <td align="right" class="totales"><?php 
		if (isset($precio_extra)) {
			$sin_ex = $precio_extra;
			echo number_format($sin_ex, 2, ",", ".").' &euro;'; 
		}
		?></td>
      </tr>
<?php ?>
<?php
	  }
if($descuento != NULL){
	//echo '<br />Descuento: '.$descuento;
	//$suma = $suma-$descuento;	
?>

      <tr>

        <td align="left"><strong>Descuento:</strong></td>

        <td align="right" class="totales">- <?php echo $descuento; ?> &euro;</td>

      </tr>

<?php
}
?>

    </table></td>

  </tr>

  <tr>

    <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="factura2">

      <tr>

        <td width="60%">&nbsp;</td>

        <td width="10%"></td>

        <td width="30%" align="right" class="totales"><?php // echo $iva.' &euro;'; ?> </td>

      </tr>

      <tr>

        <td>&nbsp;</td>

        <td><strong>TOTAL:</strong></td>

        <td align="right" class="totales"><?php $total = $suma; echo number_format($total, 2, ",", ".");?> &euro;</td>

      </tr>

    </table></td>

  </tr>

  <tr>

    <td colspan="3">&nbsp;</td>

  </tr>

</table>



</div>

</body>

</body>

</html>

<?php

mysqli_free_result($reserva_f);

?>


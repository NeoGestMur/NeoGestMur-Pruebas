<?php
require_once('archivos/funcion.php');
require_once('../Connections/rural.php');
require_once('archivos/funciones.php');

$id_reserva = $_GET['id_reserva'];

mysqli_select_db ($rural, $database_rural);

$query_reserva_f = "SELECT casa.nombre as casa, clientes.nombre as cliente, clientes.apellidos, clientes.dni, clientes.telefono, clientes.domicilio, clientes.cp, clientes.poblacion, clientes.provincia, reserva.id_reserva, reserva.tipo_tarifa, reserva.entrada, reserva.salida, reserva.dias, reserva.precio, reserva.precio2, reserva.descuento, reserva.suplementos, reserva.camas, reserva.cunas, reserva.p_suplemento, reserva.p_cama, reserva.p_cuna, reserva.animales, reserva.n_f, reserva.fecha, reserva.extras, reserva.precio_extras FROM reserva, casa, clientes WHERE id_reserva = ".$id_reserva." AND casa.id_cas= reserva.casa_id AND clientes.id_cliente= reserva.cliente_id";

$reserva_f = mysqli_query($rural, $query_reserva_f) or die(mysql_error());
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
$extra = $row_reserva_f['extras'];
$p_extra = $row_reserva_f['precio_extras'];
$p_sofa = $row_reserva_f['p_suplemento'];
$p_cama = $row_reserva_f['p_cama'];
$p_cuna = $row_reserva_f['p_cuna'];
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
$iva = $suma *0.10;
?>
<div id="contenido">
<div id="menu"><?php include('archivos/menu.html'); ?><div class="limpiar"></div></div>
</div>
<div id="factura">

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="factura">

  <tr>

    <td width="40%" align="center" valign="top"><strong>Casas de Monta&ntilde;a La Caraba, SL <br />
      </strong>Ctra. del Canal, Km 2 <br />
      30440 &middot; Moratalla-Murcia <br />
      CIF: B73015257<br />
      Tel: 968 73 02 50 - 968 70 61 79 <br />
Fax: 968 73 02 50 <br />
www.casasruraleslacaraba.com</td>

    <td width="26%" align="center">&nbsp;</td>

    <td width="34%">
<div class="factura-nombre"><strong>FACTURA</strong></div>
    	<div class="cliente-datos"> 
    	<strong>N&ordm; Factura: </strong><?php echo $row_reserva_f['n_f']; ?><br />
        <strong>Fecha: </strong><?php 
function fecha_dia($dato){
	$fechainicio = $dato;
	$fechainicio_dia = substr($fechainicio,8,2);
	return $fechainicio_dia;
}

function fecha_mes($dato){
	$fechainicio = $dato;
	$fechainicio_mes = substr($fechainicio,5,2);
	return $fechainicio_mes;
}

function fecha_year($dato){
	$fechainicio = $dato;
	$fechainicio_year = substr($fechainicio,0,4);
	return $fechainicio_year;
}		
		echo fecha_dia($row_reserva_f['fecha']).'/'.fecha_mes($row_reserva_f['fecha']).'/'.fecha_year($row_reserva_f['fecha']);?>
        
      </div> <br />
        <div class="cliente-datos-tit"><strong>CLIENTE</strong></div>
       <div class="cliente-datos"> <?php echo $row_reserva_f['cliente'].' '.$row_reserva_f['apellidos']; ?><br />
        <?php echo $row_reserva_f['dni']; ?><br />
        <?php echo $row_reserva_f['domicilio']; ?><br />
        <?php echo $row_reserva_f['cp'].' '.$row_reserva_f['poblacion'].' ('.$row_reserva_f['provincia'].')'; ?><br />
        Tel: <?php echo $row_reserva_f['telefono']; ?></div>
    </td>

  </tr>

  <tr>

    <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="factura2">

      <tr>

        <th width="592" align="left">DESCRIPCI&Oacute;N</th>

        <th width="238" align="left">PRECIO</th>

      </tr>

      <tr>

        <td align="left"><strong>Nombre de la casa:</strong> <?php echo $row_reserva_f['casa']; ?>. <br />
          <strong>Fecha de entrada:</strong> <?php echo formato_es($row_reserva_f['entrada']); ?> <br />
          <strong>Fecha de salida:</strong> <?php echo formato_es($row_reserva_f['salida']); ?>

        <br /> 
        <strong>N&ordm; de d&iacute;as:</strong> <?php echo $dias; ?>
        <br /> 
        
        </td>

        <td align="right" class="totales"><?php 
		$sin = $suma_d;
		
		echo number_format($sin, 2, ",", "."); 
		?> &euro;</td>

      </tr>
      <tr>
        <td align="left">
        <strong>Suplementos: </strong><br />
        </td>
        <td align="right">&nbsp;</td>
      </tr>
     <?php if($sofas != NULL){ ?> 
      <tr>
        <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;<?php if($sofas != NULL) echo $sofas.' Sof&aacute;s cama '.'<br />'; ?></td>
        <td align="right" class="totales"><?php 
		if (isset($precio_sofa)) {
			$sin_a = $precio_sofa;
			echo number_format($sin_a, 2, ",", ".").' &euro;'; 
		}
		?></td>
      </tr>
      <?php } ?>
      <?php if($camas != NULL){ ?> 
      <tr>
        <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;<?php if($camas != NULL) echo $camas.' Camas '.'<br />'; ?></td>
        <td align="right" class="totales"><?php 
		if (isset($precio_cama)) {
			$sin_b = $precio_cama;
			echo number_format($sin_b, 2, ",", ".").' &euro;'; 
		}
		?></td>
      </tr>
      <?php } ?>
      <?php if($cunas != NULL){ ?> 
      <tr>
        <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;<?php if($cunas != NULL) echo $cunas.' Cunas '.' <br />'; ?></td>
        <td align="right" class="totales"><?php 
		if (isset($precio_cuna)) {
			$sin_c = $precio_cuna;
			echo number_format($sin_c, 2, ",", ".").' &euro;'; 
		}
		?></td>
      </tr>
<?php } ?>

<?php if($animales != NULL){ ?> 
      <tr>
        <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;<?php if($animales != NULL) echo 'Animales '.' <br />'; ?></td>
        <td align="right" class="totales"><?php 
		if (isset($animales)) {
			$animales_c = $animales;
			echo number_format($animales_c, 2, ",", ".").' &euro;'; 
		}
		?></td>
      </tr>
<?php } ?>

<?php if($p_extra != NULL){ ?> 
      <tr>
        <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;<?php if($extra != NULL) echo 'Extras:  '.$extra.'<br />'; ?></td>
        <td align="right" class="totales"><?php 
		if (isset($precio_extra)) {
			$sin_ex = $precio_extra;
			echo number_format($sin_ex, 2, ",", ".").' &euro;'; 
		}
		?></td>
      </tr>
<?php } ?>
<?php

if($descuento != NULL){
	//echo '<br />Descuento: '.$descuento;
	//$suma = $suma-$descuento;	
?>

      <tr>

        <td align="left"><strong>Descuento:</strong><br /><br /><br /><br /><br /><br /><br /></td>

        <td align="right" class="totales">- <?php echo $descuento; ?> &euro;<br /><br /><br /><br /><br /><br /><br /></td>

      </tr>

<?php
}
?>

    </table></td>

  </tr>

  <tr>

    <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="factura2">

      <tr>
        <td>&nbsp;</td>
        <td>Base imponible:</td>
        <td align="right" class="totales"><?php $base = $suma/1.10; echo number_format($base, 2, ",", "."); ?> &euro;</td>
      </tr>
      <tr>

        <td width="55%">&nbsp;</td>

        <td width="16%">I.V.A (10%)</td>

        <td width="29%" align="right" class="totales"><?php $iva= $suma - $base; echo number_format($iva, 2, ",", "."); ?> &euro;</td>

      </tr>

      <tr>

        <td>&nbsp;</td>

        <td><strong>TOTAL:</strong></td>

        <td align="right" class="totales"><?php $total = $suma; echo number_format($total, 2, ",", ".");?> &euro;</td>

      </tr>

    </table></td>

  </tr>

  <tr>

    <td colspan="3"><div class="txt-pq"><em>De conformidad con lo establecido en el Art. 12.2 del R.D. 1720/2007, de 21 de diciembre, por el que 
se aprueba el Reglamento de desarrollo de la Ley Orgánica 15/1999, 13 de diciembre, de protección
de datos de carácter personal, Ud. queda informado y consiente expresamente que los datos de 
carácter personal que proporciona al rellenar el presente formulario, serán incorporados a los
ficheros de Casas de Montaña "La Caraba" S.L., con domicilio Ctra del Canal, km2, 30440-Moratalla-Murcia,
para que éste pueda efectuar el tratamiento, automatizado o no, de los mismos con la firnalidad de
hospedaje rural, presentando su consentimiento expreso para que dichos datos puedan ser
comunicados para su utilización con los fines anteriores a otras Entidades. Así mismo, queda
informado que podrá ejercer los derechos de acceso, rectificación, cancelación y oposición dirigiéndose
a la dirección indicada anteriormente.</em></div></td>

  </tr>

</table>



</div>

</body>

</body>

</html>

<?php

mysqli_free_result($reserva_f);

?>


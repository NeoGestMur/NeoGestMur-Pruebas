<?php 
require_once('archivos/funcion.php');
require_once('../Connections/rural.php');
require_once('archivos/funciones.php');
require_once('archivos/funciones_frm.php');
require_once ('archivos/ceta_funciones.php');

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

$id = "-1";

if (isset($_GET['id'])) {

  $id = $_GET['id'];

}

mysqli_select_db($rural, $database_rural);

$query_reserva = sprintf("SELECT casa.nombre as casa, reserva.id_reserva, reserva.cliente_id as id_ref, clientes.nombre as cliente, reserva.adultos, reserva.nenes, reserva.bebes, reserva.entrada, reserva.salida, reserva.anticipo, reserva.anticipo_ob, reserva.observaciones, reserva.estado, reserva.precio, reserva.precio2, reserva.dias, reserva.personal_id, reserva.descuento, reserva.suplementos, reserva.p_suplemento, reserva.camas, reserva.p_cama, reserva.cunas, reserva.p_cuna, reserva.animales, reserva.n_f, reserva.fecha, reserva.extras, reserva.precio_extras, reserva.tipo_tarifa , reserva.origen FROM reserva, casa, clientes WHERE id_reserva = %s AND casa.id_cas=reserva.casa_id AND clientes.id_cliente=reserva.cliente_id", GetSQLValueString($id, "int"));

$reserva = mysqli_query($rural, $query_reserva) or die(mysqli_error());

$row_reserva = mysqli_fetch_assoc($reserva);

$totalRows_reserva = mysqli_num_rows($reserva);

?>


<html>

<head>

<meta charset="utf-8" />
<title>Panel de control: La Caraba</title>

<link href="css/estilos.css" rel="stylesheet" type="text/css" />
<link href="css/imprimir.css" rel="stylesheet" type="text/css" media="print"> 
</head>



<body>

<body>

<div id="contenido">

<div id="menu"><?php include('archivos/menu.html'); ?><div class="limpiar"></div></div>

<div class="caja_frm">

<h1> Ficha reserva</h1>

<table align="center">

    <tr valign="baseline">

      <td nowrap="nowrap" align="right">Atendido por:</td>

      <td>

<?php
mysqli_select_db($rural, $database_rural);
$query_personal = "SELECT * FROM personal WHERE id_personal = ".$row_reserva['personal_id'];
$personal = mysqli_query($rural, $query_personal) or die(mysql_error());
$row_personal = mysqli_fetch_assoc($personal);

echo $row_personal['nombre'].' '.$row_personal['apellidos'];

mysqli_free_result($personal);
?>      

      </td>

    </tr>
    <tr valign="baseline">

      <td nowrap="nowrap" align="right">Origen:</td>

      <td><input name="nombre" type="text" disabled="disabled" value="<?php echo agenciaByCode($row_reserva['origen']); ?>" size="45" readonly="readonly" /></td>

    </tr>
    <tr valign="baseline">

      <td nowrap="nowrap" align="right">Cliente:</td>

      <td><input name="nombre" type="text" disabled="disabled" value="<?php echo htmlentities($row_reserva['cliente'], ENT_COMPAT, 'iso-8859-1'); ?>" size="45" readonly="readonly" /></td>

    </tr>

    <tr valign="baseline">

      <td nowrap="nowrap" align="right">Casa:</td>

      <td><input name="casa" type="text" disabled="disabled" value="<?php echo htmlentities($row_reserva['casa'], ENT_COMPAT, 'iso-8859-1'); ?>" size="32" readonly="readonly" /></td>

    </tr>

    <tr valign="baseline">

      <td nowrap="nowrap" align="right">Adultos:</td>

      <td><input name="adultos" type="text" disabled="disabled" value="<?php echo htmlentities($row_reserva['adultos'], ENT_COMPAT, 'iso-8859-1'); ?>" size="32" readonly="readonly" /></td>

    </tr>

    <tr valign="baseline">

      <td nowrap="nowrap" align="right">Niños:</td>

      <td><input name="nenes" type="text" disabled="disabled" value="<?php echo htmlentities($row_reserva['nenes'], ENT_COMPAT, 'iso-8859-1'); ?>" size="32" readonly="readonly" /></td>

    </tr>

    <tr valign="baseline">

      <td nowrap="nowrap" align="right">Bebés:</td>

      <td><input name="bebes" type="text" disabled="disabled" value="<?php echo htmlentities($row_reserva['bebes'], ENT_COMPAT, 'iso-8859-1'); ?>" size="32" readonly="readonly" /></td>

    </tr>

    <tr valign="baseline">

      <td nowrap="nowrap" align="right">Fecha entrada:</td>

      <td><input name="entrada" type="text" disabled="disabled" id="entrada" value="<?php echo formato_es($row_reserva['entrada']); ?>" size="32" readonly="readonly" /></td>

    </tr>

    <tr valign="baseline">

      <td nowrap="nowrap" align="right">Fecha salida:</td>

      <td><input name="salida" type="text" disabled="disabled" id="salida" value="<?php echo formato_es($row_reserva['salida']); ?>" size="32" readonly="readonly" /></td>

    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Nº:</td>
      <td><input name="salida" type="text" disabled="disabled" id="salida" value="<?php $ini = formato_es($row_reserva['entrada']);
	  $fin = formato_es($row_reserva['salida']);
	  $dias_n = restaFechas($ini, $fin);
		echo $dias_n;
	   ?>" size="5" readonly="readonly" /></td>
    </tr>

    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Anticipo:</td>
      <td><input name="anticipo" type="text" disabled="disabled" value="<?php echo htmlentities($row_reserva['anticipo'], ENT_COMPAT, 'iso-8859-1'); ?>" size="32" readonly="readonly" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Anticipo Observ.:</td>
      <td><input name="anticipo" type="text" disabled="disabled" value="<?php echo htmlentities($row_reserva['anticipo_ob'], ENT_COMPAT, 'iso-8859-1'); ?>" size="32" readonly="readonly" /></td>
    </tr>

    <tr valign="baseline">

      <td nowrap="nowrap" align="right">Observaciones:</td>

      <td><textarea name="observaciones" cols="45" rows="5" disabled="disabled" readonly="readonly"><?php echo $row_reserva['observaciones'] ?></textarea></td>

    </tr>

    <tr valign="baseline">

      <td nowrap="nowrap" align="right">Estado:</td>

      <td>
      <?php
	  
		$estado = $row_reserva['estado'];
		if ( $estado == 0) echo 'Bloqueado';
		if ( $estado == 1) echo 'Reservado';
		if ( $estado == 2) echo 'Han llegado';
		if ( $estado == 3) echo 'Se han ido';
		
		
		?>      
      </td>

    </tr>

    <tr valign="baseline">
      <td colspan="2" align="center" nowrap="nowrap"><strong>Tarifa:
      <?php
	  $tipo = $row_reserva['tipo_tarifa'];
	  if ($tipo == 't1') {
		  echo 'temporada baja';
		  $precio = $row_reserva['precio'];
	  }
	  if ($tipo == 't2') {
		  echo 'temporada media';
		  $precio = $row_reserva['precio2'];
	  }
	  if ($tipo == 't3') {
		  echo 'temporada alta';
		  $precio = $row_reserva['precio2'];
	  }
	  ?>
      </strong></td>
      </tr>
<?php
	  //  total
	  	$dias =  restarfecha($row_reserva['entrada'],$row_reserva['salida']);
		$dias_festivos = $row_reserva['dias'];
		$dias_normales = $dias-$dias_festivos;
		
		$td = $dias_normales*$precio;
		$tf = $dias_festivos*$row_reserva['precio2'];
		$total_d = $td + $tf - $row_reserva['descuento'];
?>
    <tr valign="baseline">

      <td nowrap="nowrap" align="right">Precio día semana:</td>

      <td><input name="estado" type="text" disabled="disabled" value="<?php echo htmlentities($precio, ENT_COMPAT, 'iso-8859-1'); ?>" size="10" readonly="readonly" /> 
        (<?php echo $dias_normales.' x '.$precio.' = '.$td; ?> &euro;)</td>

    </tr>

    <tr valign="baseline">

      <td nowrap="nowrap" align="right">Precio día festivo:</td>

      <td><input name="estado" type="text" disabled="disabled" value="<?php echo htmlentities($row_reserva['precio2'], ENT_COMPAT, 'iso-8859-1'); ?>" size="10" readonly="readonly" />
      	<?php if ($dias_festivos != NULL){ ?>
      	(<?php echo $dias_festivos.' x '.$row_reserva['precio2'].' = '.$tf; ?> &euro; )
       <?php } ?>
      </td>

    </tr>

    <tr valign="baseline">

      <td nowrap="nowrap" align="right">Nº días festivos:</td>

      <td><input name="estado" type="text" disabled="disabled" value="<?php echo htmlentities($row_reserva['dias'], ENT_COMPAT, 'iso-8859-1'); ?>" size="4" readonly="readonly" /></td>

    </tr>

    <tr valign="baseline">

      <td nowrap="nowrap" align="right">Descuento:</td>

      <td><label>

        <input name="descuento" type="text" disabled="disabled" id="descuento" value="<?php echo htmlentities($row_reserva['descuento'], ENT_COMPAT, 'iso-8859-1'); ?>" size="10" readonly="readonly" />

      </label></td>

    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Total:</td>
      <td><?php echo $total_d; ?> &euro; 
      
      </td>
    </tr>
    <tr valign="baseline">
      <td colspan="2" align="center" nowrap="nowrap"><strong>Suplementos</strong></td>
      </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Sofá cama:</td>
      <td><label>
        <input name="sofa" type="text" disabled="disabled" id="sofa" value="<?php echo htmlentities($row_reserva['suplementos'], ENT_COMPAT, 'iso-8859-1'); ?>" size="5" readonly="readonly" />
      </label>  
	  <?php 
	  if ($row_reserva['suplementos'] != NULL){ 
	  		//echo 'p: '.$row_reserva['p_suplemento'];
			if ($row_reserva['p_suplemento']== 	NULL) $precio_s1 = 0; else $precio_s1 = $row_reserva['p_suplemento'];
	  ?>
      	(<?php echo $dias_n.' x ('.$row_reserva['suplementos'].' x '.$precio_s1.'))'; ?> = 
	  	<?php $s_s = $dias_n*($row_reserva['suplementos']*$precio_s1); 
	  	echo $s_s;?> &euro; <?php 
	}else{
		$s_s =0;
	}?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Cama supletoria:</td>
      <td><label>
        <input name="cama" type="text" disabled="disabled" id="cama" value="<?php echo htmlentities($row_reserva['camas'], ENT_COMPAT, 'iso-8859-1'); ?>" size="5" readonly="readonly" />
      </label>
      	<?php if ($row_reserva['camas'] != NULL){ 
			if ($row_reserva['p_cama']== 	NULL) $precio_s2 = 0; else $precio_s2 = $row_reserva['p_cama'];
		?>
      	(<?php echo $dias_n.' x ('.$row_reserva['camas'].' x '.$precio_s2.'))'; ?> = <?php 
		$s_c =  $dias_n*($row_reserva['camas']*$precio_s2); 
		echo $s_c; ?> &euro; 
        <?php }else{
			$s_c = 0;
		}?>
        </td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Cuna:</td>
      <td><label>
        <input name="cuna" type="text" disabled="disabled" id="cuna" value="<?php echo htmlentities($row_reserva['cunas'], ENT_COMPAT, 'iso-8859-1'); ?>" size="5" readonly="readonly" />
      </label>
      <?php if ($row_reserva['cunas'] != NULL){ 
	  	if ($row_reserva['p_cuna']== 	NULL) $precio_s3 = 0; else $precio_s3 = $row_reserva['p_cuna'];
	  ?>
      (<?php echo $dias_n.' x ('.$row_reserva['cunas'].' x '.$precio_s3.'))'; ?> = 
	  <?php 
	  $s_cu = $dias_n*($row_reserva['cunas']*$precio_s3); 
	  echo $s_cu;
	  ?>&euro;
      <?php }else{
		  $s_cu = 0;
	  }?>
      </td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Animales:</td>
      <td>
      <?php
	  if ($row_reserva['animales'] != NULL)
	  	$animales = $row_reserva['animales'];
	  else
	  	$animales = 0;
	  ?>
      <input name="cuna" type="text" disabled="disabled" id="cuna" value="<?php echo $animales; ?>" size="10" readonly="readonly" /> 
        &euro;</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Extras:</td>
      <td>
	  (Precio extras: <?php echo nl2br(htmlentities($row_reserva['precio_extras'], ENT_COMPAT, 'iso-8859-1')); ?> &euro;)<br />
	  <?php echo nl2br(htmlentities($row_reserva['extras'], ENT_COMPAT, 'iso-8859-1')); ?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Total con suplementos:</td>
      <td>
      <?php
	  //  total con suplementos
	  $total_s = $total_d + $s_s + $s_c + $s_cu +$row_reserva['precio_extras']+$animales;
	  echo $total_s;?> &euro; 
      </td>
    </tr>    <tr valign="baseline">
      <td nowrap="nowrap" align="right">N&ordm; de factura:</td>
      <td><input name="n_f" type="text" disabled="disabled" id="n_f" value="<?php echo htmlentities($row_reserva['n_f'], ENT_COMPAT, 'iso-8859-1'); ?>" size="15" readonly="readonly" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Fecha:</td>
      <td>&nbsp;<?php echo fecha_dia($row_reserva['fecha']).'/'.fecha_mes($row_reserva['fecha']).'/'.fecha_year($row_reserva['fecha']);?></td>
    </tr>

    <tr valign="baseline">

      <td colspan="2" align="center" nowrap="nowrap"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><div class="bton_nuevo"><a href="factura.php?id_reserva=<?php echo $id; ?>">Factura</a> </div>
      <div class="bton_nuevo"><a href="albaran.php?id_reserva=<?php echo $id; ?>">Albar&aacute;n</a></div>
      <div class="bton_nuevo"><a href="#" onclick="window.print();">Imprimir</a></div></td>
    <td><div class="bton_nuevo"><a href="reserva_editar.php?id_reserva=<?php echo $id; ?>">Modificar</a> </div>
      <div class="bton_nuevo"><a href="reserva_eliminar.php?id=<?php echo $id; ?>">Eliminar</a> </div></td>
  </tr>
</table>
  
      
      
      </td>

      </tr>

  </table>

</div></div>

</body>

</html>

<?php

mysqli_free_result($reserva);

?>
<?php
function calendario($idc,$database_rural,$rural,$fecha_inicio,$fecha_fin,$year,$mes,$dia,$enlace){
	require_once('funciones.php');
	mysql_select_db($database_rural, $rural);

	$sql_casa = "SELECT * FROM casa WHERE id_cas=".$idc;
	$casa_r = mysql_query($sql_casa, $rural) or die(mysql_error());
	$resultado_casa = mysql_fetch_assoc($casa_r);
	$nombre_casa = $resultado_casa['nombre'];
	
	?> <h2> <?php echo $nombre_casa; ?> </h2><?php
	
	$sql = "SELECT * FROM reserva WHERE casa_id=".$idc." AND entrada BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."' ORDER BY entrada ASC";
	$reservas = mysql_query($sql, $rural) or die(mysql_error());
	$cuantos = mysql_num_rows($reservas);
	
	$reservado = array();
	$n = 0;
	while($resultados = mysql_fetch_assoc($reservas)){
		$entrada =$resultados['entrada'];
		$salida = $resultados['salida'];
		$dias =  restarfecha($entrada,$salida);;
		$reservado[$n]['entrada'] = $entrada;
		$reservado[$n]['diaI'] = substr($entrada,8,2);
		$reservado[$n]['diaF'] = substr($salida,8,2);
		$reservado[$n]['mesI'] = substr($entrada,5,2);
		$reservado[$n]['mesF'] = substr($salida,5,2);
		$reservado[$n]['dias'] = $dias+1;
		$n++;
	}
	// fin datos calendario
	///////////////////////////////////////////////////////////////
	
	////////////////////////////////////////////////////
	// calendario
	$primer_dia= $year."/".$mes."/01";  //dia real
	//$primer_dia= "2011/4/01";
	$i = strtotime($primer_dia);
	$primer_n = jddayofweek(cal_to_jd(CAL_GREGORIAN, date("m",$i),date("d",$i), date("Y",$i)) , 0 );
	$numero_dias = cal_days_in_month(1,$mes,$year);
	//echo '<br />-'.$dia.'/'.$mes.'/'.$year.'-<br />';
	

$numero_dias = cal_days_in_month(1,$mes,$year);
?>
<div id="mes_e"><h1><a href="<?php echo $enlace; ?>?ida=a&id_casa=<?php echo $idc; ?>">&laquo;</a>&nbsp;&nbsp;<?php echo mes($mes).' ('.$year.')'; ?>&nbsp;&nbsp;<a href="<?php echo $enlace; ?>?ids=s&id_casa=<?php echo $idc; ?>">&raquo;</a></h1></div>

<?php
	
	if ($primer_n==0) $primer_n = 7; // domingo es el cero, lo cambiamos a 7
	$semanas =ceil(($numero_dias+$primer_n)/7);
	?>
	
	<table cellspacing="0">
	<thead>
					<tr>
						<th>L</th><th>M</th><th>M</th>
						<th>J</th><th>V</th><th>S</th>
						<th>D</th>
					</tr>
				</thead>
				<tbody>
					
	<?php  
	$codigo = '';
	switch ($primer_n){ 
		case 7: 
			$codigo = '<td class="padding" colspan="6"></td> ';
			$prime = 7;
			break; 
		case 1: 
			$codigo = '';
			$prime = 1;
			break;
		case 2: 
			$codigo = '<td class="padding"></td> ';
			$prime = 2;
			break;
		case 3: 
			$codigo = '<td class="padding" colspan="2"></td> ';
			$prime = 3;
			break;
		case 4: 
			$codigo = '<td class="padding" colspan="3"></td> ';
			$prime = 4;
			break;
		case 5: 
			$codigo = '<td class="padding" colspan="4"></td> ';
			$prime = 5;
			break;
		case 6: 
			$codigo = '<td class="padding" colspan="5"></td> ';
			$prime = 6;
			break;
	}
	?>
					
	<?php 
	
	// semanas
	$dia = 1;
	$x = 1;
	$marca_dias = 0;
	while ($x <= $semanas){
		?><tr><?php	
		$n = 1;
		while ($n <=7){ 
			if($n == 1 and $x == 1){		// para la primera semana
				echo $codigo;		// trozo de codigo si no se ajusta
				$n = $prime;		// celdas que debe de saltar
			}
			///////////////////////////////////////////////////
			// comprobar si ese dia esta reservado
					$dia_formato =sprintf("%02d", $dia); 
					$f_c = $dia_formato.'-'.$mes.'-'.$year;
					$f_c_i = $year.'-'.$mes.'-'.$dia_formato;
					
					for($k=0; $k<count($reservado); $k++){
						for($j=0; $j<$reservado[$k]['dias']; $j++){
							$f_dia_comparar = calcula_fecha(formato_es($reservado[$k]['entrada']),$j);
							// tengo que calcular el estado
							$sql_estado = "SELECT * FROM reserva WHERE entrada = '".$reservado[$k]['entrada']."'";
							$reserva_estado = mysql_query($sql_estado, $rural) or die(mysql_error());
							$resultado_estado = mysql_fetch_assoc($reserva_estado);	
							$total_estado = mysql_num_rows($reserva_estado);					
							
							if ($total_estado > 0)
								$como_esta = $resultado_estado['estado'];		// esto es para si hay una reserva partida en un mes
							else
								$como_esta = 4;
							//echo $resultado_estado['estado'];echo $total_estado;
							//****************************************************************
							if($f_c == $f_dia_comparar){
								$marca_dias = 1;
							}
						}
					}
					
					if($marca_dias == 1) {
						
						$marca_dias=0; 
						///////////////////////////////////////////////////////////
						// buscar reserva por entrada
						
						$entrada = $f_c_i;	// fecha de busqueda
			
						$sql_reserva = "SELECT  c.nombre as cliente, c.apellidos as apellido, c.telefono, ca.nombre as casa, r.estado FROM reserva r, clientes c, casa ca WHERE r.casa_id=".$idc." AND r.entrada = '".$entrada."' AND c.id_cliente=r.cliente_id AND ca.id_cas=r.casa_id";
						$reserva = mysql_query($sql_reserva, $rural) or die(mysql_error());
						$resultados = mysql_fetch_assoc($reserva);
						$total_reservas = mysql_num_rows($reserva);
						///////////////////////////////////////////////////////////
						// buscar reserva por salida
			
						$sql_reservas = "SELECT  c.nombre as cliente, c.apellidos as apellido, c.telefono, ca.nombre as casa, r.estado FROM reserva r, clientes c, casa ca WHERE r.casa_id=".$idc." AND r.salida = '".$entrada."' AND c.id_cliente=r.cliente_id AND ca.id_cas=r.casa_id";
						$reservas = mysql_query($sql_reservas, $rural) or die(mysql_error());
						$resultadoss = mysql_fetch_assoc($reservas);
						$total_reservass = mysql_num_rows($reservas);
						$hay = 0;
						$texto = '';
						//$como_esta = 4; // como se encuentra la casa?
										// bloqueado = 0
										// reservado = 1
										// ha llegado = 2
										// se han ido = 3
										// normal el 4
						$activar = 0;
						
						
						if ($total_reservass>0){  // salidas
								$estado1 = $resultadoss['estado'];
								$como_esta = $estado1;
								$texto .= '
										<li>
											<span class="title">Salida: '.$resultadoss['cliente'].' '.$resultadoss['apellido'].'</span>
											<span class="desc">Tel&eacute;fono: '.$resultadoss['telefono'].'</span>
										</li>';               
							mysql_free_result($reservas);
							$hay = 1;
							$activar = 0;
						}
						if ($total_reservas>0){		// entradas		
								$estado2 = $resultados['estado'];
								$como_esta = $estado2;					
								$texto .= '
									<li>
										<span class="title">Entrada: '.$resultados['cliente'].' '.$resultados['apellido'].'</span>
										<span class="desc">Tel&eacute;fono: '.$resultados['telefono'].'</span>
									</li>';						
							mysql_free_result($reserva);
							$hay = 1;
							$activar = 1;
						}
						
													//$clase = '';
							if ($como_esta == 4 and $activar ==1)
								$clase = 'color';	
							if ($como_esta == 0)
								$clase = 'bloqueado';
							if ($como_esta == 1)
								$clase = 'reservado';
							if ($como_esta == 2)
								$clase = 'estan';
							if ($como_esta == 3)
								$clase = 'se_van';
								
						if ($hay==1){
							echo '<td class="'.$clase.' hay_evento">'.$dia;
							?>
							<div class="events"><ul>
                            <?php echo $texto; ?>
                            </ul></div>                  
							<?php
							echo '</td>'; 
						}else{
							echo '<td class="'.$clase.'">'.$dia; 
						
							echo '</td>';
						}
					}else{
						echo '<td>'.$dia.'</td>'; 
					}			
					
			//fin comprobacion reservado
			//////////////////////////////////////////////////
			$colocar = 0;
			if($dia >= $numero_dias){
				$colocar = 7-$n;
				break;
			}
			$n++;$dia++;
			
		}
		if ($colocar != 0) echo '<td class="padding" colspan="'.$colocar.'"></td> ';
		$x++;
		?></tr><?php
	}?>                                                       
				</tbody>
				<tfoot>
					<th>L</th><th>M</th><th>M</th>
					<th>J</th><th>V</th><th>S</th>
					<th>D</th>
				</tfoot>
			</table>           
	<?php
	// fin calendario-Pruebas
	//////////////////////////////////////////////////////////////
}
?>
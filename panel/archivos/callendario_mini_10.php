<?php
function calendario($idc,$database_rural,$rural,$fecha_inicio,$fecha_fin,$year,$mes,$dia,$enlace){
	require_once('funciones.php');
	mysql_select_db($database_rural, $rural);
	unset($reservado);
	
	$sql = "SELECT * FROM reserva WHERE casa_id=".$idc." AND entrada BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."' ORDER BY entrada ASC";
	$reservas = mysql_query($sql, $rural) or die(mysql_error());
	$cuantos = mysql_num_rows($reservas);
	
	$reservado = array();  // array con las fechas de ese mes
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
		//echo 'e:'.$entrada.'-s:'.$salida.'-d:'.$dias;
	}
	//echo count($reservado);
	// fin datos calendario
	///////////////////////////////////////////////////////////////
	
	////////////////////////////////////////////////////
	// calendario
	$primer_dia= $year."/".$mes."/01";  //dia real
	//$primer_dia= "2011/4/01";
	$i = strtotime($primer_dia);
	$primer_n = jddayofweek(cal_to_jd(CAL_GREGORIAN, date("m",$i),date("d",$i), date("Y",$i)) , 0 );
	$numero_dias = cal_days_in_month(1,$mes,$year);
	
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
	while ($x <= $semanas){  // semana
		?><tr><?php	
		$n = 1;
		while ($n <=7){ 	// dias de la semana
			if($n == 1 and $x == 1){		// para la primera semana
				echo $codigo;		// trozo de codigo si no se ajusta
				$n = $prime;		// celdas que debe de saltar
			}
			///////////////////////////////////////////////////
			// comprobar si ese dia esta reservado
					$dia_formato =sprintf("%02d", $dia); 
					//echo $dia;
					$f_c = $dia_formato.'-'.$mes.'-'.$year;
					//echo $f_c.'<br />';
					$f_c_i = $year.'-'.$mes.'-'.$dia_formato;
					 
					
					for($k=0; $k<count($reservado); $k++){    // contar las reservas
						for($j=0; $j<$reservado[$k]['dias']; $j++){
							$f_dia_comparar = calcula_fecha(formato_es($reservado[$k]['entrada']),$j);
							//echo $reservado[$k]['entrada'];
							
							// tengo que calcular el estado
							$sql_estado = "SELECT * FROM reserva WHERE entrada = '".$reservado[$k]['entrada']."'";
							$reserva_estado = mysql_query($sql_estado, $rural) or die(mysql_error());
							$resultado_estado = mysql_fetch_assoc($reserva_estado);	
							$total_estado = mysql_num_rows($reserva_estado);					
							
							if ($total_estado > 0){
								$como_esta = $resultado_estado['estado'];		// esto es para si hay una reserva partida en un mes
								//echo 'pasa'.$resultado_estado['id_reserva'];
							}else{
								$como_esta = 4;
							}
							
							//echo $resultado_estado['estado'];echo $total_estado;
							//****************************************************************
							if($f_c == $f_dia_comparar){
								$marca_dias = 1; 
								
								if ($como_esta == 4 )
									$clase = 'color';	
								if ($como_esta == 0)
									$clase = 'bloqueado';
								if ($como_esta == 1)
									$clase = 'reservado';
								if ($como_esta == 2)
									$clase = 'estan';
								if ($como_esta == 3)
									$clase = 'se_van';
								
								echo '<td class="'.$clase.' hay_evento">';
								echo $dia;
								break;
							}
						}
					}
					if ($marca_dias != 1){
						echo '<td>';
						echo $dia;
					}
					
					echo '</td>';
					$marca_dias =0;

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
	unset($reservado);
	// fin calendario-Pruebas
	//////////////////////////////////////////////////////////////
}
?>
<?php
function calendario ($year,$mes,$finDeSemana=1,$nivelH=2, $idc, $database_rural,$rural) {
	
	//////////////////////////////////////////////////
	//***********************************************
	$html = '';
	require_once('funciones.php');
	mysqli_select_db($rural, $database_rural);
	
	$sql_casa = "SELECT * FROM casa WHERE id_cas=".$idc;
	$casa_r = mysqli_query($rural, $sql_casa) or die(mysql_error());
	$resultado_casa = mysqli_fetch_assoc($casa_r);
	$nombre_casa = $resultado_casa['nombre'];
	
	?> <h2> <?php echo $nombre_casa; ?> </h2><?php
	
	$fechainicio =date("Y-m-d", gmmktime(0,0,0,($mes-1),1,$year));
	$fechafin = date("Y-m-d", gmmktime(0,0,0,($mes+1),1,$year));
	//$html .= 'fecha inicio: '.$fechainicio.' fecha fin: '.$fechafin.'<br />';
	
	$sql = "SELECT * FROM reserva WHERE casa_id=".$idc." AND entrada BETWEEN '".$fechainicio."' AND '".$fechafin."' ORDER BY entrada ASC";
	$reservas = mysqli_query($rural, $sql) or die(mysql_error());
	$cuantos = mysqli_num_rows($reservas);
	
	$reservado = array();
	$n = 0;
	while($resultados = mysqli_fetch_assoc($reservas)){
		$entrada =$resultados['entrada'];
		//$html .= $entrada.' / ';
		$salida = $resultados['salida'];
		//$html .= $salida.'<br />';
		$dias =  restarfecha($entrada,$salida);
		$reservado[$n]['entrada'] = $entrada;
		$reservado[$n]['salida'] = $salida;
		$reservado[$n]['diaI'] = substr($entrada,8,2);
		$reservado[$n]['diaF'] = substr($salida,8,2);
		$reservado[$n]['mesI'] = substr($entrada,5,2);
		$reservado[$n]['mesF'] = substr($salida,5,2);
		$reservado[$n]['dias'] = $dias+1;
		$n++;
	}	
	
	
	//////////////////////////////////////////////////
	//***********************************************
	
	
	if (strlen($year)!=4) {$year=date('Y');}
	if (($mes<1 or $mes>12) or (strlen($mes)<1 or strlen($mes)>2)) {$year=date('n');}
	
	// Listados: días de la semana, letra inicial de los días de la semana, y meses
	$dias = array('Lunes','Martes','Mi&eacute;rcoles','Jueves','Viernes','S&aacute;bado','Domingo');
	$diasAbbr = array('L','M','M','J','V','S','D');
	$meses = array('ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO','AGOSTO','SEPTIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE');
	
	// Se sacan valores que se utilizarán más adelante
	$diaInicial = gmmktime(0,0,0,$mes,1,$year);  // Primer día del mes dado
	$diasNulos = (date("N",$diaInicial))-1; // Con 'N' la semana empieza en Lunes. Con 'w', en domingo
		if($diasNulos<0){$diasNulos = 7-abs($diasNulos);}
	$diasEnMes = date("t",$diaInicial); // Número de días del mes dado
	
	// Se abre la capa contenedora y se genera el encabezado del bloque de calendario
	$html .= '<div id="calendario">';
	//$html .= '<h'.$nivelH.' class="encabezadoCalendario">Calendario</h'.$nivelH.'>';
	
		// Párrafos con la fecha actual y la fecha seleccionada
	//$html .= '<p>Fecha actual: '.date('j').' de '.$meses[(intval(date('n'))-1)].' de '.date('Y').'</p>';
	//$html .= '<p>';
	//if (isset($_GET['dia'])) {$html .= ''.$_GET['dia'].' de ';} // El día solo sale si se ha definido previamente en el parámetro 'dia' de la URL
	//$html .= ''.$meses[($mes-1)].' de '.$year.'</p>';
		$html .= '<div class="tabla">';

		// Enlaces al mes anterior y al siguiente
		//$html .= '<p>Navegaci&oacute;n por meses:</p>';
		//$html .= '<ul id="calNavMeses">';
		$enlaceAnterior1 = gmmktime(0,0,0,($mes-1),1,$year);
		$mesAnterior = date('n',$enlaceAnterior1);
		$yearMesAnterior = date('Y',$enlaceAnterior1);
		$enlaceSiguiente1 = gmmktime(0,0,0,($mes+1),1,$year);
		$mesSiguiente = date('n',$enlaceSiguiente1);
		$yearMesSiguiente = date('Y',$enlaceSiguiente1);
		//$html .= '<li class="anterior"></li>';
		//$html .= '<li class="siguiente"></li>';
		//$html .= '</ul>';

		// Enlaces al año anterior y al siguiente
		//$html .= '<p>Navegaci&oacute;n por a&ntilde;os:</p>';
		//$html .= '<ul id="calNavYears">';
		$enlaceAnterior2 = gmmktime(0,0,0,$mes,1,($year-1));
		$yearAnterior = date('Y',$enlaceAnterior2);
		$enlaceSiguiente2 = gmmktime(0,0,0,$mes,1,($year+1));
		$yearSiguiente = date('Y',$enlaceSiguiente2);
		//$html .= '<li class="anterior"></li>';
		//$html .= '<li class="siguiente"></li>';
		//$html .= '</ul>';
	
		// Se abre la tabla que contiene el calendario
		$html .= '<table>';
		
			// Título mes-año (elemento CAPTION)
			$mesLista = $mes-1;
			$html .= '<caption>';
			$html .= '<a href="?mes='.$mes.'&amp;ano='.$yearAnterior.'&amp;id_casa='.$idc.'"><span>&laquo;</span></a> &nbsp;&nbsp;';
			$html .= '<a href="?mes='.$mesAnterior.'&amp;ano='.$yearMesAnterior.'&amp;id_casa='.$idc.'"><span>&#8249;</span></a>';
			$html .= '&nbsp;'.$meses[$mesLista].'<span> de</span> '.$year.'&nbsp;';
			$html .= '<a href="?mes='.$mesSiguiente.'&amp;ano='.$yearMesSiguiente.'&amp;id_casa='.$idc.'"><span>&#8250;</span></a> &nbsp;&nbsp;';
			$html .= '<a href="?mes='.$mes.'&amp;ano='.$yearSiguiente.'&amp;id_casa='.$idc.'"><span>&raquo;</span></a>';
			$html .= '</caption>';
	
			// Se definen anchuras en elementos COL
			$cl=0; $anchoCol=100/7; while ($cl<7) {$html .= '<col width="'.$anchoCol.'%" />'; $cl++;}
			
			// Fila de los días de la semana (elemento THEAD)
			$html .= '<thead><tr>';$d=0;
			while ($d<7) {$html .= '<th scope="col" abbr="'.$dias[$d].'">'.$diasAbbr[$d].'</th>';$d++;}
			$html .= '</tr></thead>';
		
			// Se generan los días nulos (días del mes anterior o posterior) iniciales, el TBODY y su primer TR
			$html .= '<tbody>';
			if ($diasNulos>0) {$html .= '<tr>';} // Se abre el TR solo si hay días nulos
			if ($diasNulos>0) {$html .= '<td class="padding" colspan="'.$diasNulos.'"></td>';} // Se hace un TD en blanco con el ancho según los día nulos que haya
						
			// Se generan los TD con los días del mes
			$dm=1;$x=0;$ds=$diasNulos+1;
			while ($dm<=$diasEnMes) {
				if(($x+$diasNulos)%7==0 and $x!=0) {$html .= '</tr>';} // Se evita el cierre del TR si no hay días nulos iniciales
				if(($x+$diasNulos)%7==0) {$html .= '<tr>';$ds=1;}
				$enSegundosCalendario = gmmktime(0,0,0,$mes,$dm,$year); // Fecha del día generado en segundos
				$enSegundosActual = gmmktime(0,0,0,date('n'),date('j'),date('Y')); // Fecha actual en segundos
				//$enSegundosSeleccionada = gmmktime(0,0,0,$_GET['mes'],$_GET['dia'],$_GET['ano']); // Fecha seleccionada, en segundos
				$idFecha = 'cal-'.date('Y-m-d',$enSegundosCalendario);				
							
				// Clases y etiquetado general para los días, para día actual y para día seleccionado
				$claseActual='';$tagDia='span';
				
				// comprobar dia a dia si hay una entrada //////////////////////////////////
				$clase = '';
				$f_c_i = $year.'-'.$mes.'-'.$dm;
				$sql_estado = "SELECT  c.nombre as cliente, c.apellidos as apellido, c.telefono, ca.nombre as casa, r.estado FROM reserva r, clientes c, casa ca WHERE r.casa_id=".$idc." AND r.entrada = '".$f_c_i."' AND c.id_cliente=r.cliente_id AND ca.id_cas=".$idc;
				$reserva_estado = mysqli_query($rural, $sql_estado) or die(mysql_error());
				$resultado_estado = mysqli_fetch_assoc($reserva_estado);	
				$total_entrada = mysqli_num_rows($reserva_estado);
				
				$texto = '';
				if ($total_entrada > 0){
					$como_esta = $resultado_estado['estado'];
					$texto = '
							<li>
								<span class="title">Entrada: '.$resultado_estado['cliente'].' '.$resultado_estado['apellido'].'</span>
								<span class="desc">Tel&eacute;fono: '.$resultado_estado['telefono'].'</span>
							</li>
							';
				}else{
						$como_esta = 5;
				}
				// comprobar dia a dia si hay una salida /////////////////////////////////////////
				$sql_salida = "SELECT  c.nombre as cliente, c.apellidos as apellido, c.telefono, ca.nombre as casa, r.estado FROM reserva r, clientes c, casa ca WHERE r.casa_id=".$idc." AND r.salida = '".$f_c_i."' AND c.id_cliente=r.cliente_id AND ca.id_cas=".$idc;
				$reserva_salida = mysqli_query($rural, $sql_salida) or die(mysql_error());
				$resultado_salida = mysqli_fetch_assoc($reserva_salida);	
				$total_salida = mysqli_num_rows($reserva_salida);
				
				if ($total_salida > 0){
						$texto .= '
							<li>
								<span class="title">Salida: '.$resultado_salida['cliente'].' '.$resultado_salida['apellido'].'</span>
								<span class="desc">Tel&eacute;fono: '.$resultado_salida['telefono'].'</span>
							</li>
							';	
				}
				
				// comprobar dia a dia si esta entre una entrada y una salida
				$sql_entre = "SELECT * FROM reserva WHERE casa_id=".$idc." AND '".$f_c_i."' BETWEEN entrada AND salida";
				$reserva_entre = mysqli_query($rural, $sql_entre) or die(mysql_error());
				$resultado_entre = mysqli_fetch_assoc($reserva_entre);	
				$total_entre = mysqli_num_rows($reserva_entre);
					
				$como_esta_e = $resultado_entre['estado'];
				
				//  CLASES
				$sale = '';
				if ($total_salida>0 and $como_esta != 5){
					$sale = 'sale';
				}
				$entre = '';
				if ($total_entre>0){
					$entre = '-en'.$como_esta_e;
					$como_esta = $como_esta_e;
				}
				
				if ($como_esta == 4) $clase = 'color';	
				if ($como_esta == 0) $clase = 'bloqueado';
				if ($como_esta == 1) $clase = 'reservado';
				if ($como_esta == 2) $clase = 'estan';
				if ($como_esta == 3) $clase = 'se_van';
				if ($como_esta == 5) $clase = '';	
				
				if ($total_entrada>0 and $total_salida>0){
					$clase = 'coincide';	
				}										
		
				// Con las variables ya definidas, se crea el HTML del TD
				$html .= '<td id="'.$idFecha.'" class="'.$clase;
				if (!empty($texto)) $html .= ' hay_evento';
				$html .='">'.$dm;
				if (!empty($texto)) $html .= '<div class="events"><ul>'.$texto.'</ul></div>';
				$html .='</td>';
				
				$dm++;$x++;$ds++;
			}
			
			// Se generan los días nulos finales
			$diasNulosFinales = 0;
			while((($diasEnMes+$diasNulos)%7)!=0){$diasEnMes++;$diasNulosFinales++;}
			if ($diasNulosFinales>0 ) {$html .= '<td class="padding" colspan="'.$diasNulosFinales.'"></td>';} // Se hace un TD en blanco con el ancho según los día nulos que haya (si no se activa mostrar los días nulos)
			
	
	// Se cierra el último TR y el TBODY
	$html .= '</tr></tbody>';	
		
		$html .= '</table>';  // fin de la tabla
	
		$html .= '</div>';	// fin .tabla
	
	
	$html .= '</div>';  // fin #calendario
	
	return $html;	
}
?>
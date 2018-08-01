<?php
require_once('../Connections/rural.php'); 

/////////////////////////////////////////////////////////////////////////////
// DIAS RESERVADOS
//********************************************************************
// dias reservada
$idc = -1;
if (isset($_POST['idc'])) $idc = $_POST['idc'];
mysql_select_db($database_rural, $rural);
$sql = "SELECT * FROM reserva WHERE casa_id = ".$idc."  ORDER BY entrada ASC";
$respueta = mysql_query($sql, $rural) or die(mysql_error());

//$fechas = mysql_fetch_assoc($respueta);

$total = mysql_num_rows($respueta);

$quitar = array();
$quitar_f  ='';
$n = 0;

//---------------------------------
function sumaDia($fecha,$dia){	list($year,$mon,$day) = explode('-',$fecha);
	return date('Y-m-d',mktime(0,0,0,$mon,$day+$dia,$year));		
}
//---------------------------------

// FECHA ANTERIOR
$fecha_antes = array();
$w = 0;

while($fechas = mysql_fetch_assoc($respueta)){
	$fecha_antes[$w] = $fechas['salida']; 
	
	//echo 'e: '.$fechas['entrada'].' s: '.$fechas['salida'].' / <br />';
	if (isset($fecha_antes[$w-1])) {
		$fecha_anterior =$fecha_antes[$w-1]; 
		//echo $fecha_anterior .'<br/>';
	}else {
		$fecha_anterior='';
	}
	$w++;
	if ( $fecha_anterior == $fechas['entrada']) {
		//echo 'si';
		$fech_in = ($fechas['entrada']);
		$fech_in2 = strtotime($fech_in);
	}else{
		$fechaInicio = ($fechas['entrada']);
		$fech_in = sumaDia($fechaInicio,1);
		$fech_in2 = strtotime($fech_in);
	}
	// rango 

	
	$fechaFin = strtotime($fechas['salida']);

	
	for($i=$fech_in2; $i<$fechaFin; $i+=86400){
		$quitar[$n]= date("n-j-Y", $i);
		$n++;
	}
	
}

$cuantos =  count($quitar);
for($n=0; $n< count($quitar); $n++ ){
	if($n != $cuantos-1)
		$quitar_f.= '"'.$quitar[$n].'",';
	else
		$quitar_f.= '"'.$quitar[$n].'"';
}
//echo $quitar_f;
/////////////////////////////////////////////////////
//FIN DIAS RESERVADOS
////////////////////////////////////////////////////
?>
	<script>
	$(function() {
		var disabledDays = <?php echo '['.$quitar_f.']'; ?>;
		//var disabledDays = ["5-18-2012","5-19-2012"];
		function nationalDays(date) {
		  var m = date.getMonth(), d = date.getDate(), y = date.getFullYear();
		  //console.log('Checking (raw): ' + m + '-' + d + '-' + y);
		  for (i = 0; i < disabledDays.length; i++) {
			//if($.inArray((m+1) + '-' + d + '-' + y,disabledDays) != -1 || new Date() > date) {
			if($.inArray((m+1) + '-' + d + '-' + y,disabledDays) != -1) {
			  //console.log('bad:  ' + (m+1) + '-' + d + '-' + y + ' / ' + disabledDays[i]);
			  return [false];
			}
		  }
		  //console.log('good:  ' + (m+1) + '-' + d + '-' + y);
		  return [true];
		}

		function noWeekendsOrHolidays(date) {
		 // var noWeekend = jQuery.datepicker.noWeekends(date);
		  return nationalDays(date);
		}

		var dates = $( "#entrada, #salida" ).datepicker({
			firstDay: 1,
			monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
			monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
			dayNames: ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'],
			dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
			changeMonth: true,
			numberOfMonths: 2,
			dateFormat: 'dd-mm-yy',
			 beforeShowDay: noWeekendsOrHolidays,
			onSelect: function( selectedDate ) {
				var option = this.id == "entrada" ? "minDate" : "maxDate",
					instance = $( this ).data( "datepicker" ),
					date = $.datepicker.parseDate(
						instance.settings.dateFormat ||
						$.datepicker._defaults.dateFormat,
						selectedDate, instance.settings );
				dates.not( this ).datepicker( "option", option, date );
				//alert ('hola');
			}
		});
	});
	</script>

     <table width="100%" border="0">
            <tr valign="baseline">
      <td nowrap="nowrap" align="right">Fecha entrada:</td>
      <td><input type="text" name="entrada" id="entrada" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Fecha salida:</td>
      <td><input type="text" name="salida" id="salida" value="" size="32" /></td>
    </tr>
      </table>
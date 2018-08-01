<?php
require_once('archivos/funcion.php');
require_once('archivos/sesion.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin título</title>
<link rel='stylesheet' type='text/css' href='fullcalendar/fullcalendar.css' />
<link rel='stylesheet' type='text/css' href='fullcalendar/fullcalendar.print.css' media='print' />
<script type='text/javascript' src='js/jquery-1.6.2.min.js'></script>
<script type='text/javascript' src='js/jquery-ui-1.8.16.custom.min.js'></script>
<script type='text/javascript' src='fullcalendar/fullcalendar.min.js'></script>
<script type='text/javascript'>

	$(document).ready(function() {
	
		var date = new Date();
		var d = date.getDate();
		var m = date.getMonth();
		var y = date.getFullYear();
		
		$('#calendar').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,basicWeek,basicDay'
			},
			dayClick: function(date, allDay) {
        		if (allDay) {
            		alert('Clicked on the entire day: ' + date);
        		}else{
           			alert('Clicked on the slot: ' + date);
       			}
    		},
			editable: true,
			events: [
<?php
require_once('../Connections/rural.php');
$id = $_GET['id_casa'];
mysql_select_db($database_rural, $rural);
$sql = "SELECT * FROM reserva WHERE casa_id=".$id." ORDER BY entrada ASC";
$reservas = mysql_query($sql, $rural) or die(mysql_error());
$cuantos = mysql_num_rows($reservas);

while($resultados = mysql_fetch_assoc($reservas)){
	$de = substr($resultados['entrada'],8,2);
	$me = substr($resultados['entrada'],5,2);
	$ae = substr($resultados['entrada'],0,4);
	$df = substr($resultados['salida'],8,2);
	$mf = substr($resultados['salida'],5,2);
	$af = substr($resultados['salida'],0,4);
	echo '{';
	echo "title: 'Reserva',";
	echo 'start: new Date('.$ae.','.$me.','.$de.'),';
	echo 'end: new Date('.$af.','.$mf.','.$df.'),';
	echo '},';
}
?>{title: 'Reserva',start: new Date(2011,11,02),end: new Date(2011,11,06)},
{title: 'Long Event',start: new Date(2011, 11, 2),end: new Date(2011, 11,6)}
			]
		});
		
	});

</script>
<style type='text/css'>

	body {
		margin-top: 40px;
		text-align: center;
		font-size: 14px;
		font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
		}

	#calendar {
		width: 900px;
		margin: 0 auto;
		}

</style>
</head>

<body>
<?php include('archivos/menu.html'); ?>
<div id='calendar'></div>
</body>
</html>
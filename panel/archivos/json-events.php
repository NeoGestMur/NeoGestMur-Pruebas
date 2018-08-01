<?php
require_once('../../Connections/rural.php');
mysql_select_db($database_rural, $rural);
$sql = "SELECT * FROM reserva ORDER BY entrada ASC";
$reservas = mysql_query($sql, $rural) or die(mysql_error());
$cuantos = mysql_num_rows($reservas);

if($cuantos > 0){
	echo json_encode(array(
		while($resultados = mysql_fetch_assoc($reservas)){
			array(
				'id' => 111,
				'title' => "Event1",
				'start' => "$year-$month-10"
			),
		}	
	));
}

?>

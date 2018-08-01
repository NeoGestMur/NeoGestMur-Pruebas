<?php
///////////////////////////////////////////
// fechas
function formato_in($fecha){
	$dia= substr($fecha,0,2);
	$mes= substr($fecha,3,2);
	$year= substr($fecha,6,4);
	$fecha_in = $year.'/'.$mes.'/'.$dia;
	return($fecha_in);
}
function formato_es($fecha){
	$year= substr($fecha,0,4);
	$mes= substr($fecha,5,2);
	$dia= substr($fecha,8,2);
	$fecha_es = $dia.'/'.$mes.'/'.$year;
	return($fecha_es);
}
/***************************************************/

function restarfecha ($fechaIni, $fechaFin) { 
	list($year, $month, $day) = explode('-', $fechaIni); 
	$fechaIni = mktime(0, 0, 0, $month, $day, $year); 
	list($year, $month, $day) = explode('-', $fechaFin);
	$fechaFin = mktime(0, 0, 0, $month, $day, $year); 
	$dias = ($fechaFin - $fechaIni)/(60 * 60 * 24) ; 
	return round($dias); 
}

function restaFechas($dFecIni, $dFecFin){
	// Fecha en formato dd/mm/yyyy o dd-mm-yyyy retorna la diferencia en dias
	
    $dFecIni = str_replace("-","",$dFecIni);
    $dFecIni = str_replace("/","",$dFecIni);
    $dFecFin = str_replace("-","",$dFecFin);
    $dFecFin = str_replace("/","",$dFecFin);

	/*  erg ya no es valido  */
    // ereg( "([0-9]{1,2})([0-9]{1,2})([0-9]{2,4})", $dFecIni, $aFecIni);
    // ereg( "([0-9]{1,2})([0-9]{1,2})([0-9]{2,4})", $dFecFin, $aFecFin);
	/*  preg_match('/'.$patron.'/', $cadena_texto);  */
	preg_match( "#([0-9]{1,2})([0-9]{1,2})([0-9]{2,4})#", $dFecIni, $aFecIni);
    preg_match( "#([0-9]{1,2})([0-9]{1,2})([0-9]{2,4})#", $dFecFin, $aFecFin);
	

    $date1 = mktime(0,0,0,$aFecIni[2], $aFecIni[1], $aFecIni[3]);
    $date2 = mktime(0,0,0,$aFecFin[2], $aFecFin[1], $aFecFin[3]);

    return round(($date2 - $date1) / (60 * 60 * 24));
}
/**********************************************************/
function calcula_fecha($f, $d){
      if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$f))
            
              list($dia,$mes,$año)=split("/", $f);
            
      if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$f))
            
              list($dia,$mes,$año)=split("-",$f);
        $nueva = mktime(0,0,0, $mes,$dia,$año) + $d * 24 * 60 * 60;
        $nuevafecha=date("d-m-Y",$nueva);
            
      return ($nuevafecha);
}
function mes($mes){
	switch($mes) { 
      	 case "01": 
         	 $mes="Enero"; 
         	 break; 
      	 case "02": 
         	 $mes="Febrero"; 
         	 break; 
      	 case "03": 
         	 $mes="Marzo"; 
         	 break; 
      	 case "04": 
         	 $mes="Abril"; 
         	 break; 
      	 case "05": 
         	 $mes="Mayo"; 
         	 break; 
      	 case "06": 
         	 $mes="Junio"; 
         	 break; 
      	 case "07": 
         	 $mes="Julio"; 
         	 break; 
      	 case "08": 
         	 $mes="Agosto"; 
         	 break; 
      	 case "09": 
         	 $mes="Septiembre"; 
         	 break; 
      	 case "10": 
         	 $mes="Octubre"; 
         	 break; 
      	 case "11": 
         	 $mes="Noviembre"; 
         	 break; 
      	 case "12": 
         	 $mes="Diciembre"; 
         	 break; 
	}
	return($mes);
}

// fin fechas
////////////////////////////////////////
?>
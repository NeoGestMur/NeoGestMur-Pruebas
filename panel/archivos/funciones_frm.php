<?php
///////////////////////////////////////////////////
// comprobacion de datos
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") {
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }


  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

/////////////////////////////////////////////////
// provincias
$provincias = array(
		'2' =>'&Aacute;lava',
		'3' =>'Albacete',
		'4' =>'Alicante',
		'5' =>'Almer&iacute;a', 
		'6' =>'Asturias',
		'7' =>'&Aacute;vila',
		'8' =>'Badajoz',
		'9' =>'Barcelona',
		'10' =>'Burgos',
		'11' =>'C&aacute;ceres',
		'12' =>'C&Aacute;diz',
		'13' =>'Cantabria',
		'14' =>'Castell&oacute;n',
		'15' =>'Ceuta',
		'16' =>'Ciudad Real',
		'17' =>'C&oacute;rdoba',
		'18' =>'Cuenca',
		'19' =>'Girona',
		'20' =>'Las Palmas',
		'21' =>'Granada',
		'22' =>'Guadalajara',
		'23' =>'Guip&uacute;zcoa',
		'24' =>'Huelva',
		'25' =>'Huesca',
		'26' =>'Illes Balears',
		'27' =>'Ja&eacute;n',
		'28' =>'A Coru&ntilde;a',
		'29' =>'La Rioja',
		'30' =>'Le&oacute;n',
		'31' =>'Lerida',
		'32' =>'Lugo',
		'33' =>'Madrid',
		'34' =>'M&Aacute;laga',
		'35' =>'Melilla',
		'36' =>'Murcia',
		'37' =>'Navarra',
		'38' =>'Ourense',
		'39' =>'Palencia',
		'40' =>'Pontevedra',
		'41' =>'Salamanca',
		'42' =>'Segovia',
		'43' =>'Sevilla',
		'44' =>'Soria',
		'45' =>'Tarragona',
		'46' =>'Santa Cruz de Tenerife',
		'47' =>'Teruel',
		'48' =>'Toledo',
		'49' =>'Valencia',
		'50' =>'Valladolid',
		'51' =>'Vizcaya',
		'52' =>'Zamora',
		'53' =>'Zaragoza');
?>
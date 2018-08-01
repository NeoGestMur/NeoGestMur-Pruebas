<?php
require_once('archivos/funcion.php');
require_once('../Connections/rural.php');
require_once('archivos/callendario_mini.php');
require_once('archivos/funciones.php');

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
$cambio = 0;
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
	/////////////////////////////////////
	//		comprobación
	mysqli_select_db($rural, $database_rural);
	$query_usuario = "SELECT * FROM administracion WHERE id = 1";
	$usuario = mysqli_query($rural, $query_usuario) or die(mysql_error());
	$row_usuario = mysqli_fetch_assoc($usuario);
	$totalRows_usuario = mysqli_num_rows($usuario);
	$usuario_viejo = $row_usuario['contra'];
	
	$contra_vieja = trim($_POST['contra_vieja']);	
		if($contra_vieja != $usuario_viejo){
			$error['baja'] = 'La contraseña no coincide';
		}
	$contra_nueva = trim($_POST['contra_nueva']);	
		if(empty($contra_nueva)){
			$error['nueva'] = 'Este campo no puede dejarse vacío';
		}
	
	
	//		fin
	////////////////////////////////////////////////////////////
	if (!isset($error)) {
		$cambio = 1;
  $updateSQL = sprintf("UPDATE administracion SET contra=%s WHERE id=%s",
                       GetSQLValueString($_POST['contra_nueva'], "text"),
                       GetSQLValueString($_POST['id'], "int"));

  mysqli_select_db($rural, $database_rural);
  $Result1 = mysqli_query($rural, $updateSQL) or die(mysqli_error());
	}
}

mysqli_select_db($rural, $database_rural);
$query_usuario = "SELECT * FROM administracion WHERE id = 1";
$usuario = mysqli_query($rural, $query_usuario) or die(mysqli_error());
$row_usuario = mysqli_fetch_assoc($usuario);
$totalRows_usuario = mysqli_num_rows($usuario);
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8" />
<title>Documento sin titulo</title>
<link href="css/estilos.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="contenido">
<div id="menu"><?php include('archivos/menu.html'); ?><div class="limpiar"></div></div>
<div class="caja_frm">
<h1> Editar contraseña</h1>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Contraseña anterior:</td>
      <td><input type="text" name="contra_vieja" value="" size="32" />
          <?php if (isset($error['baja'])) { ?>
        <br /><span class="rojo">&nbsp;&nbsp;<?php echo $error['baja']; ?></span>
      <?php } ?>
      </td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Contraseña nueva</td>
      <td><input type="text" name="contra_nueva" value="" size="32" />
         <?php if (isset($error['nueva'])) { ?>
        <br /><span class="rojo">&nbsp;&nbsp;<?php echo $error['nueva']; ?></span>
      <?php } ?>      
      </td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Actualizar registro" class="bton-form" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="id" value="<?php echo $row_usuario['id']; ?>" />
</form>
</div>
</div>
</body>
</html>
<?php
require_once('archivos/funcion.php');
require_once('../Connections/rural.php');
require_once('archivos/funciones_frm.php');

if ((isset($_GET['id_cas'])) && ($_GET['id_cas'] != "")) { // eliminar registro
    $deleteSQL = sprintf("DELETE FROM casa WHERE id_cas=%s", GetSQLValueString($_GET['id_cas'], "int"));



    mysqli_select_db($rural, $database_rural);

    $Result1 = mysqli_query($rural, $deleteSQL) or die(mysqli_error());



    $ir = "casas_lista.php";

    header(sprintf("Location: %s", $ir));
}



// datos de la casa

$id = "-1";

if (isset($_GET['id'])) {

    $id = $_GET['id'];
}

mysqli_select_db($rural, $database_rural);

$query_casa_eliminar = sprintf("SELECT id_cas, nombre FROM casa WHERE id_cas = %s", GetSQLValueString($id, "int"));

$casa_eliminar = mysqli_query($rural, $query_casa_eliminar) or die(mysql_error());

$row_casa_eliminar = mysqli_fetch_assoc($casa_eliminar);

$totalRows_casa_eliminar = mysqli_num_rows($casa_eliminar);



// averiguar si tiene reservas

mysqli_select_db($rural, $database_rural);

$query_reservada = sprintf("SELECT id_reserva FROM reserva WHERE casa_id = %s", GetSQLValueString($id, "int"));

$reservada = mysqli_query($rural, $query_reservada) or die(mysql_error());

$row_reservada = mysqli_fetch_assoc($reservada);

$n_reservas = mysqli_num_rows($reservada);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

    <head>

        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

        <title>Panel de control: La Caraba</title>

        <link href="css/estilos.css" rel="stylesheet" type="text/css" />

    </head>



    <body>

        <div id="contenido">

            <div id="menu"><?php include('archivos/menu.html'); ?><div class="limpiar"></div></div>

            <div class="caja_frm">

                <h1>Eliminar casa</h1>

<?php
echo $row_casa_eliminar['nombre'];
echo "<br/>";

if ($n_reservas > 0) {
    ?>El registro no puede ser eliminado ya que hay reservas asociadas<?php
                } else {
                    ?>

                    <div class="bton_nuevo"><a href="casa_eliminar.php?id_cas=<?php echo $id; ?>">Confirmar</a></div>
                    <div class="bton_nuevo"> <a href="casas_lista.php">Cancelar</a></div></div>

                    <?php
                }
                ?>

    </body>

</html>

<?php
mysqli_free_result($casa_eliminar);



mysqli_free_result($reservada);
?>


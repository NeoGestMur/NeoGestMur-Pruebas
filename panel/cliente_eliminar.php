<?php
require_once('archivos/funcion.php');
require_once('../Connections/rural.php');
require_once('archivos/funciones_frm.php');

if ((isset($_GET['id_cliente'])) && ($_GET['id_cliente'] != "")) {  // eliminar cliente
    $deleteSQL = sprintf("DELETE FROM clientes WHERE id_cliente=%s", GetSQLValueString($_GET['id_cliente'], "int"));



    mysqli_select_db($rural, $database_rural);

    $Result1 = mysqli_query($rural, $deleteSQL) or die(mysqli_error());



    $deleteGoTo = "clientes_lista.php";

    if (isset($_SERVER['QUERY_STRING'])) {

        $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";

        $deleteGoTo .= $_SERVER['QUERY_STRING'];
    }

    header(sprintf("Location: %s", $deleteGoTo));
}



$id = "-1";

if (isset($_GET['id'])) {

    $id = $_GET['id'];
}

mysqli_select_db($rural, $database_rural); // datos cliente

$query_cliente = sprintf("SELECT id_cliente, nombre FROM clientes WHERE id_cliente = %s", GetSQLValueString($id, "int"));

$cliente = mysqli_query($rural, $query_cliente) or die(mysqli_error());

$row_cliente = mysqli_fetch_assoc($cliente);

$totalRows_cliente = mysqli_num_rows($cliente);



mysqli_select_db($rural, $database_rural); // buscar reservas de ese cliente

$query_reservas = sprintf("SELECT id_reserva FROM reserva WHERE cliente_id = %s", GetSQLValueString($id, "int"));

$reservas = mysqli_query($rural, $query_reservas) or die(mysql_error());

$row_reservas = mysqli_fetch_assoc($reservas);

$n_reservas = mysqli_num_rows($reservas);
?>

<html>

    <head>

        <meta charset="utf-8" />

        <title>Panel de control: La Caraba</title>

        <link href="css/estilos.css" rel="stylesheet" type="text/css" />

    </head>



    <body>

        <div id="contenido">

            <div id="menu"><?php include('archivos/menu.html'); ?><div class="limpiar"></div></div>

            <div class="caja_frm">

                <h1>Eliminar cliente</h1>

                <?php
                echo 'Nombre: ' . $row_cliente['nombre'];



                if ($n_reservas > 0) {
                    ?> <br />
                    El registro no puede ser eliminado porque tiene varias reservas asociadas. Elimine primero las reservas

                    <?php
                } else {
                    ?>

                    <div class="bton_nuevo"><a href="cliente_eliminar.php?id_cliente=<?php echo $id; ?>">Confirmar</a></div><div class="bton_nuevo"> <a href="clientes_lista.php">Cancelar</a></div></div>

                <?php
            }
            ?>

        </div>

    </body>

</html>

<?php
mysqli_free_result($cliente);



mysqli_free_result($reservas);
?>


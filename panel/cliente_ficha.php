<?php
require_once('archivos/funcion.php');
require_once('../Connections/rural.php');
require_once('archivos/funciones_frm.php');
require_once('archivos/funciones.php');

$editFormAction = $_SERVER['PHP_SELF'];

if (isset($_SERVER['QUERY_STRING'])) {

    $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$id_cliente = "-1";

if (isset($_GET['id_cliente'])) {

    $id_cliente = $_GET['id_cliente'];
}

mysqli_select_db($rural, $database_rural);

$sql_edita = sprintf("SELECT * FROM clientes WHERE id_cliente = %s", GetSQLValueString($id_cliente, "int"));

$resultados = mysqli_query($rural, $sql_edita) or die(mysql_error());

$res = mysqli_fetch_assoc($resultados);
?>


<html>

    <head>

        <meta charset="utf-8" />

        <title>Panel de control: La Caraba</title>

        <link href="css/estilos.css" rel="stylesheet" type="text/css" />

        <style type="text/css" title="currentStyle">

            @import "media/css/demo_page.css";

            @import "media/css/demo_table.css";

        </style>

        <script type="text/javascript" language="javascript" src="js/jquery-1.6.2.min.js"></script>

        <script type="text/javascript" language="javascript" src="js/jquery.dataTables.js"></script>

        <script type="text/javascript" language="javascript" src="js/thickbox.js"></script>

        <script type="text/javascript" charset="utf-8">

            $(document).ready(function () {

                $('#lista').dataTable({

                    "aaSorting": [[0, "desc"]],

                    "aoColumns": [

                        null,

                        null,

                        null,

                        null,

                        {"asSorting": [0]}

                    ]

                });

                $('#lista tbody td img.a').live('click', function () {

                    var donde = $(this.parentNode);

                    var Id = donde.attr('id');


                    $.ajax({

                        type: "POST",

                        cache: false,

                        url: 'funciones/activo.php?id=' + Id,

                        data: '{}',

                        async: false,

                        success: function (data) {

                            result = data;

                            $(donde).html(result);

                        }

                    });



                });

            });



        </script>
        <link href="css/imprimir.css" rel="stylesheet" type="text/css" media="print"> 
    </head>



    <body>

        <div id="contenido">

            <div id="menu"><?php include('archivos/menu.html'); ?><div class="limpiar"></div></div>

            <div class="caja_frm">

                <h1>Ficha cliente: <?php echo htmlentities($res['nombre'], ENT_COMPAT, 'iso-8859-1'); ?></h1>

                <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">

                    <table align="center">

                        <tr valign="baseline">

                            <td colspan="2" align="left" nowrap="nowrap">
                                <div class="botones_a">
                                    <a href="reserva_nueva.php?id_cliente=<?php echo $res['id_cliente']; ?>"><img src="img/home.png" alt="Reservar" width="35" height="35" border="0" title="Reservar" /></a>&nbsp;<a href="cliente_editar.php?id_cliente=<?php echo $res['id_cliente']; ?>"><img src="img/edit.png" alt="Editar" width="35" height="35" border="0" title="Editar" /></a>&nbsp;<a href="cliente_eliminar.php?id=<?php echo $res['id_cliente']; ?>"><img src="img/trash.png" alt="Eliminar" width="35" height="35" border="0" title="Eliminar" /></a> <a href="#" onclick="window.print();" title="Imprimir"><img src="img/document.png" width="35" height="35" alt="Imprimir" /></a>
                                </div></td>

                        </tr>

                        <tr valign="baseline">
                            <td nowrap="nowrap" align="right">Foto:</td>
                            <td>
<?php
if ($res['foto'] != "") {
    $foto = "./imagenes/clientes/" . $res['foto'];
} else {
    $foto = "./imagenes/clientes/defecto.jpg";
}
?>
                                <img src="<?php echo $foto; ?>" width="150" height="150"></br>
                            </td>
                        </tr>

                        <tr valign="baseline">

                            <td nowrap="nowrap" align="right">Nombre:</td>

                            <td><input name="nombre" type="text" disabled="disabled" value="<?php echo $res['nombre'] ?>" size="80" readonly="readonly" /></td>

                        </tr>

                        <tr valign="baseline">

                            <td nowrap="nowrap" align="right">Apellidos:</td>

                            <td><input name="apellidos" type="text" disabled="disabled" value="<?php echo $res['apellidos'] ?>" size="80" readonly="readonly" /></td>

                        </tr>

                        <tr valign="baseline">

                            <td nowrap="nowrap" align="right">DNI:</td>

                            <td><input name="dni" type="text" disabled="disabled" value="<?php echo $res['dni'] ?>" size="9" readonly="readonly" /></td>

                        </tr>
                        <tr valign="baseline">

                            <td nowrap="nowrap" align="right">Email:</td>

                            <td><input name="email" type="text" disabled="disabled" value="<?php echo $res['email'] ?>" size="80" readonly="readonly" /></td>

                        </tr>

                        <tr valign="baseline">

                            <td nowrap="nowrap" align="right">Domicilio:</td>

                            <td><input name="domicilio" type="text" disabled="disabled" value="<?php echo $res['domicilio'] ?>" size="80" readonly="readonly" /></td>

                        </tr>

                        <tr valign="baseline">

                            <td nowrap="nowrap" align="right">Código postal:</td>

                            <td><input name="cp" type="text" disabled="disabled" value="<?php echo $res['cp'] ?>" size="5" readonly="readonly" /></td>

                        </tr>

                        <tr valign="baseline">

                            <td nowrap="nowrap" align="right">Población</td>

                            <td><input name="poblacion" type="text" disabled="disabled" value="<?php echo $res['poblacion'] ?>" size="50" readonly="readonly" /></td>

                        </tr>

                        <tr valign="baseline">

                            <td nowrap="nowrap" align="right">Provincia:</td>

                            <td><input name="provincia" type="text" disabled="disabled" value="<?php echo $res['provincia'] ?>" size="50" readonly="readonly" /></td>

                        </tr>

                        <tr valign="baseline">

                            <td nowrap="nowrap" align="right">Teléfono:</td>

                            <td><input name="telefono" type="text" disabled="disabled" value="<?php echo $res['telefono'] ?>" size="50" readonly="readonly" /></td>

                        </tr>

                        <tr valign="baseline">

                            <td align="right" valign="top" nowrap="nowrap">Observaciones:</td>

                            <td><textarea name="observaciones" cols="60" rows="5" disabled="disabled" readonly="readonly" id="observaciones"><?php echo $res['Observaciones'] ?></textarea></td>

                        </tr>

                        <tr valign="baseline">

                            <td align="right" valign="top" nowrap="nowrap">¿Cómo nos conoció?</td>

                            <td><label>

                                    <input name="como" type="text" disabled="disabled" id="como" value="<?php echo $res['como'] ?>" size="50" readonly="readonly" />

                                </label></td>

                        </tr>

                    </table>

                    <input type="hidden" name="MM_update" value="form1" />

                    <input type="hidden" name="id_cliente" value="<?php echo $res['id_cliente']; ?>" />

                </form>

            </div>

            <div class="tabla">

                <h1>Reservas realizadas</h1>

<?php
mysqli_select_db($rural, $database_rural);



$sql = "SELECT c.nombre as casa, cl.nombre as cliente, r.entrada, r.salida, r.id_reserva FROM reserva r, casa c, clientes cl WHERE r.casa_id= c.id_cas AND cl.id_cliente=r.cliente_id AND cl.id_cliente = " . $res['id_cliente'] . " ORDER BY r.entrada DESC";

$result = mysqli_query($rural, $sql) or die(mysql_error());

$numero = mysqli_num_rows($result);
?>

                <table cellpadding="0" cellspacing="0" border="0" class="display" id="lista">

                    <thead>

                        <tr class="barra">

                            <th scope="col" height="28">Entrada</th>

                            <th scope="col">Salida</th>

                            <th scope="col">Cliente</th>

                            <th scope="col">Casa</th>

                            <th scope="col">Acciones</th>

                        </tr>

                    </thead>

<?php while ($datos = mysqli_fetch_assoc($result)) { ?>

                        <tr>

                            <td><?php echo formato_es($datos['entrada']); ?></td>

                            <td><?php echo formato_es($datos['salida']); ?></td>

                            <td><?php echo $datos['cliente']; ?></td>

                            <td><?php echo $datos['casa']; ?></td>

                            <td align="center"><a href="reserva_editar.php?id_reserva=<?php echo $datos['id_reserva']; ?>"><img src="img/edit.png" alt="Editar" width="20" height="20" border="0" title="Editar" /></a>&nbsp;<a href="reserva_eliminar.php?id=<?php echo $datos['id_reserva']; ?>"><img src="img/trash.png" alt="Eliminar" width="20" height="20" border="0" title="Eliminar" /></a></td>

                        </tr>

<?php } ?>

                    <tfoot>

                        <tr class="barra">

                            <th scope="col" height="28">Entrada</th>

                            <th scope="col">Salida</th>

                            <th scope="col">Cliente</th>

                            <th scope="col">Casa</th>

                            <th scope="col">&nbsp;</th>

                        </tr>

                    </tfoot>    

                </table>

            </div>

        </div>

    </body>

</html>

<?php
mysqli_free_result($resultados);
?>


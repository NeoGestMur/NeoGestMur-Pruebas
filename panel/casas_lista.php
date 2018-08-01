<?php
require_once('archivos/funcion.php');
require_once('../Connections/rural.php');

mysqli_select_db($rural, $database_rural);

$query_lista_casas = "SELECT * FROM casa ORDER BY orden ASC";
$lista_casas = mysqli_query($rural, $query_lista_casas) or die(mysql_error());
$row_lista_casas = mysqli_fetch_assoc($lista_casas);
$totalRows_lista_casas = mysqli_num_rows($lista_casas);
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
                /*
                 $('#lista').dataTable({
                                 
                 "aLengthMenu": [[30, 40, 50, -1], [30, 40, 50, "All"]],
                 "aaSorting": [[ 0, "desc" ]],
                                 
                 "aoColumns": [
                                 
                 null,
                 null,
                                 
                 { "asSorting": [ 0 ] },
                                 
                 { "asSorting": [ 0 ] }
                                 
                 ]
                                 
                 });
                 */

                $('#lista').dataTable({
                    "iDisplayLength": 30,
                    "aLengthMenu": [[30, 40, 50, -1], [30, 40, 50, "All"]],
                    "aaSorting": [[0, "desc"]],
                    "aoColumns": [

                        null,
                        null,

                        {"asSorting": [0]},

                        {"asSorting": [0]}

                    ]
                });
                $('#lista tbody td img.a').live('click', function () {

                    var donde = $(this.parentNode);

                    var Id = donde.attr('id');

                    //$(donde).load('funciones/activo.php?id='+Id);					

                    //alert(Id);



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

    </head>



    <body>

        <div id="contenido">

            <div id="menu"><?php include('archivos/menu.html'); ?><div class="limpiar"></div></div>

            <div class="bton_nuevo"><a href="casa_nueva.php">Añadir casa</a></div>

            <div class="tabla">

                <table cellpadding="0" cellspacing="0" border="0" class="display" id="lista">

                    <thead>

                        <tr class="barra">
                            <th width="50" height="28" scope="col">Plazas</th>
                            <th width="300" height="28" scope="col">Casa</th>

                            <th width="100" scope="col">Reservas</th>

                            <th width="100" scope="col">Acciones</th>

                        </tr>

                    </thead>

                    <?php do { ?>

                        <tr>
                            <td align="left"><?php echo $row_lista_casas['plazas']; ?></td>
                            <td align="left"><?php echo $row_lista_casas['nombre']; ?> </td>

                            <td align="center"><a href="reserva_ver_por_dia.php?id_casa=<?php echo $row_lista_casas['id_cas']; ?>"><img src="img/calendar.png" alt="Calendario" width="20" height="20" border="0" title="Calendario" /></a></td>

                            <td align="center"><a href="casa_editar.php?id_cas=<?php echo $row_lista_casas['id_cas']; ?>"><img src="img/edit.png" alt="Editar" width="20" height="20" border="0" title="Editar" /></a>&nbsp;<a href="casa_eliminar.php?id=<?php echo $row_lista_casas['id_cas']; ?>"><img src="img/trash.png" alt="Eliminar" width="20" height="20" border="0" title="Eliminar" /></a></td>

                        </tr>

                    <?php } while ($row_lista_casas = mysqli_fetch_assoc($lista_casas)); ?>

                    <tfoot>

                        <tr class="barra">
                            <th>Plazas</th>
                            <th height="28">Casa</th>

                            <th>Reservas</th>

                            <th>Acciones</th>

                        </tr>

                    </tfoot>

                </table>

            </div>

        </div>

    </body>

</html>

<?php
mysqli_free_result($lista_casas);
?>


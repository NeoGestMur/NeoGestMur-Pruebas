<?php
require_once('archivos/funcion.php');
require_once('../Connections/rural.php');
require_once('archivos/funciones.php');

require_once ('archivos/ceta_funciones.php');


mysqli_select_db($rural, $database_rural);

$query_reserva_lista = "SELECT r.id_reserva, c.nombre as casa, cl.nombre as cliente, cl.apellidos as apellido, r.estado, r.entrada, r.salida, r.fecha_alta,r.origen FROM reserva r, casa c, clientes cl WHERE r.casa_id= c.id_cas  AND cl.id_cliente=r.cliente_id ORDER BY r.entrada DESC";
$reserva_lista = mysqli_query($rural, $query_reserva_lista) or die(mysql_error());
$totalRows_reserva_lista = mysqli_num_rows($reserva_lista);
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



                var maintable = $('#lista').dataTable({

                    "aoColumnDefs": [
                        {"bVisible": false, "aTargets": [0]}
                    ],
                    "aaSorting": [[0, "desc"]],
                    "aoColumns": [
                        null,
                        null,
                        {"bVisible": false, "iDataSort ": 3},
                        null,
                        {"asSorting": ["desc", "asc"]},
                        null,
                        null,
                        null,
                        {"asSorting": [0]},
                        {"asSorting": [0]},
                        {"asSorting": [0]},
                    ],

                });
                function filterByOrigen(origen) {
//				      $.fn.dataTableExt.afnFiltering.push(function (oSettings, aData, iDataIndex) {
//                       return true;
//                    });
                    $.fn.dataTableExt.afnFiltering = [];
                    console.log($.fn.dataTableExt.afnFiltering);
                    $.fn.dataTableExt.afnFiltering.push(function (oSettings, aData, iDataIndex) {
                        return aData[4] == origen;
                    });
                    maintable.fnDraw();
                }
                function resetfilter() {
                    $.fn.dataTableExt.afnFiltering = [];
                    maintable.fnDraw();
                }
//				$('#testbutton').bind('click',function(){
//				    console.log("entro");
//                    $.fn.dataTableExt.afnFiltering.push(function (oSettings, aData, iDataIndex) {
//                       return aData[4]=='TC';
//                    });
//                    maintable.dataTable().fnDraw();
//                });



                $('#origen-filter').change(function ($event, v) {
                    if ($('#origen-filter').val() == '-1') {
                        resetfilter();
                    } else {
                        filterByOrigen($('#origen-filter').val());
                    }

                });

                $('#reset-filter').bind('click', function () {
                    resetfilter();
                    $('#origen-filter').val('-1');
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
            <div class="tabla">
                <div style="padding: 10px;background-color: lightgrey;margin-bottom: 5px;border: solid 1px black;">
                    <h5 style="margin-bottom: 5px;margin-top: 5px;">Filtro</h5>
                    <select name="origen" id="origen-filter">
                        <option value="-1">Origen</option>
                        <?php
                        foreach ($agencias as $k => $a) {
                            echo "<option value=" . $k . ">" . $a . "</option>";
                        }
                        ?>
                    </select>
                    <button style="padding: 5px!important;" id="reset-filter">Restablecer filtro</button>
                </div>

                <table cellpadding="0" cellspacing="0" border="0" class="display" id="lista">
                    <thead>
                        <tr class="barra">
        <!--        <th scope="col">Origen</th>-->
                            <th scope="col" height="28">fecha_e</th>
                            <th  width="100px" scope="col" height="28">Entrada</th>
                            <th scope="col">fecha_s</th>
                            <th  width="100px" scope="col">Salida</th>
                            <th scope="col">Origen</th>
                            <th  width="50px" scope="col">D&iacute;as</th>
                            <th scope="col">Cliente</th>
                            <th scope="col">Casa</th>
                            <th scope="col">Alta</th>
                            <th  width="80px" scope="col">Estado</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
<?php while ($row_reserva_lista = mysqli_fetch_assoc($reserva_lista)) { ?>
                        <tr>
                            <td><?php echo $row_reserva_lista['entrada']; ?></td>
                            <td><?php echo formato_es($row_reserva_lista['entrada']); ?></td>
                            <td><?php echo $row_reserva_lista['salida']; ?></td>
                            <td><?php echo formato_es($row_reserva_lista['salida']); ?></td>
                            <td title="<?php echo agenciaByCode($row_reserva_lista['origen']) ?>"><?php echo $row_reserva_lista['origen']; ?></td>
                            <td align="center"><?php
                                $ini = formato_es($row_reserva_lista['entrada']);
                                $fin = formato_es($row_reserva_lista['salida']);
                                echo restaFechas($ini, $fin);
                                ?></td>
                            <td><?php echo $row_reserva_lista['cliente'] . ' ' . $row_reserva_lista['apellido']; ?></td>
                            <td><?php echo $row_reserva_lista['casa']; ?></td>
                            <td><?php echo formato_es($row_reserva_lista['fecha_alta']); ?></td>
                            <td align="center"><?php
                                $reser = $row_reserva_lista['estado'];
                                if ($reser == '0')
                                    echo 'Bloqueado';
                                if ($reser == '1')
                                    echo 'Reservado';
                                if ($reser == '2')
                                    echo 'Ocupado';
                                if ($reser == '3')
                                    echo 'Vacia';
                                ?></td>
                            <td align="center"><a href="reserva_ficha.php?id=<?php echo $row_reserva_lista['id_reserva']; ?>"><img src="img/search.png" alt="Ver ficha" width="20" height="20" border="0" title="Ver ficha reserva" /></a>&nbsp;<a href="reserva_editar.php?id_reserva=<?php echo $row_reserva_lista['id_reserva']; ?>"><img src="img/edit.png" alt="Editar" width="20" height="20" border="0" title="Editar" /></a>&nbsp;<a href="reserva_eliminar.php?id=<?php echo $row_reserva_lista['id_reserva']; ?>"><img src="img/trash.png" alt="Eliminar" width="20" height="20" border="0" title="Eliminar" /></a></td>
                        </tr>
<?php } ?>
                    <tfoot>
                        <tr class="barra">
                            <th scope="col" height="28">Entrada</th>
                            <th scope="col">Salida</th>
                            <th scope="col">Origen</th>
                            <th scope="col">D&iacute;as</th>
                            <th scope="col">Cliente</th>
                            <th scope="col">Casa</th>
                            <th scope="col">Alta</th>
                            <th scope="col">Estado</th>
                        </tr>
                    </tfoot>
                </table>
            </div></div>
    </body>
</html>
<?php
mysqli_free_result($reserva_lista);
?>

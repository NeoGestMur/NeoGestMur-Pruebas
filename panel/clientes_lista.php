<?php
require_once('archivos/funcion.php');
require_once('../Connections/rural.php');
require_once('archivos/funciones.php');

mysqli_select_db($rural, $database_rural);



$query_lista_clientes = "SELECT * FROM clientes ORDER BY nombre ASC";

$lista_clientes = mysqli_query($rural, $query_lista_clientes) or die(mysql_error());

$totalRows_lista_clientes = mysqli_num_rows($lista_clientes);
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
                        {"asSorting": ["desc", "asc"]},

                        null,

                        null,

                        null,
                        {"asSorting": [0]},

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

            <div class="bton_nuevo"><a href="cliente_nuevo.php">Añadir cliente</a></div>

            <div class="tabla">

                <table cellpadding="0" cellspacing="0" border="0" class="display" id="lista">

                    <thead>

                        <tr class="barra">

                            <th scope="col" height="28">Nombre</th>
                            <th scope="col" height="28">Email</th>

                            <th scope="col">DNI</th>

                            <th scope="col">Teléfono</th>

                            <th scope="col">Provincia</th>
                            <th scope="col">Alta</th>

                            <th scope="col">Reservar</th>

                            <th scope="col">Acciones</th>

                        </tr>

                    </thead>

                    <?php while ($row_lista_clientes = mysqli_fetch_assoc($lista_clientes)) { ?>

                        <tr>

                            <td><?php echo $row_lista_clientes['nombre'] . ' ' . $row_lista_clientes['apellidos']; ?></td>
                            <td><?php echo $row_lista_clientes['email'] ?></td>

                            <td><?php echo $row_lista_clientes['dni']; ?></td>

                            <td><?php echo $row_lista_clientes['telefono']; ?></td>

                            <td><?php echo $row_lista_clientes['provincia']; ?></td>
                            <td><?php echo formato_es($row_lista_clientes['fecha']); ?></td>

                            <td align="center"><a href="reserva_nueva.php?id_cliente=<?php echo $row_lista_clientes['id_cliente']; ?>"><img src="img/home.png" alt="Reservar" width="20" height="20" border="0" title="Reservar" /></a></td>

                            <td align="center"><a href="cliente_ficha.php?id_cliente=<?php echo $row_lista_clientes['id_cliente']; ?>"><img src="img/search.png" alt="Ver ficha" width="20" height="20" border="0" title="Ver ficha: <?php echo $row_lista_clientes['nombre']; ?>" /></a>&nbsp;<a href="cliente_editar.php?id_cliente=<?php echo $row_lista_clientes['id_cliente']; ?>"><img src="img/edit.png" alt="Editar" width="20" height="20" border="0" title="Editar" /></a>&nbsp;<a href="cliente_eliminar.php?id=<?php echo $row_lista_clientes['id_cliente']; ?>"><img src="img/trash.png" alt="Eliminar" width="20" height="20" border="0" title="Eliminar" /></a></td>

                        </tr>

                    <?php } ?>

                    <tfoot>

                        <tr class="barra">

                            <th scope="col" height="28">Nombre</th>
                            <th scope="col" height="28">Email</th>

                            <th scope="col">DNI</th>

                            <th scope="col">Teléfono</th>

                            <th scope="col">Provincia</th>
                            <th scope="col">Alta</th>

                            <th scope="col">Reservar</th>

                            <th scope="col">Acciones</th>

                        </tr>

                    </tfoot>

                </table>

            </div></div>

    </body>

</html>

<?php
mysqli_free_result($lista_clientes);
?>


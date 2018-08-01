<?php
require_once('archivos/funcion.php');
require_once('../Connections/rural.php');

mysqli_select_db($rural, $database_rural);

$sql = "SELECT * FROM personal ORDER BY nombre ASC";
$personal = mysqli_query($rural, $sql) or die(mysqli_error());
$resul_personal = mysqli_num_rows($personal);
$rangoLista = "Null";
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html">
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
            <?php
            if ($_SESSION['rangoUsu'] == 1) {
                ?>
                <div class="bton_nuevo"><a href="empleado_nuevo.php">Añadir empleado</a></div>
                <?php
            }
            ?>
            <div class="tabla">
                <table cellpadding="0" cellspacing="0" border="0" class="display" id="lista">
                    <thead>
                        <tr class="barra">
                            <th scope="col" height="28">Nombre</th>
                            <th scope="col">Apellidos</th>
                            <th scope="col">Rango</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>	
                    <?php
                    while ($resul_personal = mysqli_fetch_assoc($personal)) {
                        if ($resul_personal['rango'] == 1) {
                            $rangoLista = "Administrador";
                        } else {
                            $rangoLista = "Empleado";
                        }
                        $id_persona_actual = $resul_personal['id_personal'];
                        ?>
                        <tr>
                            <td><?php echo $resul_personal['nombre']; ?></td>
                            <td><?php echo $resul_personal['apellidos']; ?></td>
                            <td align = "center"><?php echo $rangoLista; ?></td>

                            <?php
                            if ($_SESSION['rangoUsu'] != 1) {
                                if ($resul_personal['nombre'] == $_SESSION['nombreUsu']) {
                                    ?>
                                                        <!--<td align = "center"><a href = "empleados_editar.php?id_p=<?php echo $id_persona_actual ?>">Editar Datos</a>&nbsp;-->
                                    <!--</td>-->
                                    <td align = "center">
                                        <form method="POST" action="empleados_editar.php">
                                            <input type="hidden" name="id_e" value="<?php echo $id_persona_actual ?>"/>
                                            <input type="submit" name="aceptar_listado" value="Editar Datos"/>
                                        </form>
                                    </td>
                                    <?php
                                }
                            } else {
                                ?>
                                <td align = "center">
                                    <form method="POST" action="empleados_editar.php">
                                        <input type="hidden" name="id_e" value="<?php echo $id_persona_actual ?>"/>
                                        <input type="image" name="aceptar_listado" src="img/edit.png" value="Editar Datos"/>
                                    </form>
                                </td>
                            <!--<td align="center"><a href="empleados_editar.php?id_p=<?php echo $resul_personal['id_personal']; ?>"><img src="img/edit.png" alt="Editar" width="20" height="20" border="0" title="Editar" /></a>&nbsp;-->
                                <!--<a href="empleado_eliminar.php?id_p=<?php echo $resul_personal['id_personal']; ?>"><img src="img/trash.png" alt="Eliminar" width="20" height="20" border="0" title="Eliminar" /></a></td>-->
                                <?php
                            }
                            ?>
                        </tr>
                    <?php } ?>
                    <tfoot>
                        <tr class="barra">
                            <th scope="col" height="28">Nombre</th>
                            <th scope="col">Apellidos</th>
                            <th scope="col">Rango</th>
                            <th scope="col">Acciones</th>

                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </body>
</html>
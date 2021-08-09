<table id="tabla" namefile="Ot_listados" totales="<?php echo $_SESSION["totales"]; ?>" registros="<?php echo $_SESSION['cant_reg']; ?>" pagina="<?php $_SESSION['pagina']; ?>" class="table table-striped table-hover" mes="<?php echo $mes; ?>" anio="<?php echo $anio; ?>" dia="<?php echo $dia; ?>" opcion="<?php echo $opcion; ?>"> 
    <thead>
        <tr class="row " style="background-color: transparent;">
            <th class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center ordena" orderby="nro_serie" sentido="asc">OT</th>
            <th class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center ordena" orderby="fecha" sentido="asc">Fecha Ingreso</th>
            <th class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center ordena" orderby="fecha_entrega" sentido="asc">Fecha Entrega</th>
            <th class="col-lg-2 col-md-2 col-sm-2 col-xs-2 text-center ordena" orderby="cliente" sentido="asc">Cliente</th>
            <th class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-center ordena" orderby="observaciones" sentido="asc">Nombre OT</th>
            <th class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center ordena" orderby="prioridad" sentido="asc">Avance</th>
            <th class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center ordena" orderby="prioridad" sentido="asc">Prioridad</th>
            <th class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center ordena" orderby="finalizada" sentido="asc"></th>
            <th class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center noExl">Acciones</th>
        </tr>
    </thead>
    <tbody id="body">
        <?php foreach ($registros as $usu) { ?>
            <tr class="row single_registro" codigo="<?php echo $usu["codigo"]; ?>"
                estado="<?php echo intval($usu["finalizada"]); ?>" avance="<?php echo floatval($usu["avance"]); ?>" >
                <td class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center" style="vertical-align: middle;"><?php echo $usu["nro_serie"]; ?></td>
                <td class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center" style="vertical-align: middle;"><?php echo $usu["fecha"]; ?></td>
                <td class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center" style="vertical-align: middle;"><?php echo date("Y", strtotime($usu["fecha_entrega"])) > 2000 ? $usu["fecha_entrega"] : ""; ?></td>
                <td class="col-lg-2 col-md-2 col-sm-2 col-xs-2 text-left" style="vertical-align: middle;"><?php echo $usu["cliente"]; ?></td>
                <td class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-left" style="vertical-align: middle;"><?php echo $usu["observaciones"]; ?></td>
                <td class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center" style="vertical-align: middle;">
                    <?php echo "<b>" . number_format($usu["avance"],2) . "% </b>" ; ?>
                </td>
                <td class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center" style="vertical-align: middle;"><?php echo $usu["desc_prioridad"]; ?></td>
                <td class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center" style="vertical-align: middle;">
                <?php 
                    if ($usu["finalizada"] == 1){
                        echo '<span" class="label label-danger m-t-lg" style="background-color: #65b399; color: #EEE;">FINALIZADA</span>';
                    } elseif ($usu["finalizada"] == 0 or $usu["finalizada"] == null){
                        echo '<span" class="label label-danger m-t-lg" style="background-color: #9ec569; color: #eee;">EN COLA</span>';
                    } elseif ($usu["finalizada"] == -1){
                        echo '<span" class="label label-danger m-t-lg" style="background-color: #9e0000; color: #eee;">CANCELADA</span>';
                    } elseif ($usu["finalizada"] == 2){
                        echo '<span" class="label label-danger m-t-lg" style="background-color: #9e9e69; color: #eee;">EN CURSO</span>';
                    } 
                ?>
                </td>
                <td class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center noExl" style="vertical-align: middle;">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-primary dropdown-toggle nuevo" id="menu" type="button" data-toggle="dropdown"  style="font-size: 10px;height: 15px;">
                            <div class="opciones" style="margin-top: -5px">Opciones <span class="caret"></span></div>
                        </button>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="menu">
                            <li role="presentation" class="archivosOt_listado"><a role="menuitem" tabindex="-1" href="#">Archivo OT</a></li>
                            <li role="presentation" class="detallesOt_listado"><a role="menuitem" tabindex="-1" href="#">Items</a></li>
                            <?php 
                                if ($usu["finalizada"] == 1 or $usu["finalizada"] == -1){
                                    if ($_SESSION["rol"] == 1 or $_SESSION["rol"] == 2){
                                        echo '<li role="presentation" class="estadoOt_listado"><a role="menuitem" tabindex="-1" href="#">Cambiar estado</a></li>';
                                        //echo '<li role="presentation" class="abrirOt_listado"><a role="menuitem" tabindex="-1" href="#">Reabrir</a></li>';
                                    }
                                } else {
                                    if ($_SESSION["rol"] == 1 or $_SESSION["rol"] == 2){
                                        echo '<li role="presentation" class="estadoOt_listado"><a role="menuitem" tabindex="-1" href="#">Cambiar estado</a></li>';
                                        //echo '<li role="presentation" class="finalizarOt_listado"><a role="menuitem" tabindex="-1" href="#">Finalizar</a></li>';
                                    }
                                    echo '<li role="presentation" class="editOt_listado"><a role="menuitem" tabindex="-1" href="#">Editar</a></li>';
                                    echo '<li class="divider"></li>';
                                    echo '<li role="presentation" class="deleteOt_listado"><a role="menuitem" tabindex="-1" href="#">Eliminar</a></li>';
                                }
                            ?>
                            <!-- <li role="presentation" class="filesOt_listado"><a role="menuitem" tabindex="-1" href="#">Archivos</a></li> -->
                        </ul>
                    </div>
                </td>
            </tr>
<?php } ?>
    </tbody>
</table>

<div class="col-lg-2" style="float: left;text-align: left;margin-top: 8px; display: none;" id='leyenda_paginacion_aux'>
    <label>Mostrando <?php echo ($_SESSION["pagina"] - 1) * $_SESSION["cant_reg"] + 1; ?> a <?php
        if ($_SESSION["totales"] <= $_SESSION["pagina"] * $_SESSION["cant_reg"])
            echo $_SESSION["totales"];
        else
        if ($_SESSION["cant_reg"] == -1) {
            echo $_SESSION["totales"];
        } else {
            echo $_SESSION["pagina"] * $_SESSION["cant_reg"];
        }
        ?> de <?php echo $_SESSION["totales"]; ?></label>
</div>

<script>
    var requestSent = false;

    $("#btn-eliminar-ot_listado").click(function () {
        if (!requestSent) {
            requestSent = true;
            var parametros = {
                funcion: "deleteOt_listado",
                codigo: codigo
            }
            $.ajax({
                type: "POST",
                url: 'controller/ot_listados.controller.php',
                data: parametros,
                success: function (datos) {
                    if (parseInt(datos) == 0) {
                        location.reload();
                    } else {
                        alert("Error");
                    }
                },
                error: function () {
                    alert("Error");
                },
                complete: function () {
                    //me.data('requestRunning', false);
                    requestSent = false;
                }
            });
            event.preventDefault();
        }
    });
    
    $(".finalizarOt_listado").click(function () {
        codigo = $(this).closest('tr').attr("codigo");
        var parametros = {
            funcion: "finalizarOt_listado",
            codigo: codigo
        }
        $.ajax({
            type: "POST",
            url: 'controller/ot_listados.controller.php',
            data: parametros,
            success: function (data) {
                location.reload();
            },
            error: function () {
                alert("Error");
            }
        });
        //event.preventDefault();
        $('#dataUpdate').modal('show');
    });

    $(".abrirOt_listado").click(function () {
        codigo = $(this).closest('tr').attr("codigo");
        var parametros = {
            funcion: "abrirOt_listado",
            codigo: codigo
        }
        $.ajax({
            type: "POST",
            url: 'controller/ot_listados.controller.php',
            data: parametros,
            success: function (data) {
                location.reload();
            },
            error: function () {
                alert("Error");
            }
        });
    });

    $(".editOt_listado").click(function () {
        codigo = $(this).closest('tr').attr("codigo");
        $("#text-header-body").html("¿Desea eliminar el registro?");
        $("#btn-eliminar-ot_listado").css("display", "inline-block");
        $("#btn-cancelar").text("Cancelar");

        var parametros = {
            funcion: "getOt_listado",
            codigo: codigo
        }
        $.ajax({
            type: "POST",
            url: 'controller/ot_listados.controller.php',
            data: parametros,
            success: function (data) {
                var datos = JSON.parse(data);
                $('#nroserieUpdate').val(datos.nro_serie);
                $('#clienteUpdate').val(datos.cliente);
                $('#prioridadUpdate').val(datos.prioridad);
                $('#fechaUpdate').val(datos.fecha);
                $('#observacionesUpdate').val(datos.observaciones);
            },
            error: function () {
                alert("Error");
            }
        });
        //event.preventDefault();
        $('#dataUpdate').modal('show');
    });

    $(".deleteOt_listado").click(function () {
        codigo = $(this).closest('tr').attr("codigo");
        $("#name-header-modal").html("<b>Eliminar</b>");
        $("#text-header-body").html("¿Desea eliminar el registro ?");
        $("#btn-eliminar").css("display", "inline-block");
        $("#btn-cancelar").text("Cancelar");
        $('#myModal').modal('show');
    });

    $(".estadoOt_listado").click(function () {
        codigo = $(this).closest('tr').attr("codigo");
        var estado = $(this).closest('tr').attr("estado");
        var avance = $(this).closest('tr').attr("avance");
        $("#estadoAdd").val(estado);
        $("#avanceAdd").val(avance);
        $('#myModalEstado').modal('show');
    });

    $(".ordena").click(function () {

        var orderby = $(this).attr("orderby");
        var sentido = $("#div_tabla").attr("sentido");

        if (sentido == "asc") {
            sentido = "desc";
        } else {
            sentido = "asc";
        }

        $("#div_tabla").attr("orderby", orderby);
        $("#div_tabla").attr("sentido", sentido);

        var registros = $("#cant_reg").val();
        var pagina = 1;
        var busqueda = $("#busqueda").val();
        getRegistros(orderby, sentido, registros, pagina, busqueda, null);
        //callGetRegistros(orderby, sentido, registros, pagina, busqueda, this);
        //return false;
    });
    
    $("tbody > tr").click(function () {
        $("tbody > tr").css("background-color", "");
        $(this).css("background-color", "#FFFFB8");
    });
    
    $(".detallesOt_listado").click(function () {
        codigo = $(this).closest('tr').attr("codigo");
        window.location.href = "detalles.php?cod_ot=" + codigo;
    });
    
    $(".filesOt_listado").click(function () {
        codigo = $(this).closest('tr').attr("codigo");
        window.location.href = "ot_archivos.php?cod_ot=" + codigo;
    });
    
    $(".archivosOt_listado").click(function () {
        codigo = $(this).closest('tr').attr("codigo");
        window.location.href = "files_ot.php?opc=" + codigo;
    });
    
    $(".single_registro").dblclick(function () {
        codigo = $(this).attr("codigo");
        window.location.href = "detalles.php?cod_ot=" + codigo;
    });
</script>
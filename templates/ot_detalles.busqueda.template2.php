<table id="tabla" namefile="Ot_detalles" totales="<?php echo $_SESSION["totales"]; ?>" registros="<?php echo $_SESSION['cant_reg']; ?>" pagina="<?php $_SESSION['pagina']; ?>" class="table table-striped table-hover" mes="<?php echo $mes; ?>" anio="<?php echo $anio; ?>" dia="<?php echo $dia; ?>" opcion="<?php echo $opcion; ?>"> 
    <thead>
        <tr class="row " style="background-color: transparent;">
            <th class="text-center ordena" orderby="orden_trabajo_id" sentido="asc"></th>
            <th class="text-left ordena" orderby="item_vendido" sentido="asc">Item Vendido</th>
            <th class="text-center ordena" orderby="seccion" sentido="asc">Seccion</th>
            <th class="text-center ordena" orderby="sector" sentido="asc">Sector</th>
            <th class="text-center ordena" orderby="prioridad" sentido="asc">Prioridad</th>
            <th class="text-center ordena" orderby="estado" sentido="asc">Estado</th>
            <th class="text-center ordena" orderby="cantidad" sentido="asc">Cantidad</th>
            <th class="text-left ordena" orderby="observaciones" sentido="asc">Observaciones</th>
            <th class="text-center noExl">Acciones</th>
        </tr>
    </thead>
    <tbody id="body">
        <?php foreach ($registros as $usu) { ?>
            <tr class="row" codigo="<?php echo $usu["codigo"]; ?>">
                <td class="text-center verprod" oculto="1" style="vertical-align: middle; cursor: pointer; font-weight: bolder;">[+]</td>
                <td class="text-left  " style="vertical-align: middle; font-weight: bolder;"><?php echo $usu["item_vendido"]; ?></td>
                <td class="text-center" style="vertical-align: middle;"><?php echo $usu["seccion"]; ?></td>
                <td class="text-center" style="vertical-align: middle;"><?php echo $usu["sector"]; ?></td>
                <td class="text-center" style="vertical-align: middle;"><?php echo $usu["prioridad"]; ?></td>
                <td class="text-center" style="vertical-align: middle;"><?php echo $usu["estado"]; ?></td>
                <td class="text-center" style="vertical-align: middle;"><?php echo $usu["cantidad"]; ?></td>
                <td class="text-left" style="vertical-align: middle;"><?php echo $usu["observaciones"]; ?></td>
                <td class="text-center noExl" style="vertical-align: middle;">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-primary dropdown-toggle nuevo" id="menu" type="button" data-toggle="dropdown"  style="font-size: 10px;height: 15px;">
                            <div class="opciones" style="margin-top: -6px">Opciones <span class="caret"></span></div>
                        </button>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="menu">
                            <li role="presentation" class="editOt_detalle"><a role="menuitem" tabindex="-1" href="#">Editar</a></li>
                            <li role="presentation" class="prodestandar_detalle"><a role="menuitem" tabindex="-1" href="#">Prod. Estandar</a></li>
                            <li role="presentation" class="prodperso_detalle"><a role="menuitem" tabindex="-1" href="#">Prod. Personalizado</a></li>
                            <li class="divider"></li>
                            <li role="presentation" class="deleteOt_detalle"><a role="menuitem" tabindex="-1" href="#">Eliminar</a></li>
                        </ul>
                    </div>
                </td>

                <?php 
                    foreach ($prods as $prod) { 
                        if ($prod["ot_detalle_id"] != $usu["codigo"]){
                            continue;
                        }
                ?>
                    <tr class="row otdetprod" cod_det="<?php echo $usu["codigo"]; ?>" codigo="<?php echo $prod["codigo"]; ?>" style="display: none;">
                        <td class="text-center" style="vertical-align: middle;"></td>
                        <td colspan="3" class="text-left" style="vertical-align: middle;">
                            <?php 
                                if ($prod["standar"] == 1) {
                                    echo $prod["prod_standar"] . " (" . $prod["unidad"] . ")";
                                } else {
                                    echo $prod["prod_personalizado"] . " (" . $prod["unidad"] . ")";
                                }
                            ?>
                            </td>
                        <td class="text-center" style="vertical-align: middle;"><?php echo $prod["prioridad"]; ?></td>
                        <td class="text-center" style="vertical-align: middle;"><?php echo $prod["estado"]; ?></td>
                        <td class="text-center" style="vertical-align: middle;"><?php echo $prod["cantidad"]; ?></td>
                        <td class="text-center" style="vertical-align: middle;"><?php echo $prod["observaciones"]; ?></td>
                        <td class="text-center noExl" style="vertical-align: middle;">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-primary dropdown-toggle nuevo" id="menu" type="button" data-toggle="dropdown"  style="font-size: 10px;height: 15px;">
                                    <div class="opciones" style="margin-top: -6px">Opciones <span class="caret"></span></div>
                                </button>
                                <ul class="dropdown-menu" role="menu" aria-labelledby="menu">
                                    <!--<li role="presentation" class="editprod_detalle"><a role="menuitem" tabindex="-1" href="#">Editar</a></li>
                                    <li class="divider"></li>-->
                                    <li role="presentation" class="deleteprod_detalle"><a role="menuitem" tabindex="-1" href="#">Eliminar</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
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

    $(".deleteprod_detalle").click(function () {
        codigo = $(this).closest('tr').attr("codigo");
        if (!requestSent) {
            requestSent = true;
            var parametros = {
                funcion: "deleteOt_produccion",
                codigo: codigo
            }
            $.ajax({
                type: "POST",
                url: 'controller/ot_produccions.controller.php',
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

    $("#btn-eliminar-ot_detalle").click(function () {
        if (!requestSent) {
            requestSent = true;
            var parametros = {
                funcion: "deleteOt_detalle",
                codigo: codigo
            }
            $.ajax({
                type: "POST",
                url: 'controller/ot_detalles.controller.php',
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

    $(".editOt_detalle").click(function () {
        codigo = $(this).closest('tr').attr("codigo");
        $("#text-header-body").html("¿Desea eliminar el registro?");
        $("#btn-eliminar-ot_detalle").css("display", "inline-block");
        $("#btn-cancelar").text("Cancelar");

        var parametros = {
            funcion: "getOt_detalle",
            codigo: codigo
        }
        $.ajax({
            type: "POST",
            url: 'controller/ot_detalles.controller.php',
            data: parametros,
            success: function (data) {
                var datos = JSON.parse(data);
                $('#itemUpdate').val(datos.item_vendido);
                $('#cantidadUpdate').val(datos.cantidad);
                $('#seccionUpdate').val(datos.seccion_id);
                $('#sectorUpdate').val(datos.sector_id);
                $('#estadoUpdate').val(datos.estado_id);
                $('#prioridadUpdate').val(datos.prioridad_id);
                $('#otUpdate').val(datos.orden_trabajo_id);
                $('#observacionesUpdate').val(datos.observaciones);
            },
            error: function () {
                alert("Error");
            }
        });
        //event.preventDefault();
        $('#dataUpdate').modal('show');
    });

    $(".editprod_detalle").click(function () {
        codigo = $(this).closest('tr').attr("codigo");
        window.location.href = "files_p.php?opc=" + codigo;
    });

    $(".deleteOt_detalle").click(function () {
        codigo = $(this).closest('tr').attr("codigo");
        $("#name-header-modal").html("<b>Eliminar</b>");
        $("#text-header-body").html("¿Desea eliminar el registro ?");
        $("#btn-eliminar").css("display", "inline-block");
        $("#btn-cancelar").text("Cancelar");
        $('#myModal').modal('show');
    });

    $(".prodperso_detalle").click(function () {
        codigo = $(this).closest('tr').attr("codigo");
        $('#dataProdperso').modal('show');
    });

    $(".prodestandar_detalle").click(function () {
        codigo = $(this).closest('tr').attr("codigo");
        window.location.href = "ot_detalle_standar.php?n=1&otd="+codigo;
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

    $(".verprod").click(function () {
        codigo = $(this).closest('tr').attr("codigo");
        if ( $(this).attr("oculto") == 0){
            $(this).attr("oculto","1");
            $(this).html("[+]");
            //$(".otdetprod").css("display", "none");
            $('.otdetprod[cod_det="'+codigo+'"]').css("display", "none");
        } else {
            $(this).attr("oculto","0");
            $(this).html("[-]");
            $('.otdetprod[cod_det="'+codigo+'"]').css("display", "table-row");
            /*$(".verprod").attr("oculto","1");
            $(this).attr("oculto","0");
            $(".verprod").html("[+]");
            $(this).html("[-]");
            $(".otdetprod").css("display", "none");
            $('.otdetprod[cod_det="'+codigo+'"]').css("display", "table-row");*/
        }
    });
</script>
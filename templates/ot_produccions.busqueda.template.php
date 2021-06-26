<table id="tabla" namefile="Ot_produccions" totales="<?php echo $_SESSION["totales"]; ?>" registros="<?php echo $_SESSION['cant_reg']; ?>" pagina="<?php $_SESSION['pagina']; ?>" class="table table-striped table-hover" mes="<?php echo $mes; ?>" anio="<?php echo $anio; ?>" dia="<?php echo $dia; ?>" opcion="<?php echo $opcion; ?>"> 
    <thead>
        <tr class="row " style="background-color: transparent;">
            <th class="text-center ordena" orderby="ot_detalle_id" sentido="asc">#OT</th>
            <th class="text-center ordena" orderby="prod_standar" sentido="asc">Prod. Standar</th>
            <th class="text-center ordena" orderby="prod_personalizado" sentido="asc">Prod. Personalizado</th>
            <th class="text-center ordena" orderby="unidad" sentido="asc">Unidad</th>
            <th class="text-center ordena" orderby="prioridad" sentido="asc">Prioridad</th>
            <th class="text-center ordena" orderby="estado" sentido="asc">Estado</th>
            <th class="text-center ordena" orderby="avance" sentido="asc">Avance</th>
            <th class="text-center ordena" orderby="cantidad" sentido="asc">Cantidad</th>
            <th class="text-left ordena" orderby="observaciones" sentido="asc">Observaciones</th>
            <th class="text-center noExl">Acciones</th>
        </tr>
    </thead>
    <tbody id="body">
        <?php foreach ($registros as $usu) { ?>
            <tr class="row" codigo="<?php echo $usu["codigo"]; ?>">
                <td class="text-center" style="vertical-align: middle;"><?php echo $usu["ot_detalle_id"]; ?></td>
                <td class="text-center" style="vertical-align: middle;"><?php echo $usu["prod_standar"]; ?></td>
                <td class="text-center" style="vertical-align: middle;"><?php echo $usu["prod_personalizado"]; ?></td>
                <td class="text-center" style="vertical-align: middle;"><?php echo $usu["unidad"]; ?></td>
                <td class="text-center" style="vertical-align: middle;"><?php echo $usu["prioridad"]; ?></td>
                <td class="text-center" style="vertical-align: middle;"><?php echo $usu["estado"]; ?></td>
                <td class="text-center" style="vertical-align: middle;"><?php echo $usu["avance"]; ?></td>
                <td class="text-center" style="vertical-align: middle;"><?php echo $usu["cantidad"]; ?></td>
                <td class="text-left" style="vertical-align: middle;"><?php echo $usu["observaciones"]; ?></td>
                <td class="text-center noExl" style="vertical-align: middle;">
                    <div class="editOt_produccion" style="float: left; margin-left: 10px;"><a href="#"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></div>
                    <div class="deleteOt_produccion" style="float: right;margin-right: 10px;"><a href="#"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></div>
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

    $("#btn-eliminar-ot_produccion").click(function () {
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

    $(".editOt_produccion").click(function () {
        codigo = $(this).closest('tr').attr("codigo");
        $("#text-header-body").html("¿Desea eliminar el registro?");
        $("#btn-eliminar-ot_produccion").css("display", "inline-block");
        $("#btn-cancelar").text("Cancelar");

        var parametros = {
            funcion: "getOt_produccion",
            codigo: codigo
        }
        $.ajax({
            type: "POST",
            url: 'controller/ot_produccions.controller.php',
            data: parametros,
            success: function (data) {
                var datos = JSON.parse(data);
                $('#avanceUpdate').val(datos.avance);
                $('#cantidadUpdate').val(datos.cantidad);
                $('#opcionUpdate').val(datos.standar);
                $('#standarUpdate').val(datos.prod_estandar_id);
                $('#personalizadoUpdate').val(datos.prod_personalizado_id);
                $('#unidadUpdate').val(datos.unidad_id);
                $('#estadoUpdate').val(datos.estado_id);
                $('#prioridadUpdate').val(datos.prioridad_id);
                $('#otUpdate').val(datos.ot_detalle_id);
                $('#observacionesUpdate').val(datos.observaciones);
            },
            error: function () {
                alert("Error");
            }
        });
        //event.preventDefault();
        $('#dataUpdate').modal('show');
    });

    $(".deleteOt_produccion").click(function () {
        codigo = $(this).closest('tr').attr("codigo");
        $("#name-header-modal").html("<b>Eliminar</b>");
        $("#text-header-body").html("¿Desea eliminar el registro ?");
        $("#btn-eliminar").css("display", "inline-block");
        $("#btn-cancelar").text("Cancelar");
        $('#myModal').modal('show');
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
</script>
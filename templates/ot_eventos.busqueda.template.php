<table id="tabla" namefile="Ot_eventos" totales="<?php echo $_SESSION["totales"]; ?>" registros="<?php echo $_SESSION['cant_reg']; ?>" pagina="<?php $_SESSION['pagina']; ?>" class="table table-striped table-hover" mes="<?php echo $mes; ?>" anio="<?php echo $anio; ?>" dia="<?php echo $dia; ?>" opcion="<?php echo $opcion; ?>"> 
    <thead>
        <tr class="row " style="background-color: transparent;">
            <th class="text-center " orderby="ot_id" sentido="asc">OT/OV</th>
            <!--<th class="text-center ordena" orderby="ot_produccion_id" sentido="asc">#OT Produccion</th>
            <th class="text-center ordena" orderby="seccion" sentido="asc">Destino</th>-->
            <th class="text-center " orderby="ot_produccion_id" sentido="asc">ID</th>
            <th class="text-center " orderby="codigo" sentido="asc">DESC</th>
            <th class="text-center " orderby="sector" sentido="asc">Evento</th>
            <th class="text-left " style="width: 50%;" orderby="observaciones" sentido="asc">Observaciones</th>
            <th class="text-left " orderby="usuario_m" sentido="asc">Usuario</th>
            <th class="text-left " orderby="fecha_m" sentido="asc">Fecha y Hora</th>
            <!-- <th class="text-center noExl">Acciones</th> -->
        </tr>
    </thead>
    <tbody id="body">
        <?php foreach ($registros as $usu) { ?>
            <tr class="row" codigo="<?php echo $usu["codigo"]; ?>">
                <td class="text-center" style="vertical-align: middle;">
                    <?php 
                        echo $usu["cod_ot"] > 0 ? $usu["cod_ot"] : $usu["ot_id"];
                        /*if ($usu["ot_detalle_id"]){
                            echo " (".$usu["ot_detalle_id"].")";
                        }*/
                    ?>
                </td>
                <!--<td class="text-center" style="vertical-align: middle;">
                    <?php 
                        foreach ($ots_detalles as $r){
                            if ($r["codigo"] == $usu["ot_detalle_id"]){
                                echo $r["descripcion"];
                            }
                        }
                    ?>
                </td>
                <td class="text-center" style="vertical-align: middle;">
                    <?php 
                        foreach ($ots_produccions as $r){
                            if ($r["codigo"] == $usu["ot_detalle_id"]){
                                echo $r["descripcion"];
                            }
                        }
                    ?>
                </td>
                <td class="text-center" style="vertical-align: middle;"><?php echo $usu["destino"]; ?></td>-->
                <td class="text-center" style="vertical-align: middle;">
                    <?php 
                        $_id = "-";
                        if ($usu["ot_produccion_id"] > 0){
                            $_id = $usu["ot_produccion_id"];
                            while(strlen($_id) < 5){
                                $_id = '0' . $_id;
                            }
                        }
                        echo $_id; 
                    ?>
                </td>
                <td class="text-left" style="vertical-align: middle;"><?php echo $usu["custom"] ? $usu["custom"] : $usu["estandar"]; ?></td>
                <td class="text-center" style="vertical-align: middle;"><?php echo $usu["evento"]; ?></td>
                <td class="text-left" style="vertical-align: middle;"><?php echo $usu["observaciones"]; ?></td>
                <td class="text-left" style="vertical-align: middle;"><?php echo $usu["usuario_m"]; ?></td>
                <td class="text-left" style="vertical-align: middle;"><?php echo $usu["fecha_m"]; ?></td>
                <!-- <td class="text-center noExl" style="vertical-align: middle;">
                    <div class="editOt_evento" style="float: left; margin-left: 10px;"><a href="#"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></div>
                    <div class="deleteOt_evento" style="float: right;margin-right: 10px;"><a href="#"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></div>
                </td> -->
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

    $("#btn-eliminar-ot_evento").click(function () {
        if (!requestSent) {
            requestSent = true;
            var parametros = {
                funcion: "deleteOt_evento",
                codigo: codigo
            }
            $.ajax({
                type: "POST",
                url: 'controller/ot_eventos.controller.php',
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

    $(".editOt_evento").click(function () {
        codigo = $(this).closest('tr').attr("codigo");
        $("#text-header-body").html("¿Desea eliminar el registro?");
        $("#btn-eliminar-ot_evento").css("display", "inline-block");
        $("#btn-cancelar").text("Cancelar");

        var parametros = {
            funcion: "getOt_evento",
            codigo: codigo
        }
        $.ajax({
            type: "POST",
            url: 'controller/ot_eventos.controller.php',
            data: parametros,
            success: function (data) {
                var datos = JSON.parse(data);
                $('#detalleUpdate').val(datos.ot_detalle_id);
                $('#produccionUpdate').val(datos.ot_produccion_id);
                $('#eventoUpdate').val(datos.evento_id);
                $('#destinoUpdate').val(datos.destino_id);
                $('#observacionesUpdate').val(datos.observaciones);
            },
            error: function () {
                alert("Error");
            }
        });
        //event.preventDefault();
        $('#dataUpdate').modal('show');
    });

    $(".deleteOt_evento").click(function () {
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
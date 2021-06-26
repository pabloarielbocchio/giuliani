<table id="tabla" namefile="Archivo_destinos" totales="<?php echo $_SESSION["totales"]; ?>" registros="<?php echo $_SESSION['cant_reg']; ?>" pagina="<?php $_SESSION['pagina']; ?>" class="table table-striped table-hover" mes="<?php echo $mes; ?>" anio="<?php echo $anio; ?>" dia="<?php echo $dia; ?>" opcion="<?php echo $opcion; ?>"> 
    <thead>
        <tr class="row " style="background-color: transparent;">
            <th class="text-left ordena" orderby="archivo_id" sentido="asc">Archivo</th>
            <th class="text-left ordena" orderby="destino_id" sentido="asc">Destino</th>
            <th class="text-left ordena" orderby="observaciones" sentido="asc">Observaciones</th>
            <th class="text-center noExl">Acciones</th>
        </tr>
    </thead>
    <tbody id="body">
        <?php foreach ($registros as $usu) { ?>
            <tr class="row" codigo="<?php echo $usu["codigo"]; ?>">
                <td class="text-left" style="vertical-align: middle;">
                    <?php 
                        foreach ($archivos as $r){
                            if ($r["codigo"] == $usu["archivo_id"]){
                                echo $r["descripcion"];
                            }
                        }
                    ?>
                </td>
                <td class="text-left" style="vertical-align: middle;">
                    <?php 
                        foreach ($destinos as $r){
                            if ($r["codigo"] == $usu["destino_id"]){
                                echo $r["descripcion"];
                            }
                        }
                    ?>
                </td>
                <td class="text-left" style="vertical-align: middle;"><?php echo $usu["observaciones"]; ?></td>
                <td class="text-center noExl" style="vertical-align: middle;">
                    <div class="editArchivo_destino" style="float: left; margin-left: 10px;"><a href="#"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></div>
                    <div class="deleteArchivo_destino" style="float: right;margin-right: 10px;"><a href="#"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></div>
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

    $("#btn-eliminar-archivo_destinos").click(function () {
        if (!requestSent) {
            requestSent = true;
            var parametros = {
                funcion: "deleteArchivo_destino",
                codigo: codigo
            }
            $.ajax({
                type: "POST",
                url: 'controller/archivo_destinos.controller.php',
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

    $(".editArchivo_destino").click(function () {
        codigo = $(this).closest('tr').attr("codigo");
        $("#text-header-body").html("¿Desea eliminar el registro?");
        $("#btn-eliminar-archivo_destino").css("display", "inline-block");
        $("#btn-cancelar").text("Cancelar");

        var parametros = {
            funcion: "getArchivo_destino",
            codigo: codigo
        }
        $.ajax({
            type: "POST",
            url: 'controller/archivo_destinos.controller.php',
            data: parametros,
            success: function (data) {
                var datos = JSON.parse(data);
                $('#archivoUpdate').val(datos.archivo_id);
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

    $(".deleteArchivo_destino").click(function () {
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
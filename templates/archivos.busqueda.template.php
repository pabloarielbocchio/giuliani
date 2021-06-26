<table id="tabla" namefile="Archivos" totales="<?php echo $_SESSION["totales"]; ?>" registros="<?php echo $_SESSION['cant_reg']; ?>" pagina="<?php $_SESSION['pagina']; ?>" class="table table-striped table-hover" mes="<?php echo $mes; ?>" anio="<?php echo $anio; ?>" dia="<?php echo $dia; ?>" opcion="<?php echo $opcion; ?>"> 
    <thead>
        <tr class="row " style="background-color: transparent;">
            <th class="text-left ordena" orderby="descripcion" sentido="asc">Descripción</th>
            <th class="text-left ordena" orderby="ruta" sentido="asc">Ruta</th>
            <th class="text-left ordena" orderby="fecha_hora" sentido="asc">Fecha</th>
            <th class="text-left ordena" orderby="activo" sentido="asc">Activo</th>
            <th class="text-center noExl">Acciones</th>
        </tr>
    </thead>
    <tbody id="body">
        <?php foreach ($registros as $usu) { ?>
            <tr class="row" codigo="<?php echo $usu["codigo"]; ?>">
                <td class="text-left" style="vertical-align: middle;"><?php echo $usu["descripcion"]; ?></td>
                <td class="text-left" style="vertical-align: middle;"><?php echo $usu["ruta"]; ?></td>
                <td class="text-left" style="vertical-align: middle;"><?php echo $usu["fecha_hora"]; ?></td>
                <td class="text-left" style="vertical-align: middle;"><?php echo $usu["activo"]; ?></td>
                <td class="text-center noExl" style="vertical-align: middle;">
                    <div class="editArchivo" style="float: left; margin-left: 10px;"><a href="#"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></div>
                    <div class="deleteArchivo" style="float: right;margin-right: 10px;"><a href="#"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></div>
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

    $("#btn-eliminar-archivo").click(function () {
        if (!requestSent) {
            requestSent = true;
            var parametros = {
                funcion: "deleteArchivo",
                codigo: codigo
            }
            $.ajax({
                type: "POST",
                url: 'controller/archivos.controller.php',
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

    $(".editArchivo").click(function () {
        codigo = $(this).closest('tr').attr("codigo");
        $("#text-header-body").html("¿Desea eliminar el registro?");
        $("#btn-eliminar-archivo").css("display", "inline-block");
        $("#btn-cancelar").text("Cancelar");

        var parametros = {
            funcion: "getArchivo",
            codigo: codigo
        }
        $.ajax({
            type: "POST",
            url: 'controller/archivos.controller.php',
            data: parametros,
            success: function (data) {
                var datos = JSON.parse(data);
                $('#descripcionUpdate').val(datos.descripcion);
                $('#rutaUpdate').val(datos.ruta);
                $('#fechaUpdate').val(datos.fecha_hora);
                $('#activoUpdate').val(datos.activo);
                $('#prodaUpdate').val(datos.cod_prod_na);
                $('#prodbUpdate').val(datos.cod_prod_nb);
                $('#prodcUpdate').val(datos.cod_prod_nc);
                $('#proddUpdate').val(datos.cod_prod_nd);
                $('#prodeUpdate').val(datos.cod_prod_ne);
                $('#prodfUpdate').val(datos.cod_prod_nf);
                $('#prodpUpdate').val(datos.cod_prod_personalizado_id);
                $('#prodsUpdate').val(datos.cod_prod_estandar_id);
            },
            error: function () {
                alert("Error");
            }
        });
        //event.preventDefault();
        $('#dataUpdate').modal('show');
    });

    $(".deleteArchivo").click(function () {
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
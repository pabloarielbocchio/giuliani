<table id="tabla" namefile="Estados" totales="<?php echo $_SESSION["totales"]; ?>" registros="<?php echo $_SESSION['cant_reg']; ?>" pagina="<?php $_SESSION['pagina']; ?>" class="table table-striped table-hover" mes="<?php echo $mes; ?>" anio="<?php echo $anio; ?>" dia="<?php echo $dia; ?>" opcion="<?php echo $opcion; ?>"> 
    <thead>
        <tr class="row " style="background-color: transparent;">
            <th class="col-lg-7 col-md-7 col-sm-7 col-xs-7 text-left ordena" orderby="descripcion" sentido="asc">Descripción</th>
            <th class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center ordena" orderby="abrev" sentido="asc">Abrev.</th>
            <th class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-center ordena" orderby="abrev" sentido="asc">Observaciones</th>
            <th class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center noExl">Acciones</th>
        </tr>
    </thead>
    <tbody id="body">
        <?php foreach ($registros as $usu) { ?>
            <tr class="row" codigo="<?php echo $usu["codigo"]; ?>">
                <td class="col-lg-7 col-md-7 col-sm-7 col-xs-7 text-left" style="vertical-align: middle;"><?php echo $usu["descripcion"]; ?></td>
                <td class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center" style="vertical-align: middle;"><?php echo $usu["abrev"]; ?></td>
                <td class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-center" style="vertical-align: middle;">
                    <?php 
                        switch($usu["codigo"]){
                            case 1:
                                echo "Estado inicial del proceso";
                                break;
                            case 2:
                                echo "Se comienza con la produccion";
                                break;
                            case 3:
                                echo "Produccion terminada";
                                break;
                            case 4:
                                echo "Produccion cancelada/anulada";
                                break;
                            case 5:
                                echo "El producto se encuentra en fábrica";
                                break;
                        }
                    ?>
                </td>
                <td class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center noExl" style="vertical-align: middle;">
                    <div class="editEstado" style="text-align: center;"><a href="#"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></div>
                    <!-- <div class="deleteEstado" style="float: right;margin-right: 10px;"><a href="#"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></div> -->
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

    $("#btn-eliminar-estado").click(function () {
        if (!requestSent) {
            requestSent = true;
            var parametros = {
                funcion: "deleteEstado",
                codigo: codigo
            }
            $.ajax({
                type: "POST",
                url: 'controller/estados.controller.php',
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

    $(".editEstado").click(function () {
        codigo = $(this).closest('tr').attr("codigo");
        $("#text-header-body").html("¿Desea eliminar el registro?");
        $("#btn-eliminar-estado").css("display", "inline-block");
        $("#btn-cancelar").text("Cancelar");

        var parametros = {
            funcion: "getEstado",
            codigo: codigo
        }
        $.ajax({
            type: "POST",
            url: 'controller/estados.controller.php',
            data: parametros,
            success: function (data) {
                var datos = JSON.parse(data);
                $('#descripcionUpdate').val(datos.descripcion);
                $('#abreviaturaUpdate').val(datos.abrev);
            },
            error: function () {
                alert("Error");
            }
        });
        //event.preventDefault();
        $('#dataUpdate').modal('show');
    });

    $(".deleteEstado").click(function () {
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
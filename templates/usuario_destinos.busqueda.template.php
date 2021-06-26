<style>
.select2-close-mask{
    z-index: 2099;
}
.select2-dropdown{
    z-index: 3051;
}    
</style>
<table id="tabla" namefile="Usuario_destinos" totales="<?php echo $_SESSION["totales"]; ?>" registros="<?php echo $_SESSION['cant_reg']; ?>" pagina="<?php $_SESSION['pagina']; ?>" class="table table-striped table-hover" mes="<?php echo $mes; ?>" anio="<?php echo $anio; ?>" dia="<?php echo $dia; ?>" opcion="<?php echo $opcion; ?>"> 
    <thead>
        <tr class="row " style="background-color: transparent;">
            <th class="col-lg-5 col-md-5 col-sm-5 col-xs-5 text-left " orderby="usuario_id" sentido="asc">Usuario</th>
            <th class="col-lg-5 col-md-5 col-sm-5 col-xs-5 text-left " orderby="destino_id" sentido="asc">Destino</th>
            <th class="col-lg-2 col-md-2 col-sm-2 col-xs-2 text-center noExl">Acciones</th>
        </tr>
    </thead>
    <tbody id="body">
        <?php foreach ($registros as $usu) { ?>
            <tr class="row" codigo="<?php echo $usu["codigo"]; ?>">
                <td class="col-lg-5 col-md-5 col-sm-5 col-xs-5 text-left" style="vertical-align: middle;">
                    <?php 
                        foreach ($usuarios as $r){
                            if ($r["codigo"] == $usu["usuario_id"]){
                                echo $r["descripcion"];
                            }
                        }
                    ?>
                </td><td class="col-lg-5 col-md-5 col-sm-5 col-xs-5 text-left" style="vertical-align: middle;">
                    <?php 
                        foreach ($destinos as $r){
                            if ($r["codigo"] == $usu["destino_id"]){
                                echo $r["descripcion"];
                            }
                        }
                    ?>
                </td>
                <td class="col-lg-2 col-md-2 col-sm-2 col-xs-2 text-center noExl" style="vertical-align: middle;">
                    <?php if ($_SESSION["permisos"][$_SESSION['menu']]["edit"]) { ?>
                        <div class="editUsuario_destino" style="float: left; margin-left: 10px;"><a href="#"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></div>
                    <?php  } ?>
                    <?php if ($_SESSION["permisos"][$_SESSION['menu']]["eliminar"]) { ?>
                        <div class="deleteUsuario_destino" style="float: right;margin-right: 10px;"><a href="#"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></div>
                    <?php  } ?>
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
        if ($_SESSION["cant_reg"] == -2) {
            echo $_SESSION["totales"];
        } else {
            echo $_SESSION["pagina"] * $_SESSION["cant_reg"];
        }
        ?> de <?php echo $_SESSION["totales"]; ?></label>
</div>

<script>
    var requestSent = false;

    $("#btn-eliminar-usuario_destino").click(function () {
        if (!requestSent) {
            requestSent = true;
            var parametros = {
                funcion: "deleteUsuario_destino",
                codigo: codigo
            }
            $.ajax({
                type: "POST",
                url: 'controller/usuario_destinos.controller.php',
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

    $(".editUsuario_destino").click(function () {
        codigo = $(this).closest('tr').attr("codigo");
        $("#text-header-body").html("¿Desea eliminar el registro?");
        $("#btn-eliminar-usuario_destino").css("display", "inline-block");
        $("#btn-cancelar").text("Cancelar");

        var parametros = {
            funcion: "getUsuario_destino",
            codigo: codigo
        }
        $.ajax({
            type: "POST",
            url: 'controller/usuario_destinos.controller.php',
            data: parametros,
            success: function (data) {
                var datos = JSON.parse(data);
                $('#usuarioUpdate').val(datos.usuario_id);
                $('#destinoUpdate').val(datos.destino_id);
            },
            error: function () {
                alert("Error");
            }
        });
        //event.preventDefault();
        $('#dataUpdate').modal('show');
    });

    $(".deleteUsuario_destino").click(function () {
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
</script>

<script>
    $("tbody > tr").click(function () {
        $("tbody > tr").css("background-color", "");
        $(this).css("background-color", "#FFFFB8");
    });
</script>
<style>
.select2-close-mask{
    z-index: 2099;
}
.select2-dropdown{
    z-index: 3051;
}    
</style>
<table id="tabla" namefile="Usuarios" totales="<?php echo $_SESSION["totales"]; ?>" registros="<?php echo $_SESSION['cant_reg']; ?>" pagina="<?php $_SESSION['pagina']; ?>" class="table table-striped table-hover" mes="<?php echo $mes; ?>" anio="<?php echo $anio; ?>" dia="<?php echo $dia; ?>" opcion="<?php echo $opcion; ?>"> 
    <thead>
        <tr class="row " style="background-color: transparent;">
            <th class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center ordena" orderby="id" sentido="asc">C&oacute;digo</th>
            <th class="col-lg-2 col-md-2 col-sm-2 col-xs-2 text-center " orderby="password" sentido="asc">Password</th>
            <th class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-left " orderby="password" sentido="asc">Nombre</th>
            <th class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-left " orderby="mail" sentido="asc">Mail</th>
            <th class="col-lg-2 col-md-2 col-sm-2 col-xs-2 text-left " orderby="password" sentido="asc">Rol</th>
            <th class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center noExl">Acciones</th>
        </tr>
    </thead>
    <tbody id="body">
        <?php foreach ($registros as $usu) { ?>
            <tr class="row" codigo="<?php echo $usu["codigo"]; ?>">
                <td class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center" style="vertical-align: middle;"><?php echo $usu["codigo"]; ?></td>
                <td class="col-lg-2 col-md-2 col-sm-2 col-xs-2 text-center" style="vertical-align: middle;"><?php echo $usu["password"]; ?></td>
                <td class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-left" style="vertical-align: middle;"><?php echo $usu["nombre"]; ?></td>
                <td class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-left" style="vertical-align: middle;"><?php echo $usu["mail"]; ?></td>
                <td class="col-lg-2 col-md-2 col-sm-2 col-xs-2 text-left" style="vertical-align: middle;">
                    <?php 
                        foreach ($roles as $r){
                            if ($r["codigo"] == $usu["id_rol"]){
                                echo $r["descripcion"];
                            }
                        }
                    ?>
                </td>
                <td class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center noExl" style="vertical-align: middle;">
                    <?php if ($usu["usuario"] != 'pbocchio') { ?>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-primary dropdown-toggle nuevo" id="menu" type="button" data-toggle="dropdown"  style="font-size: 10px;height: 15px;">
                                <div class="opciones" style="margin-top: -6px">Opciones <span class="caret"></span></div>
                            </button>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="menu">
                                <li role="presentation" class="editUsuario"><a role="menuitem" tabindex="-1" href="#">Editar</a></li>
                                <li role="presentation" class="destinoUsuario"><a role="menuitem" tabindex="-1" href="#">Destinos</a></li>
                                <li class="divider"></li>
                                <li role="presentation" class="deleteUsuario"><a role="menuitem" tabindex="-1" href="#">Eliminar</a></li>
                            </ul>
                        </div>
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
        if ($_SESSION["cant_reg"] == -1) {
            echo $_SESSION["totales"];
        } else {
            echo $_SESSION["pagina"] * $_SESSION["cant_reg"];
        }
        ?> de <?php echo $_SESSION["totales"]; ?></label>
</div>

<script>
    var requestSent = false;

    $("#btn-eliminar-usuario").click(function () {
        if (!requestSent) {
            requestSent = true;
            var parametros = {
                funcion: "deleteUsuario",
                codigo: codigo
            }
            $.ajax({
                type: "POST",
                url: 'controller/usuarios.controller.php',
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

    $(".destinoUsuario").click(function () {
        codigo = $(this).closest('tr').attr("codigo");
        window.location.href = "usuarios_destinos.php?cod_usuario="+codigo
    });

    $(".editUsuario").click(function () {
        codigo = $(this).closest('tr').attr("codigo");
        $("#text-header-body").html("¿Desea eliminar el registro?");
        $("#btn-eliminar-usuario").css("display", "inline-block");
        $("#btn-cancelar").text("Cancelar");

        var parametros = {
            funcion: "getUsuario",
            codigo: codigo
        }
        $.ajax({
            type: "POST",
            url: 'controller/usuarios.controller.php',
            data: parametros,
            success: function (data) {
                var datos = JSON.parse(data);
                $('#rolUpdate').val(datos.id_rol);
                $('#nombreUpdate').val(datos.name);
                $('#apellidoUpdate').val(datos.surname);
                $('#usuarioUpdate').val(datos.usuario);
                $('#mailUpdate').val(datos.mail);
                $('#passwordUpdate').val("");
                //$('#sucursalUpdate').val(datos.sucursales);
                //$('#sucursalUpdate').select2();
            },
            error: function () {
                alert("Error");
            }
        });
        //event.preventDefault();
        $('#dataUpdate').modal('show');
    });

    $(".deleteUsuario").click(function () {
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
<table id="tabla" namefile="Roles" totales="<?php echo $_SESSION["totales"]; ?>" registros="<?php echo $_SESSION['cant_reg']; ?>" pagina="<?php $_SESSION['pagina']; ?>" class="table table-striped table-hover" mes="<?php echo $mes; ?>" anio="<?php echo $anio; ?>" dia="<?php echo $dia; ?>" opcion="<?php echo $opcion; ?>"> 
    <thead>
        <tr class="row " style="background-color: transparent;">
            <th class=" text-left ordena" orderby="descripcion" sentido="asc">Descripción</th>
            <th class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center " orderby="descripcion" sentido="asc">Administrador</th>
            <th class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center " orderby="descripcion" sentido="asc">Cambiar Estado OT</th>
            <th class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center " orderby="descripcion" sentido="asc">Finalizar OT</th>
            <th class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center " orderby="descripcion" sentido="asc">Editar OT</th>
            <th class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center " orderby="descripcion" sentido="asc">Archivos OT Prod.</th>
            <th class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center " orderby="descripcion" sentido="asc">Eliminar OT Prod.</th>
            <th class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center " orderby="descripcion" sentido="asc">Ver todos los archivos</th>
            <th class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center noExl">Acciones</th>
        </tr>
    </thead>
    <tbody id="body">
        <?php foreach ($registros as $usu) { ?>
            <tr class="row" codigo="<?php echo $usu["codigo"]; ?>">
                <td class=" text-left" style="vertical-align: middle;"><?php echo $usu["descripcion"]; ?></td>
                <td class=" text-center" style="vertical-align: middle;">
                    <?php
                        if ($usu["administrador"] > 0) {
                            echo '<span class="glyphicon glyphicon-ok" style="color: #0A0; cursor: pointer;" aria-hidden="true"></span>';
                        } else {
                            echo '<span class="glyphicon glyphicon-remove" style="color: #A00; cursor: pointer;" aria-hidden="true"></span>';
                        }
                    ?>
                </td>
                <td class=" text-center" style="vertical-align: middle;">
                    <?php
                        if ($usu["estado_ot"] > 0) {
                            echo '<span class="glyphicon glyphicon-ok" style="color: #0A0; cursor: pointer;" aria-hidden="true"></span>';
                        } else {
                            echo '<span class="glyphicon glyphicon-remove" style="color: #A00; cursor: pointer;" aria-hidden="true"></span>';
                        }
                    ?>
                </td>
                <td class=" text-center" style="vertical-align: middle;">
                    <?php
                        if ($usu["finalizar_ot"] > 0) {
                            echo '<span class="glyphicon glyphicon-ok" style="color: #0A0; cursor: pointer;" aria-hidden="true"></span>';
                        } else {
                            echo '<span class="glyphicon glyphicon-remove" style="color: #A00; cursor: pointer;" aria-hidden="true"></span>';
                        }
                    ?>
                </td>
                <td class=" text-center" style="vertical-align: middle;">
                    <?php
                        if ($usu["editar_ot"] > 0) {
                            echo '<span class="glyphicon glyphicon-ok" style="color: #0A0; cursor: pointer;" aria-hidden="true"></span>';
                        } else {
                            echo '<span class="glyphicon glyphicon-remove" style="color: #A00; cursor: pointer;" aria-hidden="true"></span>';
                        }
                    ?>
                </td>
                <td class=" text-center" style="vertical-align: middle;">
                    <?php
                        if ($usu["editar_files_otp"] > 0) {
                            echo '<span class="glyphicon glyphicon-ok" style="color: #0A0; cursor: pointer;" aria-hidden="true"></span>';
                        } else {
                            echo '<span class="glyphicon glyphicon-remove" style="color: #A00; cursor: pointer;" aria-hidden="true"></span>';
                        }
                    ?>
                </td>
                <td class=" text-center" style="vertical-align: middle;">
                    <?php
                        if ($usu["delete_otp"] > 0) {
                            echo '<span class="glyphicon glyphicon-ok" style="color: #0A0; cursor: pointer;" aria-hidden="true"></span>';
                        } else {
                            echo '<span class="glyphicon glyphicon-remove" style="color: #A00; cursor: pointer;" aria-hidden="true"></span>';
                        }
                    ?>
                </td>
                <td class=" text-center" style="vertical-align: middle;">
                    <?php
                        if ($usu["view_all_files"] > 0) {
                            echo '<span class="glyphicon glyphicon-ok" style="color: #0A0; cursor: pointer;" aria-hidden="true"></span>';
                        } else {
                            echo '<span class="glyphicon glyphicon-remove" style="color: #A00; cursor: pointer;" aria-hidden="true"></span>';
                        }
                    ?>
                </td>
                <td class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center noExl" style="vertical-align: middle;">
                    <?php if ($usu["codigo"] > 1) { ?>
                        <div class="editRol" style="float: left; margin-left: 10px;"><a href="#"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></div>
                        <div class="deleteRol" style="float: right;margin-right: 10px;"><a href="#"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></div>
                    <?php } ?>
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

    $("#btn-eliminar-rol").click(function () {
        if (!requestSent) {
            requestSent = true;
            var parametros = {
                funcion: "deleteRol",
                codigo: codigo
            }
            $.ajax({
                type: "POST",
                url: 'controller/roles.controller.php',
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

    $(".editRol").click(function () {
        codigo = $(this).closest('tr').attr("codigo");
        $("#text-header-body").html("¿Desea eliminar el registro?");
        $("#btn-eliminar-rol").css("display", "inline-block");
        $("#btn-cancelar").text("Cancelar");

        var parametros = {
            funcion: "getRol",
            codigo: codigo
        }
        $.ajax({
            type: "POST",
            url: 'controller/roles.controller.php',
            data: parametros,
            success: function (data) {
                var datos = JSON.parse(data);
                $('#descripcionUpdate').val(datos.descripcion);
                $('#administrador').val(datos.administrador);
                $('#estado_ot').val(datos.estado_ot);
                $('#finalizar_ot').val(datos.finalizar_ot);
                $('#editar_ot').val(datos.editar_ot);
                $('#editar_files_otp').val(datos.editar_files_otp);
                $('#delete_otp').val(datos.delete_otp);
                $('#view_all_files').val(datos.view_all_files);
                $('#abreviaturaUpdate').val(datos.descrip_abrev);
            },
            error: function () {
                alert("Error");
            }
        });
        //event.preventDefault();
        $('#dataUpdate').modal('show');
    });

    $(".deleteRol").click(function () {
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
<table id="tabla" namefile="Menus" totales="<?php echo $_SESSION["totales"]; ?>" registros="<?php echo $_SESSION['cant_reg']; ?>" pagina="<?php $_SESSION['pagina']; ?>" class="table table-striped table-hover" mes="<?php echo $mes; ?>" anio="<?php echo $anio; ?>" dia="<?php echo $dia; ?>" opcion="<?php echo $opcion; ?>"> 
    <thead>
        <tr class="row " style="background-color: transparent;">
            <th class="col-lg-11 col-md-11 col-sm-11 col-xs-11 text-left ordena" orderby="descripcion" sentido="asc">Descripción</th>
            <th class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center noExl">Acciones</th>
        </tr>
    </thead>
    <tbody id="body">
        <?php 
            foreach ($menu as $m) {
                if ($m[2] == 0){ 
        ?>
                    <tr class="row" codigo="<?php echo $m[5]; ?>" fecha_modif="<?php echo $usu["fecha_modif"]; ?>" nivel="1">
                        <td class="col-lg-11 col-md-11 col-sm-11 col-xs-11 text-left" style="vertical-align: middle; padding-left: 0px;"><?php echo '<i class="fa ' . $m[3] . '"></i> ' .  $m[0]; ?></td>
                        <td class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center noExl" style="vertical-align: middle;">
                            <?php if ($_SESSION["permisos"][$_SESSION['menu']]["edit"]) { ?>
                                <div class="editMenu" style="float: left; margin-left: 10px;"><a href="#"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></div>
                            <?php  } ?>
                            <?php if ($_SESSION["permisos"][$_SESSION['menu']]["eliminar"]) { ?>
                                <div class="deleteMenu" style="float: right;margin-right: 10px;"><a href="#"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></div>
                            <?php  } ?>    
                        </td>
                    </tr>
        <?php   } ?>
        <?php if ($m[2] > 0){ ?>
                    <tr class="row" codigo="<?php echo $m[5]; ?>" fecha_modif="<?php echo $usu["fecha_modif"]; ?>" nivel="1">
                        <td class="col-lg-11 col-md-11 col-sm-11 col-xs-11 text-left" style="vertical-align: middle; padding-left: 0px;"><?php echo '<i class="fa ' . $m[3] . '"></i> ' . $m[0] . " [+] "; ?></td>
                        <td class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center noExl" style="vertical-align: middle;">
                            <?php if ($_SESSION["permisos"][$_SESSION['menu']]["edit"]) { ?>
                                <div class="editMenu" style="float: left; margin-left: 10px;"><a href="#"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></div>
                            <?php  } ?>
                            <?php if ($_SESSION["permisos"][$_SESSION['menu']]["eliminar"]) { ?>
                                <div class="deleteMenu" style="float: right;margin-right: 10px;"><a href="#"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></div>
                            <?php  } ?>    
                        </td>
                    </tr>
        <?php foreach ($menu as $men) {
                if ($men[2] * -1 == $m[2]){
                        if ($men[4] > 0){ ?>
                    <tr class="row" codigo="<?php echo $men[5]; ?>" fecha_modif="<?php echo $usu["fecha_modif"]; ?>" nivel="2">
                        <td class="col-lg-11 col-md-11 col-sm-11 col-xs-11 text-left" style="vertical-align: middle; padding-left: 50px;"><?php echo $men[0] . " [+] "; ?></td>
                        <td class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center noExl" style="vertical-align: middle;">
                            <?php if ($_SESSION["permisos"][$_SESSION['menu']]["edit"]) { ?>
                                <div class="editMenu" style="float: left; margin-left: 10px;"><a href="#"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></div>
                            <?php  } ?>
                            <?php if ($_SESSION["permisos"][$_SESSION['menu']]["eliminar"]) { ?>
                                <div class="deleteMenu" style="float: right;margin-right: 10px;"><a href="#"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></div>
                            <?php  } ?>    
                        </td>
                    </tr>
        <?php           foreach ($menu as $_m) {
                            if ($men[4] * -1 == $_m[4]){ ?>
                                <tr class="row" codigo="<?php echo $_m[5]; ?>" fecha_modif="<?php echo $usu["fecha_modif"]; ?>" nivel="3">
                                    <td class="col-lg-11 col-md-11 col-sm-11 col-xs-11 text-left" style="vertical-align: middle; padding-left: 100px;"><?php echo $_m[0]; ?></td>
                                    <td class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center noExl" style="vertical-align: middle;">
                                        <?php if ($_SESSION["permisos"][$_SESSION['menu']]["edit"]) { ?>
                                            <div class="editMenu" style="float: left; margin-left: 10px;"><a href="#"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></div>
                                        <?php  } ?>
                                        <?php if ($_SESSION["permisos"][$_SESSION['menu']]["eliminar"]) { ?>
                                            <div class="deleteMenu" style="float: right;margin-right: 10px;"><a href="#"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></div>
                                        <?php  } ?>    
                                    </td>
                                </tr>
        <?php               }
                        }    
                    } elseif ($men[4] < 0){

                    } else { ?>
                        <tr class="row" codigo="<?php echo $men[5]; ?>" fecha_modif="<?php echo $usu["fecha_modif"]; ?>" nivel="2">
                            <td class="col-lg-11 col-md-11 col-sm-11 col-xs-11 text-left" style="vertical-align: middle; padding-left: 50px;"><?php echo $men[0]; ?></td>
                            <td class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center noExl" style="vertical-align: middle;">
                                <?php if ($_SESSION["permisos"][$_SESSION['menu']]["edit"]) { ?>
                                    <div class="editMenu" style="float: left; margin-left: 10px;"><a href="#"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></div>
                                <?php  } ?>
                                <?php if ($_SESSION["permisos"][$_SESSION['menu']]["eliminar"]) { ?>
                                    <div class="deleteMenu" style="float: right;margin-right: 10px;"><a href="#"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></div>
                                <?php  } ?>    
                            </td>
                        </tr>
        <?php       }
                }
            }
        }
    ?>
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

    $("#btn-eliminar-menu").click(function () {
        if (!requestSent) {
            requestSent = true;
            var parametros = {
                funcion: "deleteMenus",
                codigo: codigo
            }
            $.ajax({
                type: "POST",
                url: 'controller/menus.controller.php',
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

    $(".editMenu").click(function () {
        codigo = $(this).closest('tr').attr("codigo");
        var nivel = $(this).closest('tr').attr("nivel");
        $("#text-header-body").html("¿Desea eliminar el registro?");
        $("#btn-eliminar-menu").css("display", "inline-block");
        $("#btn-cancelar").text("Cancelar");

        var parametros = {
            funcion: "getMenus",
            codigo: codigo
        }
        $.ajax({
            type: "POST",
            url: 'controller/menus.controller.php',
            data: parametros,
            success: function (data) {
                var datos = JSON.parse(data);
                $('#nivelUpdate').val(nivel);
                $("#nivelUpdate").change();
                $('#descripcionUpdate').val(datos.nombre);
                $('#visibleUpdate').val(datos.visible);
                $('#destinoUpdate').val(datos.destino);
                $('#iconoUpdate').val(datos.icono);
                $('#nivelesUpdate').val(datos.nivel * -1);
                $("#nivelesUpdate").change();
                $('#subnivelesUpdate').val(datos.subnivel * -1);
                $("#subnivelesUpdate").change();
                $('#ordenUpdate').val(datos.despues_de);
            },
            error: function () {
                alert("Error");
            }
        });
        //event.preventDefault();
        $('#dataUpdate').modal('show');
    });

    $(".deleteMenu").click(function () {
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
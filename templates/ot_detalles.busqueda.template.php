<table id="tabla" cantidad_total="<?php echo count($registros); ?>" finalizada="<?php echo intval($ot_header["finalizada"]); ?>" namefile="Ot_detalles" totales="<?php echo $_SESSION["totales"]; ?>" registros="<?php echo $_SESSION['cant_reg']; ?>" pagina="<?php $_SESSION['pagina']; ?>" class="table table-striped table-hover" mes="<?php echo $mes; ?>" anio="<?php echo $anio; ?>" dia="<?php echo $dia; ?>" opcion="<?php echo $opcion; ?>"> 
    <thead>
        <tr class="row " style="background-color: transparent;">
            <th class="text-center ordena" orderby="orden_trabajo_id" sentido="asc"></th>
            <th class="text-center ordena" orderby="orden_trabajo_id" sentido="asc"></th>
            <th class="text-center ordena" orderby="cantidad" sentido="asc">Cantidad</th>
            <th class="text-left ordena" orderby="observaciones" sentido="asc">Observaciones</th>
            <th class="text-center noExl">Acciones</th>
        </tr>
    </thead>
    <tbody id="body">       
        <?php foreach ($secciones as $secc) { ?>
            <tr class="row seccion" codigo="<?php echo $secc["codigo"]; ?>">
                <td class="text-left  " style="vertical-align: middle; font-weight: bolder;"><?php echo "SECCION: " . $secc["descripcion"]; ?></td>
                <td class="text-center" style="vertical-align: middle;"></td>   
                <td class="text-center" style="vertical-align: middle;"></td>   
                <td class="text-center" style="vertical-align: middle;"></td>   
                <td class="text-center" style="vertical-align: middle;"></td>   
            </tr>             
            <?php foreach ($secc["sectores"] as $sect) { ?>  
                <tr class="row sector" codigo="<?php echo $sect["codigo"]; ?>">              
                    <td class="text-left  " style="vertical-align: middle; font-weight: bolder; padding-left: 25px;"><?php echo "SECTOR: " . $sect["descripcion"]; ?></td>
                    <td class="text-center" style="vertical-align: middle;"></td>   
                    <td class="text-center" style="vertical-align: middle;"></td>   
                    <td class="text-center" style="vertical-align: middle;"></td>   
                    <td class="text-center" style="vertical-align: middle;"></td>       
                </tr>   
                <?php foreach ($sect["registros"] as $usu) { ?>
                    <tr class="row" 
                        codigo="<?php echo $usu["codigo"]; ?>"
                        cantidad="<?php echo $usu["cantidad"]; ?>"
                        item_vendido="<?php echo $usu["item_vendido"]; ?>"
                    >
                        <!--<td class="text-center verprod" oculto="1" style="vertical-align: middle; cursor: pointer; font-weight: bolder;">[+]</td>-->
                        <td class="text-left  " style="vertical-align: middle; font-weight: bolder; padding-left: 50px;"><?php echo $usu["item_vendido"]; ?></td>
                        <td class="text-center" style="vertical-align: middle;"><?php echo ""; ?></td>
                        <td class="text-center" style="vertical-align: middle;"><?php echo $usu["cantidad"]; ?></td>
                        <td class="text-left" style="vertical-align: middle;"><?php echo $usu["observaciones"]; ?></td>
                        <td class="text-center noExl" style="vertical-align: middle;">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-primary dropdown-toggle nuevo" id="menu" type="button" data-toggle="dropdown"  style="font-size: 10px;height: 15px;">
                                    <div class="opciones" style="margin-top: -6px">Opciones <span class="caret"></span></div>
                                </button>
                                <ul class="dropdown-menu" role="menu" aria-labelledby="menu">
                                    <?php if (in_array($_SESSION["rol"], [1,2,5]) and intval($ot_header["finalizada"]) == 0) { ?>
                                        <li role="presentation" class="editOt_detalle"><a role="menuitem" tabindex="-1" href="#">Editar</a></li>
                                        <li role="presentation" class="prodestandar_detalle"><a role="menuitem" tabindex="-1" href="#">Prod. Estandar</a></li>
                                        <li role="presentation" class="prodperso_detalle"><a role="menuitem" tabindex="-1" href="#">Prod. Personalizado</a></li>
                                        
                                        <?php if (in_array($_SESSION["rol"], [1,2,5]) and intval($ot_header["finalizada"]) == 0) { ?>
                                            <li role="presentation" class="editprodot_archivo"><a role="menuitem" tabindex="-1" href="#">Archivos</a></li>   
                                        <?php } else { ?>
                                            <li role="presentation" class="viewprodot_archivo"><a role="menuitem" tabindex="-1" href="#">Archivos</a></li>
                                        <?php }  ?>

                                        <li class="divider"></li>
                                        <li role="presentation" class="deleteOt_detalle"><a role="menuitem" tabindex="-1" href="#">Eliminar</a></li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </td>

                        <?php 
                            foreach ($prods as $prod) { 
                                if ($prod["ot_detalle_id"] != $usu["codigo"]){
                                    continue;
                                }
                                $prod = $this->formatNumber($prod);
                        ?>
                            <tr class="row otdetprod" cod_det="<?php echo $usu["codigo"]; ?>" codigo="<?php echo $prod["codigo"]; ?>" >
                                <!--<td class="text-center" style="vertical-align: middle;"></td>-->
                                <td class="text-left" style="vertical-align: middle; padding-left: 75px;">
                                    <?php 
                                        if ($prod["standar"] == 1) {
                                            echo $prod["numero"] . " - " . $prod["prod_standar"];
                                        } else {
                                            echo $prod["numero"] . " - " . $prod["prod_personalizado"];
                                        }
                                    ?>
                                </td>
                                <td class="text-center" style="vertical-align: middle; padding-left: 75px;">
                                    <?php 
                                        if ($prod["standar"] == 1){
                                            echo '<span" class="label label-danger m-t-lg" style="width: 100%; background-color: #fdb25d; color: #eee;">STANDARD</span>';
                                        } else {
                                            echo '<span" class="label label-danger m-t-lg" style="width: 100%; background-color: #fe795f; color: #eee;">CUSTOM</span>';
                                        }
                                    ?>
                                </td>
                                <td class="text-center" style="vertical-align: middle;"><?php echo $prod["cantidad"]; ?></td>
                                <td class="text-left" style="vertical-align: middle;"><?php echo $prod["observaciones"]; ?></td>
                                <td class="text-center noExl" style="vertical-align: middle;">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-primary dropdown-toggle nuevo" id="menu" type="button" data-toggle="dropdown"  style="font-size: 10px;height: 15px;">
                                            <div class="opciones" style="margin-top: -6px">Opciones <span class="caret"></span></div>
                                        </button>
                                        <ul class="dropdown-menu" role="menu" aria-labelledby="menu">
                                            <!--<li role="presentation" class="editprod_detalle"><a role="menuitem" tabindex="-1" href="#">Editar</a></li>
                                            <li class="divider"></li>-->
                                            <?php if ($prod["standar"] == 1){?>
                                                <li role="presentation" class="configprod_archivo"><a role="menuitem" tabindex="-1" href="#">Configuracion</a></li>
                                            
                                            <?php } ?>
                                            <?php if (in_array($_SESSION["rol"], [1,2,5]) and intval($ot_header["finalizada"]) == 0) { ?>
                                                <li role="presentation" class="editprod_archivo"><a role="menuitem" tabindex="-1" href="#">Archivos</a></li>   
                                            <?php } else { ?>
                                                <li role="presentation" class="viewprod_archivo"><a role="menuitem" tabindex="-1" href="#">Archivos</a></li>
                                            <?php }  ?>
                                            
                                            <?php if (in_array($_SESSION["rol"], [1,2,5]) and intval($ot_header["finalizada"]) == 0) { ?>
                                                <li role="presentation" class="deleteprod_detalle"><a role="menuitem" tabindex="-1" href="#">Eliminar</a></li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    </tr>
                <?php } ?>
            <?php } ?>
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
                $('#seccionUpdate').val(datos.seccion);
                $('#sectorUpdate').val(datos.sector);
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
        var cantidad = $(this).closest('tr').attr("cantidad");
        var item_vendido = $(this).closest('tr').attr("item_vendido");
        $("#cantidadpersoAdd").val(cantidad);
        $("#prodpersoAdd").val(item_vendido);
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
    
    $(".editprodot_archivo").click(function () {
        codigo = $(this).closest('tr').attr("codigo");
        window.location.href = "files_otd.php?opc="+codigo;
    });
    
    $(".viewprodot_archivo").click(function () {
        codigo = $(this).closest('tr').attr("codigo");
        window.location.href = "files_otd.php?readonly=1&opc="+codigo;
    });
    
    $(".editprod_archivo").click(function () {
        codigo = $(this).closest('tr').attr("codigo");
        window.location.href = "files_otp.php?opc="+codigo;
    });
    
    $(".viewprod_archivo").click(function () {
        codigo = $(this).closest('tr').attr("codigo");
        window.location.href = "files_otp.php?readonly=1&opc="+codigo;
    });
    
    $(".configprod_archivo").click(function () {
        codigo = $(this).closest('tr').attr("codigo");
        window.location.href = "ot_detalle_standar_view.php?opc="+codigo;
    });

    var finalizada = $("#tabla").attr("finalizada");
    $(".div_add").css("display", "none");
    if (finalizada == 0){
        $(".div_add").css("display", "block");
    }

    var cantidad_total = $("#tabla").attr("cantidad_total");
    if (cantidad_total == 0){
        $(".barra_descarga").removeClass("hidden");
    } else {        
        $(".barra_descarga").addClass("hidden");
    }
</script>
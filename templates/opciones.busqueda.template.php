<table id="tabla" namefile="Productos" totales="<?php echo $_SESSION["totales"]; ?>" registros="<?php echo $_SESSION['cant_reg']; ?>" pagina="<?php $_SESSION['pagina']; ?>" class="table table-striped table-hover" mes="<?php echo $mes; ?>" anio="<?php echo $anio; ?>" dia="<?php echo $dia; ?>" opcion="<?php echo $opcion; ?>"> 
    <thead>
        <tr class="row " style="background-color: transparent;">
            <th class="col-lg-10 col-md-10 col-sm-10 col-xs-10 text-left ordena" orderby="descripcion" sentido="asc">Descripción</th>
            <th class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center ordena" orderby="ing_estado" sentido="asc">Estado</th>
            <th class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center noExl">Acciones</th>
        </tr>
    </thead>
    <tbody id="body">
        <?php foreach ($registros as $usu) { ?>
            <tr class="row" codigo="<?php echo $usu["codigo"]; ?>">
                <td class="col-lg-10 col-md-10 col-sm-10 col-xs-10 text-left" style="vertical-align: middle;"><?php echo $usu["descripcion"]; ?></td>
                <td class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-left" style="vertical-align: middle;">
                    <?php 
                        //echo $usu["ing_estado"]; 
                    ?>
                </td>
                <td class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center noExl" style="vertical-align: middle;">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-primary dropdown-toggle nuevo" id="menu" type="button" data-toggle="dropdown"  style="font-size: 10px;height: 15px;">
                            <div class="opciones" style="margin-top: -6px">Opciones <span class="caret"></span></div>
                        </button>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="menu">
                            <li role="presentation" class="nuevoProducto"><a role="menuitem" tabindex="-1" href="#">Nueva Alt.</a></li>
                            <li role="presentation" class="editProducto"><a role="menuitem" tabindex="-1" href="#">Editar</a></li>
                            <li class="divider"></li>
                            <li role="presentation" class="deleteProducto"><a role="menuitem" tabindex="-1" href="#">Eliminar</a></li>
                        </ul>
                    </div>
                </td>
            </tr>
            <?php 
                foreach ($prod_f as $pf) { 
                    if ($pf["cod_prod_ne"] != $usu["codigo"]){
                        continue;
                    }
            ?>
                <tr class="row" codigo="<?php echo $pf["codigo"]; ?>" codigo_opc="<?php echo $usu["codigo"]; ?>">
                    <td class="col-lg-10 col-md-10 col-sm-10 col-xs-10 text-left" style="vertical-align: middle; ">
                        <span style="margin-left: 25px;">
                            <?php echo $pf["descripcion"]; ?>
                        </span>
                    </td>
                    <td class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center" style="vertical-align: middle;">
                        <?php 
                            switch (intval($pf["ing_estado"])){
                                case -1:
                                    echo '<span style="cursor: pointer;" estado="-1" codigo="'.$pf["codigo"].'" class="estado_editable label label-danger m-t-lg">Necesita Correccion</span>';
                                    break;
                                case 0:
                                    echo'<span style="cursor: pointer;" estado="0" codigo="'.$pf["codigo"].'" class="estado_editable label label-warning m-t-lg" >Sin Desarrollar</span>';
                                    break;
                                case 1:
                                    echo '<span style="cursor: pointer;" estado="1"  codigo="'.$pf["codigo"].'" class="estado_editable label label-info m-t-lg">En Desarrollo</span>';
                                    break;
                                case 2:
                                    echo '<span style="cursor: pointer;" estado="2"  codigo="'.$pf["codigo"].'" class="estado_editable label label-success m-t-lg">Aprobado</span>';
                                    break;
                            }
                        ?>
                    </td>
                    <td class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center noExl" style="vertical-align: middle;">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-primary dropdown-toggle nuevo" id="menu" type="button" data-toggle="dropdown"  style="font-size: 10px;height: 15px;">
                                <div class="opciones" style="margin-top: -6px">Opciones <span class="caret"></span></div>
                            </button>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="menu">
                                <li role="presentation" class="editAlternativa"><a role="menuitem" tabindex="-1" href="#">Editar</a></li>
                                <li role="presentation" class="archivosProducto"><a role="menuitem" tabindex="-1" href="#">Archivos</a></li>
                                <li class="divider"></li>
                                <li role="presentation" class="deleteAlternativa"><a role="menuitem" tabindex="-1" href="#">Eliminar</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
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

    $("#btn-eliminar-producto").click(function () {
        if (!requestSent) {
            requestSent = true;
            var parametros = {
                funcion: "deleteProducto",
                codigo: codigo,
                select_n1: $('#select_n1').val(),
                select_n2: $('#select_n2').val(),
                select_n3: $('#select_n3').val(),
                select_n4: $('#select_n4').val()
            }
            $.ajax({
                type: "POST",
                url: 'controller/productos.controller.php',
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

    $(".editProducto").click(function () {
        codigo = $(this).closest('tr').attr("codigo");
        $("#text-header-body").html("¿Desea eliminar el registro?");
        $("#btn-eliminar-producto").css("display", "inline-block");
        $("#btn-cancelar").text("Cancelar");

        var parametros = {
            funcion: "getProducto",
            select_n1: $('#select_n1').val(),
            select_n2: $('#select_n2').val(),
            select_n3: $('#select_n3').val(),
            select_n4: $('#select_n4').val(),
            codigo: codigo
        }
        $.ajax({
            type: "POST",
            url: 'controller/productos.controller.php',
            data: parametros,
            success: function (data) {
                var datos = JSON.parse(data);
                $('#descripcionUpdate').val(datos.descripcion);
                $('#abreviaturaUpdate').val(datos.descrip_abrev);
            },
            error: function () {
                alert("Error");
            }
        });
        //event.preventDefault();
        $('#dataUpdate').modal('show');
    });

    $(".nuevoProducto").click(function () {
        codigo = $(this).closest('tr').attr("codigo");
        $('#dataRegisterAlt').modal('show');
        $('#productoAddAlt').focus();
    });

    $(".deleteProducto").click(function () {
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
    });
    
    $("tbody > tr").click(function () {
        $("tbody > tr").css("background-color", "");
        $(this).css("background-color", "#FFFFB8");
    });

    $(".deleteAlternativa").click(function () {
        codigo = $(this).closest('tr').attr("codigo");
        $("#name-header-modalAlt").html("<b>Eliminar</b>");
        $("#text-header-bodyAlt").html("¿Desea eliminar el registro ?");
        $("#btn-eliminar").css("display", "inline-block");
        $("#btn-cancelar").text("Cancelar");
        $('#myModalAlt').modal('show');
    });
    
    $("#btn-eliminar-productoAlt").click(function () {
        if (!requestSent) {
            requestSent = true;
            var parametros = {
                funcion: "deleteProductoAlt",
                codigo: codigo
            }
            $.ajax({
                type: "POST",
                url: 'controller/productos.controller.php',
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
    

    $(".editAlternativa").click(function () {
        codigo = $(this).closest('tr').attr("codigo");
        $("#text-header-bodyAlt").html("¿Desea eliminar el registro?");
        $("#btn-eliminar-productoAlt").css("display", "inline-block");
        $("#btn-cancelar").text("Cancelar");

        var parametros = {
            funcion: "getProductoAlt",
            codigo: codigo
        }
        $.ajax({
            type: "POST",
            url: 'controller/productos.controller.php',
            data: parametros,
            success: function (data) {
                var datos = JSON.parse(data);
                $('#descripcionUpdateAlt').val(datos.descripcion);
            },
            error: function () {
                alert("Error");
            }
        });
        //event.preventDefault();
        $('#dataUpdateAlt').modal('show');
    });
    
    $(".archivosProducto").click(function () {
        codigo = $(this).closest('tr').attr("codigo");
        codigo_opc = $(this).closest('tr').attr("codigo_opc");
        window.location.href = "files.php?opc="+codigo+"&grupo="+codigo_opc;
    });
    
    $(".estado_editable").click(function (){
        var codigo = $(this).attr("codigo");
        var estado = $(this).attr("estado");
        var nuevo_estado = 0;
        if (estado == -1){
            nuevo_estado = 0;
        }
        if (estado == 0){
            nuevo_estado = 1;
        }
        if (estado == 1){
            nuevo_estado = 2;
        }
        if (estado == 2){
            nuevo_estado = -1;
        }
        var parametros = {
            funcion: "cambiar_estadoOpcion",   
            select_n1: $('#select_n1').val(),
            select_n2: $('#select_n2').val(),
            select_n3: $('#select_n3').val(),
            select_n4: $('#select_n4').val(),
            codigo: codigo,
            estado: nuevo_estado
        }
            $.ajax({
                type: "POST",
                url: 'controller/productos.controller.php',
                data: parametros,
                success: function (datos) {
                    location.reload();
                },
                error: function () {
                    alert("Error");
                },
                complete: function () {
                    requestSent = false;
                }
            });
    });
</script>
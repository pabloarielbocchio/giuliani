
<link href="inspinia/css/plugins/dropzone/dropzone.css" rel="stylesheet">	

<div class="row">
    <div class="col-md-6">    
        <?php if ($pa != "") { ?>
            <div class="form-group">
                <label for="nombre0" class="control-label">Nivel 1:</label>
                <input type="text" class="form-control" id="nivel1" name="nivel1" value="<?php echo $pa; ?>" readonly>
            </div>
        <?php } ?>

    </div>
    <div class="col-md-6">    
        
        <?php if ($pb != "") { ?>
            <div class="form-group">
                <label for="nombre0" class="control-label">Nivel 2:</label>
                <input type="text" class="form-control" id="nivel2" name="nivel2" value="<?php echo $pb; ?>" readonly>
            </div>
        <?php } ?>
        
    </div>
    <div class="col-md-6">  
        
        <?php if ($pc != "") { ?>
            <div class="form-group">
                <label for="nombre0" class="control-label">Nivel 3:</label>
                <input type="text" class="form-control" id="nivel3" name="nivel3" value="<?php echo $pc; ?>" readonly>
            </div>
        <?php } ?>
    
    </div>
    <div class="col-md-6">  
        
        <?php if ($pd != "") { ?>
            <div class="form-group">
                <label for="nombre0" class="control-label">Nivel 4:</label>
                <input type="text" class="form-control" id="nivel4" name="nivel4" value="<?php echo $pd; ?>" readonly>
            </div>
        <?php } ?>
    </div>  
    <div class="col-md-6">  
        
        <?php if ($pd != "") { ?>
            <div class="form-group">
                <label for="nombre0" class="control-label">Cantidad</label>
                <input type="text" class="form-control" id="cantidad" name="cantidad" value="1" >
                <input type="text" class="form-control hidden" id="unidad" name="unidad" value="<?php echo $unidad; ?>" >
            </div>
        <?php } ?>
    </div>  
        
    <?php if (count($registros) > 0) { ?> 

    <div class="col-md-6">   
        <div class="form-group">
            <label for="nombre0" class="control-label">Descripcion:</label>
            <input type="text" class="form-control" id="descripcion" name="descripcion" value="<?php echo $pa . ", " . $pb . ", " . $pc . ",  " . $pd; ?>" > 
            <button type="button" style="margin-top: 10px;" class="btn btn-danger boton_marron_carni boton_guardar" >Guardar</button> 
        </div>
    </div>

    <?php } ?>
    
</div>    

<?php if (count($registros) > 0) { ?> 


    <table id="tabla" namefile="Productos" totales="<?php echo $_SESSION["totales"]; ?>" registros="<?php echo $_SESSION['cant_reg']; ?>" pagina="<?php $_SESSION['pagina']; ?>" class="table table-striped table-hover" mes="<?php echo $mes; ?>" anio="<?php echo $anio; ?>" dia="<?php echo $dia; ?>" opcion="<?php echo $opcion; ?>"> 
        <thead>
            <tr class="row " style="background-color: transparent;">
                <th class="col-lg-11 col-md-11 col-sm-11 col-xs-11 text-left ordena" orderby="descripcion" sentido="asc">Opciones</th>
                <th class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center noExl">Seleccionado</th>
            </tr>
        </thead>
        <tbody id="body">
            <?php foreach ($registros as $usu) { ?>
                <tr class="row" codigo="<?php echo $usu["codigo"]; ?>">
                    <td class="col-lg-11 col-md-11 col-sm-11 col-xs-11 text-left" style="vertical-align: middle;"><?php echo $usu["descripcion"]; ?></td>
                    <td class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center noExl" style="vertical-align: middle;">
                        
                    </td>
                </tr>
                <?php 
                    $primero = 1;
                    foreach ($prod_f as $pf) { 
                        if ($pf["cod_prod_ne"] != $usu["codigo"]){
                            continue;
                        }
                ?>
                    <tr class="row opc_selected"  codigo="<?php echo $pf["codigo"]; ?>" pf="<?php echo $pf["codigo"]; ?>" pe="<?php echo $usu["codigo"]; ?>" Seleccionado="<?php echo $primero; ?>">
                        <td class="col-lg-11 col-md-11 col-sm-11 col-xs-11 text-left" style="vertical-align: middle; ">
                            <span style="margin-left: 25px;">
                                <?php echo $pf["descripcion"]; ?>
                            </span>
                        </td>
                        <td class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center noExl" style="vertical-align: middle;">
                            <?php
                            if ($primero == 1) {
                                $primero = 0;
                                echo '<span class="glyphicon glyphicon-ok opcion ok" pe="'.$usu["codigo"].'" pf="'.$pf["codigo"].'" style="color: #0A0; cursor: pointer;" aria-hidden="true"></span>';
                                echo '<span class="glyphicon glyphicon-remove opcion nook" pe="'.$usu["codigo"].'" pf="'.$pf["codigo"].'" style="color: #A00; display: none; cursor: pointer;" aria-hidden="true"></span>';
                            } else {
                                echo '<span class="glyphicon glyphicon-ok opcion ok" pe="'.$usu["codigo"].'" pf="'.$pf["codigo"].'" style="color: #0A0; display: none; cursor: pointer;" aria-hidden="true"></span>';
                                echo '<span class="glyphicon glyphicon-remove opcion nook" pe="'.$usu["codigo"].'" pf="'.$pf["codigo"].'" style="color: #A00; cursor: pointer;" aria-hidden="true"></span>';
                            }
                            ?>
                        </td>
                    </tr>
                <?php } ?>
            <?php } ?>
        </tbody>
    </table>

<?php } ?>

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
    
    $(".opcion").click(function (){
        var pe = $(this).attr("pe");
        var pf = $(this).attr("pf");
        $('.ok[pe="'+pe+'"]').css("display","none");
        $('.nook[pe="'+pe+'"]').css("display","block");
        $('.nook[pf="'+pf+'"]').css("display","none");
        $('.ok[pf="'+pf+'"]').css("display","block");
        $('.opc_selected[pe="'+pe+'"]').attr("seleccionado",0);
        $('.opc_selected[pf="'+pf+'"]').attr("seleccionado",1);
        console.log(pe);
        console.log(pf);
    });
    
    $(".boton_guardar").click(function () {
        if (!requestSent) {
            requestSent = true;
            configuracion = [];
            $('.opc_selected[seleccionado="1"]').each(function (){
                configuracion.push($(this).attr("pf"));
            });
            var parametros = {
                funcion: "updateProductoAll",
                codigo: codigo,
                cantidad: $("#cantidad").val(),
                unidad: $("#unidad").val(),
                descripcion: $("#descripcion").val(),
                configuracion: configuracion,
                otd: $(".container").attr("ot_detail"),
                prodd: $("#select_n4").val()
            }
            $.ajax({
                type: "POST",
                url: 'controller/productos_s.controller.php',
                data: parametros,
                success: function (datos) {
                    if (parseInt(datos) == 0) {
                        window.location.href = "detalles.php";
                    } else {
                        alert("Error: " + datos);
                    }
                },
                error: function () {
                    alert("Error");
                }
            });
            event.preventDefault();
        }
        });
</script>

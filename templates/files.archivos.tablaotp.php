<?php if (count($archivos) > 0) { ?>
    <table id="tabla" class="table table-striped table-hover"  style="margin-top: -<?php echo count($archivos)*40; ?>px;"> 
        <thead>
            <tr class="row " style="background-color: transparent;">
                <th  class="text-left " orderby="seccion" sentido="asc">Archivo</th>
                <th  class="text-left " orderby="seccion" sentido="asc">Dependencia</th>
                <th  class="text-center " orderby="seccion" sentido="asc">Última Actualización</th>
                <th  class="text-center " orderby="seccion" sentido="asc">Estado Actual</th>
                <?php foreach($destinos as $destino) { ?>
                    <th class="text-center " orderby="item_vendido" sentido="asc"><?php echo $destino["descripcion"]; ?></th>
                <?php } ?>
                <th  class="text-left " orderby="seccion" sentido="asc" style="width: 5%;"></th>            
            </tr>
        </thead>
        <tbody id="body">
            <?php foreach ($archivos as $usu) { ?>
                <tr class="row" 
                        otp="<?php echo $usu["ot_produccion_id"]; ?>" 
                        otd="<?php echo $usu["ot_detalle_id"]; ?>" 
                        codigo="<?php echo $usu["codigo"]; ?>" 
                        ruta="<?php echo $usu["ruta"]; ?>"
                        archivo="<?php echo $usu["archivo"]; ?>"
                        nuevo="<?php echo $usu["nuevo"]; ?>"
                    >
                    <td class="text-left" style="vertical-align: middle;"><?php echo $usu["archivo"]; ?></td>
                    <td class="text-left" style="vertical-align: middle;" title="<?php echo $usu["dependencia"]; ?>">
                        <?php echo strlen($usu["dependencia"]) > 35 ? substr($usu["dependencia"],0,35)."..." : $usu["dependencia"]; ?></td>
                    <td class="text-center" style="vertical-align: middle;"><?php echo date("d/m/Y", strtotime($usu["ultima_actualizacion"])); ?></td>
                    <td class="text-center" style="vertical-align: middle; width: 10%;">
                    <?php 
                            switch ($usu["activo"]){
                                case -1:
                                    echo '<span style="cursor: pointer;" estado="-1" archivo="'.$usu["cod_archivo"].'" class="estado_editable label label-danger m-t-lg">Inactivo</span>';
                                    break;
                                case 0:
                                    echo'<span style="cursor: pointer;" estado="0" archivo="'.$usu["cod_archivo"].'" class="estado_editable label label-warning m-t-lg" >En Proceso</span>';
                                    break;
                                case 1:
                                    echo '<span style="cursor: pointer;" estado="1"  archivo="'.$usu["cod_archivo"].'" class="estado_editable label label-info m-t-lg">Activo</span>';
                                    break;
                            }
                        ?>
                    </td>
                    <?php foreach($destinos as $destino) { 
                        $permitido = 0;
                    ?>
                        <?php 
                        foreach($archivos_destinos as $ad) { 
                            if ($ad["archivo_id"] != $usu["cod_archivo"]){
                                continue;
                            }
                            if ($ad["destino_id"] != $destino["codigo"]){
                                continue;
                            }
                            $permitido = $ad["codigo"];
                            break;
                        }
                        ?>
                        <td class="text-center" style="vertical-align: middle; width: 5%;">
                            <?php
                            if ($permitido > 0) {
                                echo '<span class="glyphicon glyphicon-ok opcion ok" archivo="'.$usu["cod_archivo"].'" destino="'.$destino["codigo"].'" permitido="'.$permitido.'"  style="color: #0A0;  cursor: pointer;" aria-hidden="true"></span>';
                            } else {
                                echo '<span class="glyphicon glyphicon-remove opcion nook" archivo="'.$usu["cod_archivo"].'" destino="'.$destino["codigo"].'" permitido="0" style="color: #A00; cursor: pointer; " aria-hidden="true"></span>';
                            }
                            ?>
                        </td>
                    <?php } ?>
                    

                    <td class="text-left" style="vertical-align: middle;">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-primary dropdown-toggle nuevo" id="menu" type="button" data-toggle="dropdown"  style="font-size: 10px;height: 15px;">
                                <div class="opciones" style="margin-top: -6px">Opciones <span class="caret"></span></div>
                            </button>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="menu">
                                <li role="presentation" class="descargar"><a role="menuitem" tabindex="-1" href="#">Descargar</a></li>
                                <?php if ($usu["nuevo"] == 1) {  ?>
                                    <li role="presentation" class="eliminar opc_eliminar"><a role="menuitem" tabindex="-1" href="#">Eliminar</a></li>
                                <?php } ?>
                            </ul>
                        </div>
                    </td>
                        
                </tr>
            <?php } ?>
        </tbody>
    </table>
<?php } ?>

<script>

    var readonly = $(".container").attr("read");
    if (readonly == 1){
        $(".opc_eliminar").css("display", "none");
    }

    $(".descargar").click(function () {
        codigo = $(this).closest('tr').attr("codigo");
        otp = $(this).closest('tr').attr("otp");
        otd = $(this).closest('tr').attr("otd");
        ruta = $(this).closest('tr').attr("ruta");
        archivo = $(this).closest('tr').attr("archivo");
        var parametros = {
            funcion: "addOt_evento",
            detalle: otd,
            produccion: otp,
            evento: 5,
            destino: 0,
            observaciones: "Descarga archivo " + archivo + " (" + ruta + ")"
        }
        $.ajax({
            type: "POST",
            url: 'controller/ot_eventos.controller.php',
            data: parametros,
            success: function (datos) {
                if (parseInt(datos) == 0) {                            
                    var link=document.createElement('a');
                    document.body.appendChild(link);
                    link.download=archivo;
                    link.href=ruta;
                    link.click();
                    //location.reload();
                } else {
                    alert("Error");
                }
            },
            error: function () {
                alert("Error");
            }
        });
    });
    
    $(".estado_editable").click(function (){
        var nuevo = $(this).closest('tr').attr("nuevo");
        var archivo = $(this).attr("archivo");
        var estado = $(this).attr("estado");
        var nuevo_estado = 0;
        if (estado == -1){
            nuevo_estado = 0;
        }
        if (estado == 0){
            nuevo_estado = 1;
        }
        if (estado == 1){
            nuevo_estado = -1;
        }
        var readonly = $(".container").attr("read");
        if (readonly == 1){
            return false;
        }
        var parametros = {
            funcion: "cambiar_estadoArchivo",         
            codigo: archivo,
            estado: nuevo_estado,
        }
        if (nuevo == 1){ 
            console.log(parametros);    
            $.ajax({
                type: "POST",
                url: 'controller/archivo_destinos.controller.php',
                data: parametros,
                success: function (datos) {
                    buscarTabla();
                },
                error: function () {
                    alert("Error");
                },
                complete: function () {
                    requestSent = false;
                }
            });
        }
    });

    
    $(".opcion").click(function (){
        var nuevo = $(this).closest('tr').attr("nuevo");
        var archivo = $(this).attr("archivo");
        var destino = $(this).attr("destino");
        var permitido = $(this).attr("permitido");      
        var readonly = $(".container").attr("read");
        if (readonly == 1){
            return false;
        }  
        if (nuevo == 1){ 
            if (permitido > 0){
                var parametros = {
                    funcion: "deleteArchivo_destino",         
                    codigo: permitido
                }
                console.log(parametros);        
                $.ajax({
                    type: "POST",
                    url: 'controller/archivo_destinos.controller.php',
                    data: parametros,
                    success: function (datos) {
                        buscarTabla();
                    },
                    error: function () {
                        alert("Error");
                    },
                    complete: function () {
                        requestSent = false;
                    }
                });
            }
            if (permitido == 0){                
                var parametros = {
                    funcion: "addArchivo_destino",         
                    archivo: archivo,
                    destino: destino,
                    observaciones: ""
                }
                console.log(parametros);        
                $.ajax({
                    type: "POST",
                    url: 'controller/archivo_destinos.controller.php',
                    data: parametros,
                    success: function (datos) {
                        buscarTabla();
                    },
                    error: function () {
                        alert("Error");
                    },
                    complete: function () {
                        requestSent = false;
                    }
                });
            }
        }
    });

    $(".eliminar").click(function () {
        codigo = $(this).closest('tr').attr("codigo");
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
    });
</script>


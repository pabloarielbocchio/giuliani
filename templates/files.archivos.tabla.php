<table id="tabla" totales="<?php echo $_SESSION["totales"]; ?>" registros="<?php echo $_SESSION['cant_reg']; ?>" pagina="<?php $_SESSION['pagina']; ?>" class="table table-striped table-hover" mes="<?php echo $mes; ?>" anio="<?php echo $anio; ?>" dia="<?php echo $dia; ?>" opcion="<?php echo $opcion; ?>"> 
    <thead>
        <tr class="row " style="background-color: transparent;">
            <th  class="text-left " orderby="seccion" sentido="asc">Archivo</th>
            <th  class="text-center " orderby="seccion" sentido="asc">Estado</th>
            <?php foreach($destinos as $destino) { ?>
                <th class="text-center " orderby="item_vendido" sentido="asc"><?php echo $destino["descripcion"]; ?></th>
            <?php } ?>
        </tr>
    </thead>
    <tbody id="body">
        <?php foreach ($archivos as $usu) { ?>
            <tr class="row" codigo="<?php echo $usu["codigo"]; ?>">
                <td class="text-left" style="vertical-align: middle;"><?php echo $usu["descripcion"]; ?></td>
                <td class="text-center" style="vertical-align: middle; width: 10%;">
                    <?php 
                        switch ($usu["activo"]){
                            case -1:
                                echo '<span style="cursor: pointer;" estado="-1" archivo="'.$usu["codigo"].'" class="estado_editable label label-danger m-t-lg">Inactivo</span>';
                                break;
                            case 0:
                                echo'<span style="cursor: pointer;" estado="0" archivo="'.$usu["codigo"].'" class="estado_editable label label-warning m-t-lg" >En Proceso</span>';
                                 break;
                            case 1:
                                echo '<span style="cursor: pointer;" estado="1"  archivo="'.$usu["codigo"].'" class="estado_editable label label-info m-t-lg">Activo</span>';
                                break;
                        }
                    ?>
                </td>
                <?php foreach($destinos as $destino) { 
                    $permitido = 0;
                ?>
                    <?php 
                    foreach($archivos_destinos as $ad) { 
                        if ($ad["archivo_id"] != $usu["codigo"]){
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
                                echo '<span class="glyphicon glyphicon-ok opcion ok" archivo="'.$usu["codigo"].'" destino="'.$destino["codigo"].'" permitido="'.$permitido.'" style="color: #0A0; cursor: pointer;" aria-hidden="true"></span>';
                            } else {
                                echo '<span class="glyphicon glyphicon-remove opcion nook" archivo="'.$usu["codigo"].'" destino="'.$destino["codigo"].'" permitido="0" style="color: #A00; cursor: pointer;" aria-hidden="true"></span>';
                            }
                            ?>
                        </td>
                    <?php } ?>
                
            </tr>
<?php } ?>
    </tbody>
</table>

<script>    
    $(".opcion").click(function (){
        var archivo = $(this).attr("archivo");
        var destino = $(this).attr("destino");
        var permitido = $(this).attr("permitido");
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
    });
    $(".estado_editable").click(function (){
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
        var parametros = {
            funcion: "cambiar_estadoArchivo",         
            codigo: archivo,
            estado: nuevo_estado,
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
    });
</script>
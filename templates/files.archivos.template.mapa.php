<?php
    $last_a = null;
    $last_b = null;
    $last_c = null;
    $last_d = null;
?>
<div style="float:right; margin: 10px;">
    <span style="cursor: pointer; font-weight: bolder;" class=" m-t-lg m-r-sm">Referencias => </span>
    <span style="cursor: pointer;" class=" label label-danger m-t-lg">Necesita Correccion</span>
    <span style="cursor: pointer;" class=" label label-warning m-t-lg" >Sin Desarrollar</span>
    <span style="cursor: pointer;" class=" label label-info m-t-lg">En Desarrollo</span>
    <span style="cursor: pointer;" class=" label label-success m-t-lg">Aprobado</span>
</div>
<table id="tabla" class="table table-striped table-hover"> 
    <thead>
        <tr class="row " style="background-color: transparent;">
            <th  class="text-center ">Nivel 1</th>        
            <th  class="text-center ">Nivel 2</th>        
            <th  class="text-center ">Nivel 3</th>        
            <th  class="text-center ">Nivel 4</th>        
            <th  class="text-center ">Grupo de Opciones</th>        
            <th  class="text-center ">Opciones</th>        
        </tr>
    </thead>
    <tbody id="body">        
        <?php foreach ($registros as $regs) { 
            if ($n1 > 0 and $regs["cna"] != $n1){
                continue;
            }
            if ($n2 > 0 and $regs["cnb"] != $n2){
                continue;
            }
            if ($n3 > 0 and $regs["cnc"] != $n3){
                continue;
            }
            if ($n4 > 0 and $regs["cnd"] != $n4){
                continue;
            }
        ?>
            <tr class="row">
                <td class="text-left" style="vertical-align: middle; border: 1px solid #DEDEDE;">
                    <?php 
                        if ($regs["cna"] != $last_a) {
                            switch (intval($regs["ena"])){
                                case -1:
                                    echo '<span nivel="1" style="cursor: pointer;" estado="-1" codigo="'.$regs["cna"].'" class="estado_editable label label-danger m-t-lg">'.$regs["dna"].'</span>';
                                    break;
                                case 0:
                                    echo'<span nivel="1" style="cursor: pointer;" estado="0" codigo="'.$regs["cna"].'" class="estado_editable label label-warning m-t-lg" >'.$regs["dna"].'</span>';
                                    break;
                                case 1:
                                    echo '<span nivel="1" style="cursor: pointer;" estado="1"  codigo="'.$regs["cna"].'" class="estado_editable label label-info m-t-lg">'.$regs["dna"].'</span>';
                                    break;
                                case 2:
                                    echo '<span nivel="1" style="cursor: pointer;" estado="2"  codigo="'.$regs["cna"].'" class="estado_editable label label-success m-t-lg">'.$regs["dna"].'</span>';
                                    break;
                            }
                        }
                    ?>
                </td>
                <td class="text-left" style="vertical-align: middle; border: 1px solid #DEDEDE;">
                    <?php 
                        if ($regs["cnb"] != $last_b) {
                            switch (intval($regs["enb"])){
                                case -1:
                                    echo '<span nivel="2" style="cursor: pointer;" estado="-1" codigo="'.$regs["cnb"].'" class="estado_editable label label-danger m-t-lg">'.$regs["dnb"].'</span>';
                                    break;
                                case 0:
                                    echo'<span nivel="2" style="cursor: pointer;" estado="0" codigo="'.$regs["cnb"].'" class="estado_editable label label-warning m-t-lg" >'.$regs["dnb"].'</span>';
                                    break;
                                case 1:
                                    echo '<span nivel="2" style="cursor: pointer;" estado="1"  codigo="'.$regs["cnb"].'" class="estado_editable label label-info m-t-lg">'.$regs["dnb"].'</span>';
                                    break;
                                case 2:
                                    echo '<span nivel="2" style="cursor: pointer;" estado="2"  codigo="'.$regs["cnb"].'" class="estado_editable label label-success m-t-lg">'.$regs["dnb"].'</span>';
                                    break;
                            }
                        }
                    ?>
                </td>
                <td class="text-left" style="vertical-align: middle; border: 1px solid #DEDEDE;">
                    <?php 
                        if ($regs["cnc"] != $last_c) {
                            switch (intval($regs["enc"])){
                                case -1:
                                    echo '<span nivel="3" style="cursor: pointer;" estado="-1" codigo="'.$regs["cnc"].'" class="estado_editable label label-danger m-t-lg">'.$regs["dnc"].'</span>';
                                    break;
                                case 0:
                                    echo'<span nivel="3" style="cursor: pointer;" estado="0" codigo="'.$regs["cnc"].'" class="estado_editable label label-warning m-t-lg" >'.$regs["dnc"].'</span>';
                                    break;
                                case 1:
                                    echo '<span nivel="3" style="cursor: pointer;" estado="1"  codigo="'.$regs["cnc"].'" class="estado_editable label label-info m-t-lg">'.$regs["dnc"].'</span>';
                                    break;
                                case 2:
                                    echo '<span nivel="3" style="cursor: pointer;" estado="2"  codigo="'.$regs["cnc"].'" class="estado_editable label label-success m-t-lg">'.$regs["dnc"].'</span>';
                                    break;
                            }
                        }
                    ?>
                </td>
                <td class="text-left" style="vertical-align: middle; border: 1px solid #DEDEDE;">
                    <?php 
                        if ($regs["cnd"] != $last_d) {
                            switch (intval($regs["end"])){
                                case -1:
                                    echo '<span nivel="4" style="cursor: pointer;" estado="-1" codigo="'.$regs["cnd"].'" class="estado_editable label label-danger m-t-lg">'.$regs["dnd"].'</span>';
                                    break;
                                case 0:
                                    echo'<span nivel="4" style="cursor: pointer;" estado="0" codigo="'.$regs["cnd"].'" class="estado_editable label label-warning m-t-lg" >'.$regs["dnd"].'</span>';
                                    break;
                                case 1:
                                    echo '<span nivel="4" style="cursor: pointer;" estado="1"  codigo="'.$regs["cnd"].'" class="estado_editable label label-info m-t-lg">'.$regs["dnd"].'</span>';
                                    break;
                                case 2:
                                    echo '<span nivel="4" style="cursor: pointer;" estado="2"  codigo="'.$regs["cnd"].'" class="estado_editable label label-success m-t-lg">'.$regs["dnd"].'</span>';
                                    break;
                            }
                        }
                    ?>
                </td>
                <td class="text-left" style="vertical-align: middle; border: 1px solid #DEDEDE;"><?php echo $regs["cne"] != $last_e ? $regs["dne"] : ""; ?></td>
                <td class="text-left" style="vertical-align: middle; border: 1px solid #DEDEDE;">
                    <?php echo "<br/>"; ?>
                <?php foreach ($regs["opciones"] as $usu) { ?>    
                    <?php echo "<div>"; ?>                
                    <?php 
                            switch (intval($usu["enf"])){
                                case -1:
                                    echo '<span nivel="6" style="cursor: pointer;" estado="-1" codigo="'.$usu["cnf"].'" class="estado_editable label label-danger m-t-lg">'.$usu["dnf"].'</span>';
                                    break;
                                case 0:
                                    echo'<span nivel="6" style="cursor: pointer;" estado="0" codigo="'.$usu["cnf"].'" class="estado_editable label label-warning m-t-lg" >'.$usu["dnf"].'</span>';
                                    break;
                                case 1:
                                    echo '<span nivel="6" style="cursor: pointer;" estado="1"  codigo="'.$usu["cnf"].'" class="estado_editable label label-info m-t-lg">'.$usu["dnf"].'</span>';
                                    break;
                                case 2:
                                    echo '<span nivel="6" style="cursor: pointer;" estado="2"  codigo="'.$usu["cnf"].'" class="estado_editable label label-success m-t-lg">'.$usu["dnf"].'</span>';
                                    break;
                            }
                    ?>
                    <?php echo "</div><br/>"; ?>
                <?php } ?>
                </td>  
            </tr>
        <?php 
            $last_a = $regs["cna"];
            $last_b = $regs["cnb"];
            $last_c = $regs["cnc"];
            $last_d = $regs["cnd"];
        } ?>
    </tbody>
</table>

<script>
    $("tbody > tr").click(function () {
        $("tbody > tr").css("background-color", "");
        $(this).css("background-color", "#FFFFB8");
    });

    
    $(".estado_editable").click(function (){
        var codigo = $(this).attr("codigo");
        var estado = $(this).attr("estado");
        var nivel = $(this).attr("nivel");
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
            funcion: "mostrarArchivos",   
            nivel: nivel,
            codigo: codigo,
            estado: nuevo_estado
        }
        $.ajax({
            type: "POST",
            url: 'controller/productos.controller.php',
            data: parametros,
            success: function (datos) {
                //$("#name-header-modal").html("<b>Eliminar</b>");
                //$("#text-header-body").html("¿Desea eliminar el registro ?");
                //$("#btn-eliminar").css("display", "inline-block");
                //$("#btn-cancelar").text("Cancelar");
                $("#text-header-body").html(datos);
                $('#myModal').modal('show');
            },
            error: function () {
                alert("Error");
            },
            complete: function () {
                requestSent = false;
            }
        });
        /*var parametros = {
            funcion: "cambiar_estadoProductoParam",   
            nivel: nivel,
            codigo: codigo,
            estado: nuevo_estado
        }
        $.ajax({
            type: "POST",
            url: 'controller/productos.controller.php',
            data: parametros,
            success: function (datos) {
                $("#busqueda-icono").click();
            },
            error: function () {
                alert("Error");
            },
            complete: function () {
                requestSent = false;
            }
        });*/
    });

</script>
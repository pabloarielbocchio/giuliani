<table id="tabla" namefile="Ot_detalles" totales="<?php echo $_SESSION["totales"]; ?>" registros="<?php echo $_SESSION['cant_reg']; ?>" pagina="<?php $_SESSION['pagina']; ?>" destinos="<?php echo $_SESSION['destinos']; ?>" class="table table-striped table-hover" > 
    <thead>
        <tr class="row " style="background-color: transparent;">
            <th  class="text-left " orderby="item_vendido" sentido="asc"> </th>
            <th  class="text-center " orderby="seccion" sentido="asc"></th>
            <th  class="text-center " orderby="seccion" sentido="asc">Ingenieria</th>
            <?php foreach($destinos as $destino) { ?>
                <th class="text-center " orderby="item_vendido" sentido="asc"><?php echo $destino["descripcion"]; ?></th>
            <?php } ?>
            <th  class="text-center " orderby="prioridad" sentido="asc">Calidad</th>
            <th  class="text-center " orderby="estado" sentido="asc">Gerencia</th>
            <th  class="text-center " orderby="estado" sentido="asc"></th>
        </tr>
    </thead>
    <tbody id="body">
        <!--
        <?php foreach ($registros as $usu) {  ?>
            <tr class="row" codigo="<?php echo $usu["codigo"]; ?>">
                <td class="text-center verprod" oculto="1" style="vertical-align: middle; width: 5%; font-weight: bolder;">[+]</td>
                <td class="text-left  " style="vertical-align: middle; font-weight: bolder;"><?php echo $usu["item_vendido"]; ?></td>
                <td class="text-center" style="vertical-align: middle; width: 5%;"><span style="cursor: pointer;" class="estado_editable label label-success m-t-lg">CERTIFICADO</span></td>
                    
                <?php foreach($destinos as $destino) { ?>
                    <td class="text-center" style="vertical-align: middle; width: 5%;"><span style="cursor: pointer;" class="estado_editable label label-info m-t-lg">CERRADO</span></td>
                <?php } ?>
                <td class="text-center" style="vertical-align: middle; width: 5%;"><span style="cursor: pointer;" class="estado_editable label label-warning m-t-lg">EN PROCESO</span></td>
                <td class="text-center" style="vertical-align: middle; width: 5%;"><span style="cursor: pointer;" class="estado_editable label label-danger m-t-lg">BORRADOR</span></td>
                <td class="text-center noExl" style="vertical-align: middle;  width: 5%;">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-primary dropdown-toggle nuevo" id="menu" type="button" data-toggle="dropdown"  style="font-size: 10px;height: 15px;">
                            <div class="opciones" style="margin-top: -6px">Opciones <span class="caret"></span></div>
                        </button>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="menu">
                            <li role="presentation" class="editprod_detalle"><a role="menuitem" tabindex="-1" href="#">Eventos</a></li>
                        </ul>
                    </div>
                </td>

                <?php 
                    foreach ($prods as $prod) { 
                        if ($prod["ot_detalle_id"] != $usu["codigo"]){
                            continue;
                        }
                ?>
                    <tr class="row otdetprod" cod_det="<?php echo $usu["codigo"]; ?>" codigo="<?php echo $prod["codigo"]; ?>" style="display: none;">
                        <td class="text-center" style="vertical-align: middle; width: 5%;"></td>
                        <td colspan="1" class="text-left" style="vertical-align: middle;"><?php echo $prod["prod_personalizado"] . " (" . $prod["unidad"] . ")"; ?></td>
                        <td class="text-center" style="vertical-align: middle; width: 5%;"><span style="cursor: pointer;" class="estado_editable label label-success m-t-lg">CERTIFICADO</span></td>
                        <?php foreach($destinos as $destino) { ?>
                            <td class="text-center" style="vertical-align: middle; width: 5%;"><span style="cursor: pointer;" class="estado_editable label label-info m-t-lg">CERRADO</span></td>
                        <?php } ?>
                        <td class="text-center" style="vertical-align: middle; width: 5%;"><span style="cursor: pointer;" class="estado_editable label label-warning m-t-lg">EN PROCESO</span></td>
                        <td class="text-center" style="vertical-align: middle; width: 5%;"><span style="cursor: pointer;" class="estado_editable label label-danger m-t-lg">BORRADOR</span></td>
                        <td class="text-center noExl" style="vertical-align: middle;  width: 5%;">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-primary dropdown-toggle nuevo" id="menu" type="button" data-toggle="dropdown"  style="font-size: 10px;height: 15px;">
                                    <div class="opciones" style="margin-top: -6px">Opciones <span class="caret"></span></div>
                                </button>
                                <ul class="dropdown-menu" role="menu" aria-labelledby="menu">
                                    <li role="presentation" class="editprod_detalle"><a role="menuitem" tabindex="-1" href="#">Eventos</a></li>
                                </ul>
                            </div>
                        </td>
       
                    </tr>
                <?php } ?>
<?php } ?>
            </tr>-->            
            <?php foreach ($secciones as $secc) { ?>
                <tr class="row seccion" codigo="<?php echo $secc["codigo"]; ?>">
                    <td class="text-left  " style="vertical-align: middle; font-weight: bolder;"><?php echo "SECCION: " . $secc["descripcion"]; ?></td>
                    <td class="text-center" style="vertical-align: middle; width: 5%;"></td>   
                    <td class="text-center" style="vertical-align: middle; width: 5%;"></td>   
                    <?php foreach($destinos as $destino) { ?>
                        <td class="text-center" style="vertical-align: middle; width: 5%;"></td>                
                    <?php } ?>
                    <td class="text-center" style="vertical-align: middle; width: 5%;"></td>   
                    <td class="text-center" style="vertical-align: middle; width: 5%;"></td>   
                    <td class="text-center" style="vertical-align: middle; width: 5%;"></td>   
                </tr>             
                <?php foreach ($secc["sectores"] as $sect) { ?>  
                <tr class="row sector" codigo="<?php echo $sect["codigo"]; ?>">              
                    <td class="text-left  " style="vertical-align: middle; font-weight: bolder; padding-left: 25px;"><?php echo "SECTOR: " . $sect["descripcion"]; ?></td>
                    <td class="text-center" style="vertical-align: middle; width: 5%;"></td>   
                    <td class="text-center" style="vertical-align: middle; width: 5%;"></td>   
                    <?php foreach($destinos as $destino) { ?>
                        <td class="text-center" style="vertical-align: middle; width: 5%;"></td>                
                    <?php } ?>
                    <td class="text-center" style="vertical-align: middle; width: 5%;"></td>   
                    <td class="text-center" style="vertical-align: middle; width: 5%;"></td>   
                    <td class="text-center" style="vertical-align: middle; width: 5%;"></td>       
                </tr>   
                    <?php foreach ($sect["registros"] as $reg) { ?>  
                        <tr class="row registro" codigo="<?php echo $reg["codigo"]; ?>" finalizada="<?php echo intval($reg["finalizada"]); ?>" >                                  
                            <td class="text-left  " style="vertical-align: middle; font-weight: bolder; padding-left: 50px;"><?php echo $reg["item_vendido"]; ?></td>
                            <td class="text-center" style="vertical-align: middle; width: 5%;">
                            <?php 
                                if ($reg["finalizada"] == 1){
                                    echo '<span class="label label-danger m-t-lg" style="width: 100%; background-color: #65b399; color: #EEE;">FINALIZADA</span>';
                                } else {
                                    echo '<span class="label label-danger m-t-lg" style="width: 100%; background-color: #9ec569; color: #eee;">EN CURSO</span>';
                                }
                            ?>
                            </td>   
                            <td class="text-center" style="vertical-align: middle; width: 5%;"></td>   
                            <?php foreach($destinos as $destino) { ?>
                                <td class="text-center" style="vertical-align: middle; width: 5%;"></td>                
                            <?php } ?>
                            <td class="text-center" style="vertical-align: middle; width: 5%;"></td>   
                            <td class="text-center" style="vertical-align: middle; width: 5%;"></td>   
                            <td class="text-center" style="vertical-align: middle; width: 5%;">
                            <?php if ($_SESSION["rol"] == 1 or $_SESSION["rol"] == 2) { ?>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-primary dropdown-toggle nuevo" id="menu" type="button" data-toggle="dropdown"  style="font-size: 10px;height: 15px;">
                                        <div class="opciones" style="margin-top: -6px">Opciones <span class="caret"></span></div>
                                    </button>
                                    <ul class="dropdown-menu" role="menu" aria-labelledby="menu">
                                    <?php 
                                        if ($reg["finalizada"] == 1){
                                            if ($_SESSION["rol"] == 1 or $_SESSION["rol"] == 2){
                                                echo '<li role="presentation" class="abrirOtDetalle_listado"><a role="menuitem" tabindex="-1" href="#">Reabrir</a></li>';
                                            }
                                        } else {                                            
                                            if ($_SESSION["rol"] == 1 or $_SESSION["rol"] == 2){
                                                echo '<li role="presentation" class="finalizarOtDetalle_listado"><a role="menuitem" tabindex="-1" href="#">Finalizar</a></li>';
                                            }
                                        }
                                    ?>
                                    </ul>
                                </div>
                            <?php } ?>
                            </td>      
                        </tr>   
                        
                        <?php 
                            foreach ($prods as $prod) { 
                                if ($prod["ot_detalle_id"] != $reg["codigo"]){
                                    continue;
                                }
                                $cod_otd_pte = 0;
                                $cod_otd_ep = 0;
                                $cod_otd_fin = 0;
                                $cod_otd_anu = 0;
                                $prod = $this->formatNumber($prod);
                        ?>
                            <tr class="row otdetprod" cod_det="<?php echo $prod["ot_detalle_id"]; ?>" codigo="<?php echo $prod["codigo"]; ?>" finalizada="<?php echo intval($reg["finalizada"]); ?>" >
                                <td class="text-left" style="vertical-align: middle; padding-left: 75px;">
                                    <?php 
                                        if ($prod["standar"] == 1){
                                            echo $prod["numero"] . " - " . "STD: ";
                                            echo $prod["prod_standar"] . " (" . $prod["cantidad"] . " " . $prod["unidad"] . ")"; 
                                        } else {
                                            echo $prod["numero"] . " - " . $prod["prod_personalizado"] . " (" . $prod["cantidad"] . " " . $prod["unidad"] . ")"; 
                                        }
                                    ?>
                                </td>
                                <td class="text-center" style="vertical-align: middle; width: 5%;">
                                    <?php 
                                        if ($prod["standar"] == 1){
                                            echo '<span class="label label-danger m-t-lg" style="width: 100%; background-color: #fdb25d; color: #eee;">STD</span>';
                                        } else {
                                            echo '<span class="label label-danger m-t-lg" style="width: 100%; background-color: #fe795f; color: #eee;">CTM</span>';
                                        }
                                    ?>
                                </td>   
                                <?php 
                                    $estado_html = '<span style="background-color: #ffffff00;" class=" label  m-t-lg">...</span>';    
                                    foreach($estados as $estado) { 
                                        if ($estado["ot_prod_id"] != $prod["codigo"]){
                                            continue;
                                        }
                                        if ($estado["ingenieria"] != 1){
                                            continue;
                                        }
                                        $_estado = $estado;
                                        switch($estado["estado_id"]){
                                            case 1:
                                                $estado_html = '<span style="background-color: #ea4d3b;" codigo="'.$estado["codigo"].'" userrol="5" estado="'.$estado["estado_id"].'" class="estado_editable label label-warning m-t-lg" >'.$estado["estado"].'</span>';
                                                break;
                                            case 2:
                                                $title = "\n";
                                                if ($estado["ing_alcance"] == 1){
                                                    $title .= "Definicion de alcance: FINALIZADO\n";
                                                }
                                                if ($estado["ing_planos"] == 1){
                                                    $title .= "Definicion de planos: FINALIZADO\n";
                                                }
                                                $estado_html = '<span title="'.$title.'" style="background-color: #ff899f;" codigo="'.$estado["codigo"].'" userrol="5" estado="'.$estado["estado_id"].'" class="estado_editable label label-info m-t-lg">'.$estado["estado"].' '.number_format($estado["avance"],0).'%</span>';
                                                break;
                                            case 3:
                                                $estado_html = '<span style="background-color: #3a3d5c;" codigo="'.$estado["codigo"].'" userrol="5" estado="'.$estado["estado_id"].'" class="estado_editable label label-success m-t-lg">'.$estado["estado"].'</span>';
                                                break;
                                            case 4:
                                                $estado_html = '<span style="background-color: #7583a0;" codigo="'.$estado["codigo"].'" userrol="5" estado="'.$estado["estado_id"].'" class="estado_editable label label-danger m-t-lg">'.$estado["estado"].'</span>';
                                                break;
                                        } 
                                    } 
                                ?>
                                <td class="text-center" atributo="ingenieria" style="vertical-align: middle; width: 5%;"><?php echo $estado_html; ?></td>

                                <?php foreach($destinos as $destino) { ?>
                                    <?php 
                                        $estado_html = '<span style="background-color: #ffffff00;" class=" label  m-t-lg">...</span>';    
                                        foreach($estados as $estado) { 
                                            if ($estado["ot_prod_id"] != $prod["codigo"]){
                                                continue;
                                            }
                                            if ($estado["produccion"] != 1){
                                                continue;
                                            }
                                            if ($estado["destino_id"] != $destino["codigo"]){
                                                continue;
                                            }
                                            if (!$prod["destinos"][$destino["codigo"]]){
                                                continue;
                                            }
                                            switch($estado["estado_id"]){
                                                case 1:
                                                    $estado_html = '<span style="background-color: #ea4d3b;" codigo="'.$estado["codigo"].'" userrol="4" destino="'.$estado["destino_id"].'" estado="'.$estado["estado_id"].'" class="estado_editable label label-warning m-t-lg">'.$estado["estado"].'</span>';
                                                    break;
                                                case 2:
                                                    $estado_html = '<span style="background-color: #ff899f;" codigo="'.$estado["codigo"].'" userrol="4" destino="'.$estado["destino_id"].'" class="estado_editable label label-info m-t-lg">'.$estado["estado"].' '.number_format($estado["avance"],0).'%</span>';
                                                    break;
                                                case 3:
                                                    $estado_html = '<span style="background-color: #3a3d5c;" codigo="'.$estado["codigo"].'" userrol="4" destino="'.$estado["destino_id"].'" class="estado_editable label label-success m-t-lg">'.$estado["estado"].'</span>';
                                                    break;
                                                case 4:
                                                    $estado_html = '<span style="background-color: #7583a0;" codigo="'.$estado["codigo"].'" userrol="4" destino="'.$estado["destino_id"].'" class="estado_editable label label-danger m-t-lg">'.$estado["estado"].'</span>';
                                                    break;
                                            } 
                                        } 
                                    ?>
                                    <td class="text-center" atributo="produccion" style="vertical-align: middle; width: 5%;"><?php echo $estado_html; ?></td>
                                <?php } ?>

                                <?php 
                                    $estado_html = '<span style="background-color: #ffffff00;" class=" label  m-t-lg">...</span>';      
                                    foreach($estados as $estado) { 
                                        if ($estado["ot_prod_id"] != $prod["codigo"]){
                                            continue;
                                        }
                                        if ($estado["calidad"] != 1){
                                            continue;
                                        }
                                        switch($estado["estado_id"]){
                                            case 1:
                                                $estado_html = '<span style="background-color: #ea4d3b;" codigo="'.$estado["codigo"].'" userrol="3" estado="'.$estado["estado_id"].'" class="estado_editable label label-warning m-t-lg">'.$estado["estado"].'</span>';
                                                break;
                                            case 2:
                                                $estado_html = '<span style="background-color: #ff899f;" codigo="'.$estado["codigo"].'" userrol="3" estado="'.$estado["estado_id"].'" class="estado_editable label label-info m-t-lg">'.$estado["estado"].' '.number_format($estado["avance"],0).'%</span>';
                                                break;
                                            case 3:
                                                $estado_html = '<span style="background-color: #3a3d5c;" codigo="'.$estado["codigo"].'" userrol="3" estado="'.$estado["estado_id"].'" class="estado_editable label label-success m-t-lg">'.$estado["estado"].'</span>';
                                                break;
                                            case 4:
                                                $estado_html = '<span style="background-color: #7583a0;" codigo="'.$estado["codigo"].'" userrol="3" estado="'.$estado["estado_id"].'" class="estado_editable label label-danger m-t-lg">'.$estado["estado"].'</span>';
                                                break;
                                        } 
                                    } 
                                ?>
                                <td class="text-center" atributo="calidad" style="vertical-align: middle; width: 5%;"><?php echo $estado_html; ?></td>
                                
                                <?php 
                                    $estado_html = '<span style="background-color: #ffffff00; border: 1px solid #AAA;" class="label  m-t-lg">NO CORRESPONDE</span>';                                                
                                    $estado_html = '<span style="background-color: #ffffff00;" class=" label  m-t-lg">...</span>';    
                                    foreach($estados as $estado) { 
                                        if ($estado["ot_prod_id"] != $prod["codigo"]){
                                            continue;
                                        }
                                        if ($estado["gerencia"] != 1){
                                            continue;
                                        }
                                        switch($estado["estado_id"]){
                                            case 1:
                                                $estado_html = '<span style="background-color: #ea4d3b;" codigo="'.$estado["codigo"].'" userrol="2" estado="'.$estado["estado_id"].'" class="estado_editable label label-warning m-t-lg">'.$estado["estado"].'</span>';
                                                break;
                                            case 2:
                                                $estado_html = '<span style="background-color: #ff899f;" codigo="'.$estado["codigo"].'" userrol="2" estado="'.$estado["estado_id"].'" class="estado_editable label label-info m-t-lg">'.$estado["estado"].' '.number_format($estado["avance"],0).'%</span>';
                                                break;
                                            case 3:
                                                $estado_html = '<span style="background-color: #3a3d5c;" codigo="'.$estado["codigo"].'" userrol="2" estado="'.$estado["estado_id"].'" class="estado_editable label label-success m-t-lg">'.$estado["estado"].'</span>';
                                                break;
                                            case 4:
                                                $estado_html = '<span style="background-color: #7583a0;" codigo="'.$estado["codigo"].'" userrol="2" estado="'.$estado["estado_id"].'" class="estado_editable label label-danger m-t-lg">'.$estado["estado"].'</span>';
                                                break;
                                        } 
                                    } 
                                ?>
                                <td class="text-center" atributo="gerencia" style="vertical-align: middle; width: 5%;"><?php echo $estado_html; ?></td>

                                <td class="text-center noExl" style="vertical-align: middle;  width: 5%;">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-primary dropdown-toggle nuevo" id="menu" type="button" data-toggle="dropdown"  style="font-size: 10px;height: 15px;">
                                            <div class="opciones" style="margin-top: -6px">Opciones <span class="caret"></span></div>
                                        </button>
                                        <ul class="dropdown-menu" role="menu" aria-labelledby="menu">                                        
                                            <?php if ($reg["finalizada"] == 1){?>
                                                <li role="presentation" class="editprod_archivo"><a role="menuitem" tabindex="-1" href="#">Archivos</a></li>
                                                <li role="presentation" class="editprod_detalle"><a role="menuitem" tabindex="-1" href="#">LOG Eventos</a></li>
                                            <?php } else { ?>
                                                <?php if ($prod["standar"] == 1){?>
                                                    <li role="presentation" class="configprod_archivo"><a role="menuitem" tabindex="-1" href="#">Configuracion</a></li>
                                                <?php } ?>
                                                <li role="presentation" class="editprod_archivo"><a role="menuitem" tabindex="-1" href="#">Archivos</a></li>
                                                <li role="presentation" class="editprod_detalle"><a role="menuitem" tabindex="-1" href="#">LOG Eventos</a></li>
                                                <?php if ($_SESSION["rol"] == 1) { ?>
                                                    <li role="presentation" class="restablecerprod_detalle"><a role="menuitem" tabindex="-1" href="#">Restablecer</a></li>
                                                <?php } ?>                                                
                                            <?php } ?>
                                        </ul>
                                    </div>
                                </td>            
                            </tr>
                        <?php } ?>

                    <?php } ?>
                <?php } ?>
            <?php } ?>
    </tbody>
</table>

<script>
    var requestSent = false;

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

    $(".editprod_archivo").click(function () {
        codigo = $(this).closest('tr').attr("codigo");
        window.location.href = "files_otp.php?readonly=1&opc="+codigo;
    });

    $(".editprod_detalle").click(function () {
        codigo = $(this).closest('tr').attr("codigo");
        window.location.href = "eventos_otp.php?opc="+codigo;
    });
    
    $(".finalizarOtDetalle_listado").click(function () {
        codigo = $(this).closest('tr').attr("codigo");
        var parametros = {
            funcion: "finalizarOtDetalle_listado",
            codigo: codigo
        }
        $.ajax({
            type: "POST",
            url: 'controller/ot_listados.controller.php',
            data: parametros,
            success: function (data) {
                location.reload();
            },
            error: function () {
                alert("Error");
            }
        });
        //event.preventDefault();
        $('#dataUpdate').modal('show');
    });

    $(".abrirOtDetalle_listado").click(function () {
        codigo = $(this).closest('tr').attr("codigo");
        var parametros = {
            funcion: "abrirOtDetalle_listado",
            codigo: codigo
        }
        $.ajax({
            type: "POST",
            url: 'controller/ot_listados.controller.php',
            data: parametros,
            success: function (data) {
                location.reload();
            },
            error: function () {
                alert("Error");
            }
        });
    });

    $(".estado_editable").each(function () {
        var estado = $(this).attr("estado");
        var destino = $(this).attr("destino");
        var userrol = $(this).attr("userrol");
        var code = $(this).attr("codigo");
        var rol = $(".container").attr("rol");
        var destinos = $(".container").attr("destinos");
        $(this).removeAttr("modificable");
        if (rol == 1 || userrol == rol){
            if (rol != 2) {
                $(this).css("cursor", "pointer");
                $(this).attr("modificable", "1");
            } else {
                if (destinos.indexOf(","+destino+",") >= 0){
                    $(this).css("cursor", "pointer");
                    $(this).attr("modificable", "1");
                }
            }
        }
        //console.log(destinos);
    });

    

    $(".estado_editable").click(function () {

        codigo = $(this).closest('tr').attr("codigo");
        finalizada = $(this).closest('tr').attr("finalizada");
        if (finalizada == 1){
            return false;
        }

        var estado = $(this).attr("estado");
        var destino = $(this).attr("destino");
        var userrol = $(this).attr("userrol");
        code = $(this).attr("codigo");
        var rol = $(".container").attr("rol");
        var destinos = $(".container").attr("destinos");
        var destinos = $("#tabla").attr("destinos");
        
        if (!$(this).attr("modificable") == 1){
            return false;
        }

        atributo = $(this).closest('td').attr("atributo");   

        var rol = $(".container").attr("rol");

        if (atributo == "produccion") {
            if (!(destinos.indexOf(","+destino+",") >= 0)) {
                return false;
            } else {
                //alert("ok");
            }
        }
        
        var parametros = {
            funcion: "getOt_produccionEstado",
            atributo: atributo,
            codigo: codigo,
            destino: destino
        }
        $.ajax({
            type: "POST",
            url: 'controller/ot_produccions.controller.php',
            data: parametros,
            success: function (data) {
                var datos = JSON.parse(data);
                console.log(datos);
                $('#avanceUpdate').val(datos.avance);
                $('#estadoUpdate').val(datos.estado_id);
                $(".div_alcance").css("display", "none");
                $(".div_plano").css("display", "none");
                $(".opcion_estado").css("display", "block");
                if (datos.estado_id == 1){
                    $('#avanceUpdate').val(10);
                    $('#estadoUpdate').val(2);
                    $(".opcion_estado").css("display", "none");
                    $(".opcion_estado[estado='2']").css("display", "block");
                    $("#estadoUpdate").change();                    
                    $('#dataUpdate').modal('show');
                    
                }
                if (datos.estado_id == 2){
                    $('#avanceUpdate').val(datos.avance);
                    $('#estadoUpdate').val(datos.estado_id);
                    $(".opcion_estado").css("display", "none");
                    $(".opcion_estado[estado='2']").css("display", "block");
                    $(".opcion_estado[estado='3']").css("display", "block");
                    $(".opcion_estado[estado='4']").css("display", "block");
                    $("#estadoUpdate").change();
                    $('#dataUpdate').modal('show');
                }
                if (datos.estado_id == 4){
                    $('#avanceUpdate').val(0);
                    $('#estadoUpdate').val(1);
                    $(".opcion_estado").css("display", "none");
                    $(".opcion_estado[estado='1']").css("display", "block");
                    $("#estadoUpdate").change();
                    $('#dataUpdate').modal('show');
                }
                if (datos.ingenieria == 1){
                    $(".div_alcance").css("display", "block");
                    $(".div_plano").css("display", "block");
                    $('#alcanceUpdate').val(datos.ing_alcance);
                    $('#planoUpdate').val(datos.ing_planos);
                }
            },
            error: function () {
                alert("Error");
            }
        });
        //event.preventDefault();
    });

    $(".restablecerprod_detalle").click(function (){

        codigo = $(this).closest('tr').attr("codigo");
        
        var parametros = {
            funcion: "restablecerOt_produccion",
            codigo: codigo
        }
        $.ajax({
            type: "POST",
            url: 'controller/ot_produccions.controller.php',
            data: parametros,
            success: function (data) {
                location.reload();
            },
            error: function () {
                alert("Error");
            }
        });
    });
    
    $(".configprod_archivo").click(function () {
        codigo = $(this).closest('tr').attr("codigo");
        window.location.href = "ot_detalle_standar_view.php?opc="+codigo;
    });

</script>
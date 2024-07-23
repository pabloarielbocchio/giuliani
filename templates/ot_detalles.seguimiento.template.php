<style>

.divespecial {
  max-height: 75%;
  overflow: scroll;
  position: relative;
}

table {
  position: relative;
  border-collapse: collapse;
}

tr {
  min-height: 15em;
}

td {
  min-width: 08em;
  border: 1px solid #DEDEDE;
}

td,
th {
  padding: 0.1em;
}

thead th {
  position: -webkit-sticky; /* for Safari */
  position: sticky;
  background: #DEDEDE;
  top: 0;
}

thead th:first-child {
  left: 0;
  z-index: 3;
}

thead th{
  z-index: 1;
}

/*
._opciones {
  position: -webkit-sticky; 
  position: sticky;
  right: 0;
  left: 0;
  min-width: 10em;
  background: #DEDEDE;
  border-left: 1px solid #CCC;
}*/

.nombre {
  position: -webkit-sticky; /* for Safari */
  position: sticky;
  right: 0;
  left: 0;
  min-width: 45em;
  z-index: 2;
  background: #f1f1f1;
  font-family: 'montserrat';
  font-weight: normal;
  border: 1px solid #CCC;
}

.unidad {
  position: -webkit-sticky; /* for Safari */
  position: sticky;
  right: 0;
  left: 45em;
  min-width: 8em;
  z-index: 2;
  background: #f1f1f1;
  font-family: 'montserrat';
  font-weight: normal;
  border: 1px solid #CCC;
}

.observaciones {
  position: -webkit-sticky; /* for Safari */
  position: sticky;
  min-width: 45em;
  font-family: 'montserrat';
  font-weight: normal;
  border-right: 1px solid #CCC;
}

</style>

<div class="divespecial m-t-lg">
  <table id="tabla" namefile="Ot_detalles" class="table table-striped table-hover" rol_administrador="<?php echo intval($_SESSION["rol_administrador"]); ?>" >
    <thead>
        <tr class="row ">
            <th class="text-left nombre" orderby="item_vendido" sentido="asc"> </th>
            <th class="text-center unidad" orderby="cantidad" sentido="asc">Cantidad</th>
            <th class="text-center " orderby="estado" sentido="asc"></th>
            <th class="text-center " orderby="seccion" sentido="asc"></th>
            <?php foreach($destinos as $destino) { ?>
                <th class="text-center " orderby="item_vendido" sentido="asc"><?php echo $destino["descripcion"]; ?></th>
            <?php } ?>
            <th class="text-center " orderby="observaciones" sentido="asc">Observaciones</th>
        </tr>
    </thead>
    <tbody id="body">   
                <?php foreach ($secciones as $secc) { ?>
                    <tr class="row seccion" codigo="<?php echo $secc["codigo"]; ?>">
                        <th class="text-left nombre" style="vertical-align: middle;"><?php echo "SECCION: " . $secc["descripcion"]; ?></th>
                        <th class="text-center unidad" style="vertical-align: middle;"></th>   
                        <th class="text-center _opciones" style="vertical-align: middle; z-index: 100;"></th>   
                        <td class="text-center" style="vertical-align: middle; "></td>   
                        <?php foreach($destinos as $destino) { ?>
                            <td class="text-center" style="vertical-align: middle; "></td>                
                        <?php } ?>  
                        <th class="text-center observaciones" style="vertical-align: middle;"></th>   
                    </tr>  
                           
                    <?php foreach ($secc["sectores"] as $sect) { ?>  
                        <tr class="row sector" codigo="<?php echo $sect["codigo"]; ?>">              
                            <th class="text-left nombre " style="vertical-align: middle; padding-left: 10px;"><?php echo "SECTOR: " . $sect["descripcion"]; ?></th>
                            <th class="text-center unidad" style="vertical-align: middle; "></th>   
                            <th class="text-center _opciones noExl" style="vertical-align: middle;  z-index: 100;"></th>       
                            <td class="text-center" style="vertical-align: middle; "></td>  
                            <?php foreach($destinos as $destino) { ?>
                                <td class="text-center" style="vertical-align: middle; "></td>                
                            <?php } ?> 
                            <th class="text-center observaciones" style="vertical-align: middle;"></th>  
                        </tr>  

                        <?php foreach ($sect["registros"] as $reg) { ?>  
                            <tr class="row registro" codigo="<?php echo $reg["codigo"]; ?>" finalizada="<?php echo intval($reg["finalizada"]); ?>" >                                  
                                <th class="text-left nombre " 
                                data-toggle="tooltip" title="<?php echo "PU: " . $reg["pu"] . " | PU_CANT: " . $reg["pu_cant"] . " | PU_NETO: " . $reg["pu_neto"] . " | CLASIFICACION: " . $reg["clasificacion"]; ?> "
                                style="vertical-align: middle; padding-left: 20px;"><?php echo $reg["item_vendido"]; ?></th>
                                <th class="text-center unidad" style="vertical-align: middle; "><?php echo $reg["cantidad"]; ?></th>   
                                <th class="text-center _opciones noExl" style="vertical-align: middle;  z-index: 100;">
                                <?php //if ($_SESSION["rol"] == 1 or $_SESSION["rol"] == 2) { ?>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-primary dropdown-toggle nuevo" id="menu" type="button" data-toggle="dropdown"  style="font-size: 10px;height: 15px;">
                                            <div class="opciones" style="margin-top: -6px">Opciones <span class="caret"></span></div>
                                        </button>
                                        <ul class="dropdown-menu" role="menu" aria-labelledby="menu">
                                            <!--<li role="presentation" class="editprodotd_archivo"><a role="menuitem" tabindex="-1" href="#">Archivos</a></li>-->
                                                    
                                        <?php 
                                            if ($reg["finalizada"] == 1){
                                                if ($_SESSION["rol_finalizar_ot"] == 1){
                                                    echo '<li role="presentation" class="abrirOtDetalle_listado"><a role="menuitem" tabindex="-1" href="#">Reabrir</a></li>';
                                                }
                                            } elseif ($reg["finalizada"] != null and $reg["finalizada"] == 0){                                            
                                                if ($_SESSION["rol_finalizar_ot"] == 1){
                                                    echo '<li role="presentation" class="finalizarOtDetalle_listado"><a role="menuitem" tabindex="-1" href="#">Finalizar</a></li>';
                                                }
                                            } else {
                                                echo '<li role="presentation" class="abrirOtDetalle_listado"><a role="menuitem" tabindex="-1" href="#">Iniciar</a></li>';
                                            }
                                        ?>
                                        </ul>
                                    </div>
                                <?php //} ?>
                                </th>  
                                <td class="text-center" style="vertical-align: middle; ">
                                <?php 
                                    if ($reg["finalizada"] == 1){
                                        echo '<span class="label label-danger m-t-lg" style="width: 100%; background-color: #65b399; color: #EEE;">FINALIZADA</span>';
                                    } elseif ($reg["finalizada"] != null and $reg["finalizada"] == 0){ 
                                        echo '<span class="label label-danger m-t-lg" style="width: 100%; background-color: #9ec569; color: #eee;">EN CURSO</span>';
                                    } else {
                                        echo '<span class="label label-danger m-t-lg" style="width: 100%; background-color: #Cec569; color: #eee;">EN COLA</span>';
                                    }
                                ?>
                                </td>   
                                <?php foreach($destinos as $destino) { ?>
                                    <td class="text-center" style="vertical-align: middle; "></td>                
                                <?php } ?>     
                                <th class="text-center observaciones" style="vertical-align: middle;text-align: justify !important;"><?php echo $reg["observaciones"]; ?></th>  
                            </tr>   
                            
                            <?php 
                                foreach ($prods as $prod) { 
                                    $_prod = $prod;
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
                                    <th class="text-left nombre" style="vertical-align: middle; padding-left: 30px;">
                                        <?php 
                                            if ($prod["standar"] == 1){
                                                echo $prod["numero"] . " - " . "STD: ";
                                                echo $prod["prod_standar"] . " (" . $prod["cantidad"] . ")"; 
                                            } else {
                                                echo $prod["numero"] . " - " . $prod["prod_personalizado"] . " (" . $prod["cantidad"] . " " . $prod["unidad"] . ")"; 
                                            }
                                        ?>
                                    </th>
                                    <th class="text-center unidad" style="vertical-align: middle; "><?php echo $prod["cantidad"]; ?></th>   
                                    <th class="text-center _opciones noExl" style="vertical-align: middle;  z-index: 100; ">
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
                                    </th>          
                                    <td class="text-center" style="vertical-align: middle; ">
                                        <?php 
                                            if ($prod["standar"] == 1){
                                                echo '<span class="label label-danger m-t-lg" style="width: 100%; background-color: #fdb25d; color: #eee;">STD</span>';
                                            } else {
                                                echo '<span class="label label-danger m-t-lg" style="width: 100%; background-color: #fe795f; color: #eee;">CTM</span>';
                                            }
                                        ?>
                                    </td>   
                                    <?php 
                                    
                                    // Aca comienzan los cartelitos APRO
                                    $aprob_calidad = 0;
                                    foreach($destinos as $destino) {      
                                        foreach($estados as $estado) {  
                                            if ($estado["ot_prod_id"] != $prod["codigo"]){
                                                continue;
                                            }
                                            if ($estado["destino_id"] != $destino["codigo"]){
                                                continue;
                                            }  
                                            if ($destino["calidad"] == 1 and $estado["estado_id"] == 3) {
                                                $aprob_calidad = 1;
                                            }  
                                        }
                                    }

                                    foreach($destinos as $destino) {
                                        if ($_SESSION["rol"] == 1){
                                            $modificable = 1;
                                        } else {
                                            $modificable = 0; 
                                            foreach($menu_user_destinos as $m){
                                                if ($destino["codigo"] == $m["destino_id"] and $m["permiso"] > 1){
                                                    $modificable = 1;
                                                }
                                            }
                                        }

                                        $estado_editable = "estado_editable";
                                        if ($aprob_calidad == 1 and $destino["calidad"] != 1){
                                            $modificable = 0;
                                            $estado_editable = "";
                                        }
                                    ?>
                                        <?php 
                                            // Aca hay que aplicar la logica que si es siempre visible, entonces lo muestre siempre, sino hay que ver si los destinos tienen archivos.
                                            if($destino["siempre_visible"] == 1){
                                                $estado_html = '<span modificable="' . $modificable . '" style="background-color: #CECECE;" codigo="0" userrol="4" destino="'.$destino["codigo"].'" class="'.$estado_editable.' label label-danger m-t-lg">EN COLA</span>';            
                                            } else {
                                                if ($_prod["destinos_cuenta"][$destino["codigo"]] > 0){
                                                    $estado_html = '<span modificable="' . $modificable . '" style="background-color: #CECECE;" codigo="0" userrol="4" destino="'.$destino["codigo"].'" class="'.$estado_editable.' label label-danger m-t-lg">EN COLA</span>';            
                                                } else {
                                                    $estado_html = '';
                                                }
                                            }
                                            //$estado_html = '<span modificable="' . $modificable . '" style="background-color: #CECECE;" codigo="0" userrol="4" destino="'.$destino["codigo"].'" class="estado_editable label label-danger m-t-lg">EN COLA</span>';            
                                            $obs = null;
                                            foreach($estados as $estado) {  
                                                if ($estado["ot_prod_id"] != $prod["codigo"]){
                                                    continue;
                                                }
                                                if ($estado["destino_id"] != $destino["codigo"]){
                                                    continue;
                                                }                                                
                                                $obs = $estado["observaciones"];
                                                switch($estado["estado_id"]){ 
                                                    case 1:
                                                        $estado_html = '<span modificable="' . $modificable . '"  style="background-color: #ea4d3b;" codigo="'.$estado["codigo"].'" userrol="4" destino="'.$estado["destino_id"].'" estado="'.$estado["estado_id"].'" class="'.$estado_editable.' label label-warning m-t-lg">'.$estado["estado"].'</span>';
                                                        break;
                                                    case 2:
                                                        $estado_html = '<span modificable="' . $modificable . '"  style="background-color: #ff899f;" codigo="'.$estado["codigo"].'" userrol="4" destino="'.$estado["destino_id"].'" estado="'.$estado["estado_id"].'" class="'.$estado_editable.' label label-info m-t-lg">'.$estado["estado"].' '.number_format($estado["avance"],0).'%</span>';
                                                        break;
                                                    case 3:
                                                        $estado_html = '<span modificable="' . $modificable . '"  style="background-color: #3a3d5c;" codigo="'.$estado["codigo"].'" userrol="4" destino="'.$estado["destino_id"].'" estado="'.$estado["estado_id"].'" class="'.$estado_editable.' label label-success m-t-lg">'.$estado["estado"].'</span>';
                                                        break;
                                                    case 5:
                                                        $estado_html = '<span modificable="' . $modificable . '"  style="background-color: #3a3d5c;" codigo="'.$estado["codigo"].'" userrol="4" destino="'.$estado["destino_id"].'" estado="'.$estado["estado_id"].'" class="'.$estado_editable.' label label-success m-t-lg">'.$estado["estado"].'</span>';
                                                        break;
                                                    case 4:
                                                        $estado_html = '<span modificable="' . $modificable . '"  style="background-color: #7583a0;" codigo="'.$estado["codigo"].'" userrol="4" destino="'.$estado["destino_id"].'" estado="'.$estado["estado_id"].'" class="'.$estado_editable.' label label-danger m-t-lg">'.$estado["estado"].'</span>';
                                                        break;
                                                } 
                                            } 
                                        ?>
                                        <td 
                                        data-toggle="tooltip" title="<?php echo $obs; ?> "  estado="<?php echo $estado["estado_id"]; ?>"
                                        class="text-center" atributo="produccion" style="vertical-align: middle; "><?php echo $estado_html; ?></td>
                                    <?php } ?>
                                    
                                    <th class="text-center observaciones" style="vertical-align: middle; text-align: justify !important;"><?php echo $prod["observaciones"]; ?></th>  
                                </tr>
                            <?php } ?>

                        <?php } ?>

                    <?php } ?>
                <?php } ?>
        </tbody>
  </table>
</div>


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

    $(".editprodotd_archivo").click(function () {
        codigo = $(this).closest('tr').attr("codigo");
        window.location.href = "files_otd.php?readonly=1&opc="+codigo;
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
        $(this).css("cursor", "pointer");
        //$(this).removeAttr("modificable");
        //$(this).attr("modificable", "1");
        /*if (rol == 1 || userrol == rol){
            if (rol != 2) {
                $(this).css("cursor", "pointer");
                $(this).attr("modificable", "1");
            } else {
                if (destinos.indexOf(","+destino+",") >= 0){
                    $(this).css("cursor", "pointer");
                    $(this).attr("modificable", "1");
                }
            }
        }*/
        //console.log(destinos);
    });

    

    $(".estado_editable").click(function () {
        
        var scrolly = $(".divespecial").scrollTop();
        var scrollx = $(".divespecial").scrollLeft();  
        $("#div_tabla").attr("scrollx", scrollx);    
        $("#div_tabla").attr("scrolly", scrolly);  

        codigo = $(this).closest('tr').attr("codigo");
        finalizada = $(this).closest('tr').attr("finalizada");
        if (finalizada == 1){
            return false;
        }

        estado_id = $(this).attr("estado");   
        var estado = $(this).attr("estado");
        destino = $(this).attr("destino");
        var userrol = $(this).attr("userrol");
        code = $(this).attr("codigo");
        var rol = $(".container").attr("rol");
        var destinos = $(".container").attr("destinos");
        var destinos = $("#tabla").attr("destinos");
        //alert($(this).attr("modificable"));
        if ($(this).attr("modificable") == 0){
            return false;
        }

        atributo = $(this).closest('td').attr("atributo");   

        var rol = $(".container").attr("rol");
        /*
        if (atributo == "produccion") {
            if (!(destinos.indexOf(","+destino+",") >= 0)) {
                return false;
            } else {
                //alert("ok");
            }
        }
        */
        var parametros = {
            funcion: "getOt_produccionEstado",
            atributo: atributo,
            codigo: codigo,
            destino: destino,
            estado_id: estado_id,
            code: code
        }
        var rol_administrador = $("#tabla").attr("rol_administrador");

        console.log(rol_administrador);
        console.log(estado_id);
        console.log(parametros);
        $(".div_cambio_apro").css("display","none");
        //Cambio de estado de aprobado
        if (estado_id == 3){
            $(".div_cambio_apro").css("display","block");
        }
        if (estado_id == 3 && rol_administrador != 1) {
            console.log("entra");
            return false;
        }
        $.ajax({
            type: "POST",
            url: 'controller/ot_produccions.controller.php',
            data: parametros,
            success: function (data) {
                var datos = JSON.parse(data);
                console.log(datos);
                if (datos) {
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
                        
                    }
                    if (datos.estado_id == 2){
                        $('#avanceUpdate').val(datos.avance);
                        $('#estadoUpdate').val(datos.estado_id);
                        $(".opcion_estado").css("display", "none");
                        $(".opcion_estado[estado='2']").css("display", "block");
                        $(".opcion_estado[estado='3']").css("display", "block");
                        $(".opcion_estado[estado='4']").css("display", "block");
                        $("#estadoUpdate").change();
                    }
                    if (datos.estado_id == 4){
                        $('#avanceUpdate').val(0);
                        $('#estadoUpdate').val(1);
                        $(".opcion_estado").css("display", "none");
                        $(".opcion_estado[estado='1']").css("display", "block");
                        $("#estadoUpdate").change();
                    }
                }       
                $('#dataUpdate').modal('show');
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

    $(document).ready(function() {
        // Almacenar la posición del scroll al salir de la página
        $('#tabla').on('scroll', function() {
            localStorage.setItem('scrollPositionTablaSeguimiento', $(this).scrollTop());
        });
        // Recuperar la posición del scroll al cargar la página
        var scrollPosition = localStorage.getItem('scrollPositionTablaSeguimiento');
        if (scrollPosition !== null) {
            $('#tabla').scrollTop(scrollPosition);
        }
    });
</script>
<div class=" m-t-lg">
  <table id="tabla" namefile="Ot_detalles" class="table table-striped table-hover" >
    <thead>
        <tr class="row ">
            <th class="text-left " orderby="item_vendido" sentido="asc">Archivos OT </th>
            <th class="text-center " orderby="estado" sentido="asc"></th>
            <th class="text-center " orderby="seccion" sentido="asc"></th>
        </tr>
        
        <?php foreach ($archivos as $a) { if ($a["ot_id"] != $ot) { continue; } ?>    
        <tr class="row "
            codigo="<?php echo $a["codigo"]; ?>" 
            ruta="<?php echo $a["ruta"]; ?>"
            archivo="<?php echo $a["descripcion"]; ?>"
        >
            <td class="text-left " orderby="item_vendido" sentido="asc"><?php echo $a["descripcion"]; ?></td>
            <td class="text-center" style="vertical-align: middle; width: 10%;">
                <?php 
                    switch ($a["activo"]){
                        case -1:
                            echo '<span style="cursor: pointer;" estado="-1" archivo="'.$a["cod_archivo"].'" class="estado_editable label label-danger m-t-lg">Inactivo</span>';
                            break;
                        case 0:
                            echo'<span style="cursor: pointer;" estado="0" archivo="'.$a["cod_archivo"].'" class="estado_editable label label-warning m-t-lg" >En Proceso</span>';
                            break;
                        case 1:
                            echo '<span style="cursor: pointer;" estado="1"  archivo="'.$a["cod_archivo"].'" class="estado_editable label label-info m-t-lg">Activo</span>';
                            break;
                    }
                ?>
            </td>
            
            <td class="text-left" style="vertical-align: middle;">
                <div class="dropdown">
                    <button class="btn btn-sm btn-primary dropdown-toggle nuevo" id="menu" type="button" data-toggle="dropdown"  style="font-size: 10px;height: 15px;">
                        <div class="opciones" style="margin-top: -6px">Opciones <span class="caret"></span></div>
                    </button>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="menu">
                        <li role="presentation" class="ver"><a role="menuitem" tabindex="-1" href="#">Ver!</a></li>
                        <li role="presentation" class="descargar"><a role="menuitem" tabindex="-1" href="#">Descargar</a></li>
                    </ul>
                </div>
            </td>
        </tr>
        <?php } ?>

        <tr class="row ">
            <th class="text-left " orderby="item_vendido" sentido="asc"> </th>
            <th class="text-center " orderby="estado" sentido="asc"></th>
            <th class="text-center " orderby="seccion" sentido="asc"></th>
        </tr>
    </thead>
    <tbody id="body">   
                <?php foreach ($secciones as $secc) { ?>
                    <tr class="row seccion" codigo="<?php echo $secc["codigo"]; ?>">
                        <th class="text-left nombre" style="vertical-align: middle;"><?php echo "SECCION: " . $secc["descripcion"]; ?></th>
                        <th class="text-center _opciones" style="vertical-align: middle; z-index: 100;"></th>   
                        <td class="text-center" style="vertical-align: middle; "></td>   
                    </tr>  
                           
                    <?php foreach ($secc["sectores"] as $sect) { ?>  
                        <tr class="row sector" codigo="<?php echo $sect["codigo"]; ?>">              
                            <th class="text-left nombre " style="vertical-align: middle; padding-left: 10px;"><?php echo "SECTOR: " . $sect["descripcion"]; ?></th>
                            <th class="text-center _opciones" style="vertical-align: middle;  z-index: 100;"></th>       
                            <td class="text-center" style="vertical-align: middle; "></td>  
                        </tr>  

                        <?php foreach ($sect["registros"] as $reg) { ?>  
                            <tr class="row registro" codigo="<?php echo $reg["codigo"]; ?>" finalizada="<?php echo intval($reg["finalizada"]); ?>" >                                  
                                <th class="text-left nombre " style="vertical-align: middle; padding-left: 20px;"><?php echo $reg["item_vendido"]; ?></th>
                                <th class="text-center _opciones" style="vertical-align: middle;  z-index: 100;">
                                <th class="text-center _opciones" style="vertical-align: middle;  z-index: 100;">
                                   
                            </tr>   

                            <?php foreach ($archivos as $a) { if ($a["ot_detalle_id"] != $reg["codigo"]) { continue; } ?>   
                                <tr class="row "
                                    codigo="<?php echo $a["codigo"]; ?>" 
                                    ruta="<?php echo $a["ruta"]; ?>"
                                    archivo="<?php echo $a["descripcion"]; ?>"
                                >
                                    <td class="text-left " style="padding-left: 20px;" orderby="item_vendido" sentido="asc"><?php echo $a["descripcion"]; ?></td>
                                    <td class="text-center" style="vertical-align: middle; width: 10%;">
                                        <?php 
                                            switch ($a["activo"]){
                                                case -1:
                                                    echo '<span style="cursor: pointer;" estado="-1" archivo="'.$a["cod_archivo"].'" class="estado_editable label label-danger m-t-lg">Inactivo</span>';
                                                    break;
                                                case 0:
                                                    echo'<span style="cursor: pointer;" estado="0" archivo="'.$a["cod_archivo"].'" class="estado_editable label label-warning m-t-lg" >En Proceso</span>';
                                                    break;
                                                case 1:
                                                    echo '<span style="cursor: pointer;" estado="1"  archivo="'.$a["cod_archivo"].'" class="estado_editable label label-info m-t-lg">Activo</span>';
                                                    break;
                                            }
                                        ?>
                                    </td>
                                    
                                    <td class="text-left" style="vertical-align: middle;">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-primary dropdown-toggle nuevo" id="menu" type="button" data-toggle="dropdown"  style="font-size: 10px;height: 15px;">
                                                <div class="opciones" style="margin-top: -6px">Opciones <span class="caret"></span></div>
                                            </button>
                                            <ul class="dropdown-menu" role="menu" aria-labelledby="menu">
                                                <li role="presentation" class="descargar"><a role="menuitem" tabindex="-1" href="#">Descargar</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?> 
                            
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
                                    <th class="text-left nombre" style="vertical-align: middle; padding-left: 30px;">
                                        <?php 
                                            if ($prod["standar"] == 1){
                                                echo $prod["numero"] . " - " . "STD: ";
                                                echo $prod["prod_standar"] . " (" . $prod["cantidad"] . " " . $prod["unidad"] . ")"; 
                                            } else {
                                                echo $prod["numero"] . " - " . $prod["prod_personalizado"] . " (" . $prod["cantidad"] . " " . $prod["unidad"] . ")"; 
                                            }
                                        ?>
                                    </th>
                                    <td class="text-center _opciones" style="vertical-align: middle;  z-index: 100;"></td>
                                    <td class="text-center _opciones" style="vertical-align: middle;  z-index: 100;"></td>
                                </tr>

                                

                                <?php foreach ($archivos as $a) { if ($a["ot_produccion_id"] != $prod["codigo"]) { continue; } ?>   
                                    <tr class="row "
                                        codigo="<?php echo $a["codigo"]; ?>" 
                                        ruta="<?php echo $a["ruta"]; ?>"
                                        archivo="<?php echo $a["descripcion"]; ?>"
                                    >
                                        <td class="text-left " style="padding-left: 30px;" orderby="item_vendido" sentido="asc"><?php echo $a["descripcion"]; ?></td>
                                        <td class="text-center" style="vertical-align: middle; width: 10%;">
                                            <?php 
                                                switch ($a["activo"]){
                                                    case -1:
                                                        echo '<span style="cursor: pointer;" estado="-1" archivo="'.$a["cod_archivo"].'" class="estado_editable label label-danger m-t-lg">Inactivo</span>';
                                                        break;
                                                    case 0:
                                                        echo'<span style="cursor: pointer;" estado="0" archivo="'.$a["cod_archivo"].'" class="estado_editable label label-warning m-t-lg" >En Proceso</span>';
                                                        break;
                                                    case 1:
                                                        echo '<span style="cursor: pointer;" estado="1"  archivo="'.$a["cod_archivo"].'" class="estado_editable label label-info m-t-lg">Activo</span>';
                                                        break;
                                                }
                                            ?>
                                        </td>
                                        
                                        <td class="text-left" style="vertical-align: middle;">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-primary dropdown-toggle nuevo" id="menu" type="button" data-toggle="dropdown"  style="font-size: 10px;height: 15px;">
                                                    <div class="opciones" style="margin-top: -6px">Opciones <span class="caret"></span></div>
                                                </button>
                                                <ul class="dropdown-menu" role="menu" aria-labelledby="menu">
                                                    <li role="presentation" class="descargar"><a role="menuitem" tabindex="-1" href="#">Descargar</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?> 
                            <?php } ?>

                        <?php } ?>

                    <?php } ?>
                <?php } ?>
        </tbody>
  </table>
</div>

<script>
    
    $(".descargar").click(function () {
        codigo = $(this).closest('tr').attr("codigo");
        ruta = $(this).closest('tr').attr("ruta");
        archivo = $(this).closest('tr').attr("archivo");                          
        var link=document.createElement('a');
        document.body.appendChild(link);
        link.download=archivo;
        link.href=ruta;
        link.click();
    });

    $(".ver").click(function () {
        codigo = $(this).closest('tr').attr("codigo");                        
        console.log(codigo);
        var win = window.open('pdf.php?archivo='+codigo, '_blank');
        if (win) {
            win.focus();
        } else {
            alert('Please allow popups for this website');
        }
    });
</script>
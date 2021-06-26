<?php if (count($archivos) > 0) { ?>
    <table id="tabla" class="table table-striped table-hover"  style="margin-top: -<?php echo count($archivos)*40; ?>px;"> 
        <thead>
            <tr class="row " style="background-color: transparent;">
                <th  class="text-center " orderby="seccion" sentido="asc" style="width: 5%;">Nivel</th>      
                <th  class="text-left " orderby="seccion" sentido="asc">Archivo</th>
                <?php foreach($destinos as $destino) { ?>
                    <th class="text-center " orderby="item_vendido" sentido="asc"><?php echo $destino["descripcion"]; ?></th>
                <?php } ?>
                <th  class="text-center " orderby="seccion" sentido="asc" style="width: 15%;">Estado</th>
                <th  class="text-left " orderby="seccion" sentido="asc" style="width: 5%;"></th>            
            </tr>
        </thead>
        <tbody id="body">
            <?php 
                $usados = [];
                foreach ($archivos as $nivel => $archivo) { 
            ?>
                <?php 
                    foreach ($archivo as $usu) { 
                        if (in_array($usu["codigo"], $usados)){
                            continue;
                        }
                        $usados[] = $usu["codigo"];
                ?>
                    <tr class="row" 
                            codigo="<?php echo $usu["codigo"]; ?>" 
                            ruta="<?php echo $usu["ruta"]; ?>"
                            archivo="<?php echo $usu["archivo"]; ?>"
                        >
                        <td class="text-center" style="vertical-align: middle;"><?php echo $nivel; ?></td>
                        <td class="text-left" style="vertical-align: middle;"><?php echo $usu["descripcion"]; ?></td>
                        
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
                                    echo '<span class="glyphicon glyphicon-ok opcion ok" archivo="'.$usu["cod_archivo"].'" destino="'.$destino["codigo"].'" permitido="'.$permitido.'"  style="color: #0A0;  cursor: pointer;" aria-hidden="true"></span>';
                                } else {
                                    echo '<span class="glyphicon glyphicon-remove opcion nook" archivo="'.$usu["cod_archivo"].'" destino="'.$destino["codigo"].'" permitido="0" style="color: #A00; cursor: pointer; " aria-hidden="true"></span>';
                                }
                                ?>
                            </td>
                        <?php } ?>                        

                        <td class="text-center" style="vertical-align: middle;">
                            <?php 
                                switch ($usu["activo"]){
                                    case -1:
                                        echo "Inactivo";
                                        break;
                                    case 0:
                                        echo "En Proceso";
                                        break;
                                    case 1:
                                        echo "Activo";
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
        </tbody>
    </table>
<?php } ?>

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
</script>


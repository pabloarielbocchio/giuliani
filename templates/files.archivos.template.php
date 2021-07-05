<?php if (count($archivos)) { ?>
    <div class="monitoreos-lista-lotes" >                  
        <div class="ibox">
            <div id="lote-images">
                <div id="miniaturas" class="galeria-lotes">
                    <div>
                        <h3>Arhivos asociados</h3>
                    </div>
                    <?php foreach ($archivos as $archivo) { 
                        if ($grupo > 0){
                            if ($archivo["cod_prod_nf"] != $opc){
                                continue;
                            }
                        }
                    ?>
                        <div class="col-md-3">   
                            <li style="list-style: none">
                                <div class="ibox-galeria">
                                    <div class="ibox-content galeria-box">
                                        <div class="galeria-desc text-center">
                                            <small class="text-muted">
                                                <?php 
                                                    if (strlen($archivo["nombre"]) > 12) {
                                                        echo substr($archivo["nombre"],0,9) . "..."; 
                                                    } else {
                                                        echo $archivo["nombre"];
                                                    }
                                                ?>
                                            </small>
                                        </div>
                                        <div class="galeria-imitation download" descarga="<?php echo $archivo["ruta"]; ?>" nombre="<?php echo $archivo["nombre"]; ?>" style="cursor: pointer;">
                                            <?php if ($archivo["imagen"]) { ?>
                                                <img class="img-xlg" style="height:100px; width: 100px; margin-left: -17px;" src="<?php echo $archivo["ruta"]; ?>"/>
                                            <?php } else { ?>
                                                <i style="height:100px; width: 100px; font-size: 100px; margin-left: -7px;" class="fa fa-file-code-o"></i>
                                            <?php } ?>
                                        </div>
                                        <div class="galeria-desc text-center" style="cursor: pointer;">
                                            <?php if ($vista == 0) { ?>
                                                <small class="text-muted remove" archivo_id="<?php echo $archivo["codigo"]; ?>">
                                                    <i class="fa fa-remove"></i>
                                                </small>
                                            <?php } else { ?>
                                                <small class="text-muted ">
                                                    <i class="fa "></i>
                                                </small>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </div>
                    <?php } ?>
                </div>
            </div>        
        </div>
    </div>
<?php } ?>

<script>
    $(".remove").click(function () {
        archivo_id = $(this).attr("archivo_id");
        deleteFoto(archivo_id);
    });

    $(".download").click(function () {
        archivo = $(this).attr("descarga");
        nombre = $(this).attr("nombre");
        descargarArchivo(archivo, nombre);
    });
</script>

<link href="inspinia/css/plugins/dropzone/dropzone.css" rel="stylesheet">	

<div class="row">
    <div class="col-md-6">
        <div class="row">      
            <?php if ($pa != "") { ?>
                <div class="form-group">
                    <label for="nombre0" class="control-label">Nivel 1:</label>
                    <input type="text" class="form-control" id="nivel1" name="nivel1" value="<?php echo $pa; ?>" readonly>
                </div>
            <?php } ?>
            
            <?php if ($pb != "") { ?>
                <div class="form-group">
                    <label for="nombre0" class="control-label">Nivel 2:</label>
                    <input type="text" class="form-control" id="nivel2" name="nivel2" value="<?php echo $pb; ?>" readonly>
                </div>
            <?php } ?>
            
            <?php if ($pc != "") { ?>
                <div class="form-group">
                    <label for="nombre0" class="control-label">Nivel 3:</label>
                    <input type="text" class="form-control" id="nivel3" name="nivel3" value="<?php echo $pc; ?>" readonly>
                </div>
            <?php } ?>
            
            <?php if ($pd != "") { ?>
                <div class="form-group">
                    <label for="nombre0" class="control-label">Nivel 4:</label>
                    <input type="text" class="form-control" id="nivel4" name="nivel4" value="<?php echo $pd; ?>" readonly>
                </div>
            <?php } ?>
            
            <?php if ($pf != "") { ?>
                <div class="form-group">
                    <label for="nombre0" class="control-label">OPC: <?php echo $pe; ?></label>
                    <input type="text" class="form-control" id="nivel6" name="nivel6" value="<?php echo $pf; ?>" readonly>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="col-md-6">
        <fieldset>
            <div class="row">
                <div id="cont_archivos" class="col-md-12">          

                </div>
            </div>              
            <div class="row" style="margin-top: 10px;">
                <div class="col-md-12">
                    <form action="subir.php" class="dropzone" id="formdropZone">
                        <input type="text" class="form-control hidden" id="n1" name="n1" value="<?php echo $n1; ?>" />
                        <input type="text" class="form-control hidden" id="n2" name="n2" value="<?php echo $n2; ?>" />
                        <input type="text" class="form-control hidden" id="n3" name="n3" value="<?php echo $n3; ?>" />
                        <input type="text" class="form-control hidden" id="n4" name="n4" value="<?php echo $n4; ?>" />
                        <input type="text" class="form-control hidden" id="n5" name="n5" value="<?php echo $n5; ?>" />
                        <input type="text" class="form-control hidden" id="n6" name="n6" value="<?php echo $n6; ?>" />
                    </form>
                </div>
            </div>              
        </fieldset>
    </div>     
</div>     
<div class="row">
    <div class="col-md-12 div_tabla" scrollx="0" scrolly="0">
    </div>     
</div>     
    
<script src="inspinia/js/plugins/dropzone/dropzone.js"></script>

<script>
    Dropzone.autoDiscover = false;
    var myDropzone = new Dropzone("#formdropZone", { url: "subir.php"});        
    myDropzone.on("addedfile", function(file) {
        console.log(file);
    });
    myDropzone.on("success", function(file, responseText) {
        buscarArchivos();
        console.log(responseText);
    });
    myDropzone.on("complete", function(file) {
        console.log(file);
    });
    myDropzone.on("error", function(file, errorMessage) {
        console.log(file);
    });
    
    function buscarArchivos() {         
        var parametros = {
            funcion: "buscarArchivos",         
            select_n1: $('#select_n1').val(),
            select_n2: $('#select_n2').val(),
            select_n3: $('#select_n3').val(),
            select_n4: $('#select_n4').val(),
            opc: $(".container").attr("opc"),
            grupo: $(".container").attr("grupo")
        }
        console.log(parametros);        
        $.ajax({
            type: "POST",
            url: 'controller/productos.controller.php',
            data: parametros,
            success: function (datos) {
                $("#cont_archivos").html(datos);
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
    
    function buscarTabla() {         
        var parametros = {
            funcion: "buscarArchivosTabla",         
            select_n1: $('#select_n1').val(),
            select_n2: $('#select_n2').val(),
            select_n3: $('#select_n3').val(),
            select_n4: $('#select_n4').val(),
            opc: $(".container").attr("opc"),
            grupo: $(".container").attr("grupo")
        }
        console.log(parametros);        
        $.ajax({
            type: "POST",
            url: 'controller/productos.controller.php',
            data: parametros,
            success: function (datos) {
                $(".div_tabla").html(datos);
                $(".divespecial").scrollLeft($("#div_tabla").attr("scrollx"));
            },
            error: function () {
                alert("Error");
            },
            complete: function () {
                requestSent = false;
            }
        });
    }
    
    buscarArchivos();
    
    function deleteFoto(archivo_id = 0){
        var parametros = {
            funcion: "deleteArchivo",         
            codigo: archivo_id
        }
        console.log(parametros);        
        $.ajax({
            type: "POST",
            url: 'controller/archivos.controller.php',
            data: parametros,
            success: function (datos) {
                buscarArchivos();
            },
            error: function () {
                alert("Error");
            },
            complete: function () {
                requestSent = false;
            }
        });
    }
    
    function descargarArchivo(archivo, nombre){
        console.log(archivo);
        console.log(nombre);
        if (archivo){
            var link=document.createElement('a');
            document.body.appendChild(link);
            link.download=nombre;
            link.href=archivo;
            link.click();
        }
    }
</script>
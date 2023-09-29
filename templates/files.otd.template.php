
<link href="inspinia/css/plugins/dropzone/dropzone.css" rel="stylesheet">	
<legend>
    <h4>
    <?php 
        echo $otd["codigo"] ? "#" . $otd["codigo"] . " - " : "";
        echo $otd["item_vendido"] . " - " . $otd["sector"] . " (" . $otd["seccion"] . ")";
    ?>
    </h4>
</legend>

<div class="row" >

    <?php if ($readonly == 1) { ?>
        <div class="row"  style="margin-top: 20px;">
                <div class="div_tabla" scrollx="0" scrolly="0">

            </div>     
        </div>         
    <?php } ?>      
    <fieldset>

        <div class="row" style="display: none;">
            <div id="cont_archivos" class="col-md-12">          

            </div>
        </div>              
        <div class="row" style="margin-top: 10px; <?php if ($readonly == 1) { echo 'display: none; '; } ?>">
            <div class="col-md-12">
                <form action="subir_otd.php" class="dropzone" id="formdropZone">
                    <input type="text" class="form-control hidden" id="otp" name="otp" value="<?php echo $opc; ?>" />
                    
                </form>
            </div>
        </div>     

        <?php if ($readonly == 0) { ?>
            <div class="row" >
                <div class="div_tabla" scrollx="0" scrolly="0">

                </div>     
            </div>         
        <?php } ?>            
    </fieldset>
</div>       
    
<script src="inspinia/js/plugins/dropzone/dropzone.js"></script>

<script>
    Dropzone.autoDiscover = false;
    var myDropzone = new Dropzone("#formdropZone", { url: "subir_otd.php"});        
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
            funcion: "buscarArchivosOtd",         
            select_n1: $('#select_n1').val(),
            select_n2: $('#select_n2').val(),
            select_n3: $('#select_n3').val(),
            select_n4: $('#select_n4').val(),
            opc: $(".container").attr("opc")
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
            funcion: "buscarArchivosTablaOtd",         
            select_n1: $('#select_n1').val(),
            select_n2: $('#select_n2').val(),
            select_n3: $('#select_n3').val(),
            select_n4: $('#select_n4').val(),
            opc: $(".container").attr("opc")
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
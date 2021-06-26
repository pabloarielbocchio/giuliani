
<link href="inspinia/css/plugins/dropzone/dropzone.css" rel="stylesheet">	

<div class="row">
    <div class="col-md-6">   
        <div class="form-group">
            <label for="nombre0" class="control-label">Descripcion:</label>
            <input type="text" class="form-control" id="descripcion" name="descripcion" value="<?php echo $pp; ?>" > 
            <button type="button" style="margin-top: 10px;" class="btn btn-danger boton_marron_carni boton_guardar" >Guardar</button> 
        </div>
    </div>
    <div class="col-md-12">
        <fieldset>
            <div class="row">
                <div id="cont_archivos" class="col-md-12">          

                </div>
            </div>              
            <div class="row" style="margin-top: 10px;">
                <div class="col-md-12">
                    <form action="subir_p.php" class="dropzone" id="formdropZone">
                        <input type="text" class="form-control hidden" id="opc" name="opc" value="<?php echo $opc; ?>" />
                        <input type="text" class="form-control hidden" id="prodperso" name="prodperso" value="<?php echo $prod["prod_personalizado_id"]; ?>" />
                        <input type="text" class="form-control hidden" id="unidad" name="unidad" value="<?php echo $prod["unidad_id"]; ?>" />
                    </form>
                </div>
            </div>              
        </fieldset>
    </div>     
</div>     
<script src="inspinia/js/plugins/dropzone/dropzone.js"></script>

<script>
    Dropzone.autoDiscover = false;
    var myDropzone = new Dropzone("#formdropZone", { url: "subir_p.php"});        
    myDropzone.on("addedfile", function(file) {
        console.log(file);
    });
    myDropzone.on("success", function(file, responseText) {
        buscarArchivosP();
        console.log(responseText);
    });
    myDropzone.on("complete", function(file) {
        console.log(file);
    });
    myDropzone.on("error", function(file, errorMessage) {
        console.log(file);
    });
    
    function buscarArchivosP() {         
        var parametros = {
            funcion: "buscarArchivosP",       
            opc: $(".container").attr("opc")
        }
        console.log(parametros);        
        $.ajax({
            type: "POST",
            url: 'controller/productos.controller.php',
            data: parametros,
            success: function (datos) {
                $("#cont_archivos").html(datos);
            },
            error: function () {
                alert("Error");
            },
            complete: function () {
                requestSent = false;
            }
        });
    }
    
    buscarArchivosP();
    
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
                buscarArchivosP();
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

    $(".boton_guardar").click(function () {
        var parametros = {
            funcion: "updateProducto",
            codigo: $("#prodperso").val(),
            descripcion: $("#descripcion").val(),
            unidad: $("#unidad").val()
        }
        $.ajax({
            type: "POST",
            url: 'controller/productos_p.controller.php',
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
            }
        });
        event.preventDefault();
    });
</script>
<?php
    if (isset($_GET["codigo"])) {
        $codigo = $_GET["codigo"];

     //   $codigoDetalle = $_GET["codigoDetalle"];
    }
    $codigo1=intval($_SESSION['ot']);

?>
<link href="inspinia/css/plugins/dropzone/dropzone.css" rel="stylesheet">	
<legend>
    <?php
        $_SESSION["valorSeleccion"]=$otp["standar"]; 
        if ($otp["standar"] == 1) {
           
            $_SESSION["valorCodigoPP"]=$otp["codigo"];
            
            echo $otp["codigo"] ? "#" . $otp["codigo"] . " - " : "";
            echo $otp["prod_standar"] ;
        } else {
             
            $_SESSION["valorCodigoPP"]=$otp["codigo"];
            echo $otp["codigo"] ? "#" . $otp["codigo"] . " - " : "";
            echo $otp["prod_personalizado"] ;
        }
        $_SESSION["zip_otp"] = $otp["codigo"];
        $_SESSION["zip_otd"] = $otp["ot_detalle_id"];
        
    ?>
</legend>
  
<!-- Aqui agregue un boton de portada --> 
<?php if (in_array($_SESSION["rol"], [1,8])) { ?>
    <?php if ($ot["estado_prod"] != 1){ ?>
        <div style="margin-left: 80%;">
            <button style="background-color: orangered ; color: white;font-weight: bold; width: 100px; border: transparent; border-radius: 5px; vertical-align: middle;" type="submit" name="btnPortada" id="btnPortada">PORTADA</button>

        <button style="background-color: orangered ; color: white;font-weight: bold; width: 100px; border: transparent; border-radius: 5px; vertical-align: middle;" type="submit" name="btnDescargar" id="btnDescargar">DESCARGAR</button> 
        </div>
    <?php } ?>
<?php } ?>


  
<div class="row" >  
    <?php if ($readonly == 1) { ?>
        <div class="row" >
                <div class="div_tabla" scrollx="0" scrolly="0">

            </div>     
        </div> 
    <?php } ?>
    <fieldset>

        <div class="row" style="display: none;">
            <div id="cont_archivos" class="col-md-12">          

            </div>
        </div>         
        <div class="row" style="margin-top: 10px; <?php if ($readonly == 1 or $otp["standar"] == 1 or $ot["estado_ing"] == 1) { echo 'display: none; '; } ?>">
            <div class="col-md-12">
                <form action="subir_otp.php" class="dropzone" id="formdropZone">
                    <input type="text" class="form-control hidden" id="otp" name="otp" value="<?php echo $opc; ?>" />
                    <input type="text" class="form-control hidden" id="standar" name="standar" value="<?php echo $otp["standar"]; ?>" />
                    <input type="text" class="form-control hidden" id="prod_std" name="prod_std" value="<?php echo $otp["prod_estandar_id"]; ?>" />
                    <input type="text" class="form-control hidden" id="prod_perso" name="prod_perso" value="<?php echo $otp["prod_personalizado_id"]; ?>" />
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
    var myDropzone = new Dropzone("#formdropZone", { url: "subir_otp.php"});        
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
            funcion: "buscarArchivosOtp",         
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
            funcion: "buscarArchivosTablaOtp",         
            select_n1: $('#select_n1').val(),
            select_n2: $('#select_n2').val(),
            select_n3: $('#select_n3').val(),
            select_n4: $('#select_n4').val(),
            opc: $(".container").attr("opc"), 
            valorCodigoPP: <?php echo $_SESSION["valorCodigoPP"]; ?>
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
<?php
session_start();

include_once $_SERVER['DOCUMENT_ROOT'] . "/Giuliani/controller/index.controller.php";
$controlador = IndexController::singleton_index();

$_SESSION['menu'] = "inicio.php";

$_SESSION['breadcrumb'] = "Inicio";

$titlepage = "Giuliani - Inicio";

include 'inc/html/encabezado.php';

?>

<link href="inspinia/css/plugins/dropzone/dropzone.css" rel="stylesheet">	

<div class="row">
    <div class="col-md-12">
        <fieldset>
            <div class="row">
                <div id="cont_archivos" class="col-md-12">          

                </div>
            </div>              
            <div class="row">
                <div class="col-md-12">
                    <form action="subir.php" class="dropzone" id="formdropZone">
                        <input class="hidden" type="text" value="1" id="bomb_articulo_id" name="bomb_articulo_id" />
                    </form>
                </div>
            </div>              
        </fieldset>
    </div>     
</div>     

<?php
include 'inc/html/footer.php';
?>
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
        /*$(".table-loader").removeClass("hidden");        
        var parametros = {};
        parametros['articulo'] = $("#bomb_articulo_id").val();
        var datos = jQuery.extend({}, parametros);
        console.log(parametros);
        $.ajax({
            async: true,
            dataType: "html",
            data: datos,
            type: "post",
            url: "/bombeo/bomb-archivos/view",
            success: function (data, textStatus) {
                $("#cont_archivos").html(data);
                $(".table-loader").addClass('hidden');
            }
        });*/
    }
    
    function deleteFoto(archivo_id = 0){
        /*$(".table-loader").removeClass("hidden");  
        var parametros = {};
        parametros['articulo'] = archivo_id;
        var datos = jQuery.extend({}, parametros);
        console.log(parametros);
        $.ajax({
            async: true,
            dataType: "html",
            data: datos,
            type: "post",
            url: "/bombeo/bomb-archivos/delete",
            success: function (data, textStatus) {
                buscarArchivos();
            }
        });*/
    }

    function descargarArchivo(archivo, nombre){
        /*$(".table-loader").removeClass("hidden");  
        if (archivo){
            var link=document.createElement('a');
            document.body.appendChild(link);
            link.download=nombre;
            link.href=archivo;
            link.click();
        }
        $(".table-loader").addClass('hidden');*/
    }
</script>
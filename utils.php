<?php
session_start();

if (!($_SESSION["permisos"][basename(__FILE__, '.php') . ".php"]["access"])) {
    header("Location: cerrar.php");
}
include_once $_SERVER['DOCUMENT_ROOT'] . "/Giuliani/controller/index.controller.php";
$controlador = IndexController::singleton_index();
$texto_inicio = $controlador->getTextoInicio();

$_SESSION['menu'] = "utils.php";

$_SESSION['breadcrumb'] = "Frase Inicial";

$titlepage = "Giuliani - Frase Inicial";

include 'inc/html/encabezado.php';

?>


<div class="container" style="text-align: center;">
    <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12 right">
        <textarea name="textarea" id="textarea" rows="20" cols="100"><?php echo $texto_inicio; ?></textarea>
        
        <input type="button" id="save" name="save" class="btn-danger btn-sm boton_marron_carni" style="border-radius: 10px; margin: 10px;" value="Actualizar"/>
    </div>
</div>

<?php
include 'inc/html/footer.php';
?>

<script>
    $(document).ready(function(){
        $(".navbar-minimalize").click();
    });

    $("#save").click(function () {
        var parametros = {
            funcion: "updateFrase",
            textarea: $("#textarea").val()
        }
        $.ajax({
            type: "POST",
            url: 'controller/index.controller.php',
            data: parametros,
            success: function (datos) {
                if (parseInt(datos) == 0) {
                    window.location.href = "inicio.php";
                } else {
                    alert("Error"+datos);
                }
            },
            error: function () {
                alert("Error");
            }
        });
    });
</script>
<style>
.select2-close-mask{
    z-index: 2099;
}
.select2-dropdown{
    z-index: 3051;
}    
</style>
<div class="row">
    <div class="col-lg-12" style="margin-top: 10px; margin-left: 15px;">
        <div class="ibox">
            <div class="ibox-title">
                <h5>Destinos</h5>
            </div>
            <div class="ibox-content">
                <p>
                    Elija los destinos del usuario
                </p>
                <form id="form" action="#" class="wizard-big">
                    <select class="form-control dual_select" multiple>
                        <?php foreach ($destinos as $p) { ?>
                            <option <?php echo $p["selected"]; ?> value="<?php echo $p["codigo"]; ?>"><?php echo $p["descripcion"] ; ?></option>
                        <?php } ?>
                    </select>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12 right">
        <input type="button" id="afectar" name="afectar" class="btn-danger btn-sm boton_marron_carni" style="border-radius: 10px; margin-left: 10px;" value="Confirmar"/>
    </div>
</div>

<script>
    var requestSent = false;

    $("tbody > tr").click(function () {
        $("tbody > tr").css("background-color", "");
        $(this).css("background-color", "#FFFFB8");
    });
    
    $(document).ready(function() {
        $('.dual_select').bootstrapDualListbox({
            selectorMinimalHeight: 160
        }); 
        $('#select_usuario').attr("disabled", "disabled");
    });
    
    
    $("#afectar").click(function () {
        $('#select_usuario').removeAttr("disabled");
        var selected = $(".dual_select").val();
        var parametros = {
            funcion: "afectar",
            selected: selected,            
            cod_usuario: $('#select_usuario').val()
        }
        $.ajax({
            type: "POST",
            url: 'controller/usuarios.controller.php',
            data: parametros,
            success: function (datos) {
                console.log(datos);
                if (parseInt(datos) == 0) {
                    window.location.href = "usuarios.php";
                } else {
                    alert("Error" + datos);
                }
            },
            error: function () {
                alert("Error");
            },
            complete: function () {
                requestSent = false;
                $(window).scrollTop(scroll);
            }
        });
    });
</script>
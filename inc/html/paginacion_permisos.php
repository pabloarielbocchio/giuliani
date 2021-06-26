<?php
session_start();
?>

<div class="row" style="margin-top: -10px; font-size: 11px;" >
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="float: left;text-align: left;margin-top: 0px;">
        <label>Elija un <br />Usuario:</label>
        <select id="select_usuario" class="form-control asistencia" style="width: 65%;display: inline-block;vertical-align: middle; font-size: 11px; height: auto; margin-top: -15px;">
            <?php 
                foreach ($usuarios as $usuario){
                    if ($usuario["id"] == $_SESSION["permiso_selected"]){
                        echo '<option value="' . $usuario["id"] . '" selected>' . $usuario["mail"] . '</option>';
                    } else {
                        echo '<option value="' . $usuario["id"] . '">' . $usuario["mail"] . '</option>';
                    }
                }
            ?>
        </select>
    </div>
</div>
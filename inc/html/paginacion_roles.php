<?php
session_start();
?>

<div class="row" style="margin-top: -10px; font-size: 11px;" >
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="float: left;text-align: left;margin-top: 0px;">
        <label>Elija un <br />Rol:</label>
        <select id="select_rol" class="form-control asistencia" style="width: 65%;display: inline-block;vertical-align: middle; font-size: 11px; height: auto; margin-top: -15px;">
            <?php 
                foreach ($roles as $usuario){
                    if ($usuario["codigo"] == $_SESSION["rol_selected"]){
                        echo '<option value="' . $usuario["codigo"] . '" selected>' . $usuario["descripcion"] . '</option>';
                    } else {
                        echo '<option value="' . $usuario["codigo"] . '">' . $usuario["descripcion"] . '</option>';
                    }
                }
            ?>
        </select>
    </div>
</div>
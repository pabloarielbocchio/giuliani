<?php
session_start();
?>

<div class="row" style="margin-top: -10px; font-size: 11px;" >
    <div class="col-lg-2 col-md-3 col-sm-3  hidden-xs" style="float: left;text-align: left;margin-top: 5px; display: none;">
        <label for="busqueda">Buscar:</label>
        <input type="text" id="busqueda" name="busqueda" value="<?php echo $_SESSION["busqueda"]; ?>" style="width: 65px;" />
        <a href="#" style="margin-left: 5px;" id="busqueda-erase" name="busqueda-erase"><span class="glyphicon glyphicon-erase" aria-hidden="true"></span></a>
        <a href="#" style="margin-left: 5px;" id="busqueda-icono" name="busqueda-icono"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></a>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="float: left;text-align: left;margin-top: 0px;">
        <label style="text-align: right; margin-right: 10px;">Elija una<br />OT:</label>
        <select id="select_ot" class="form-control asistencia" style="width: 65%;display: inline-block;vertical-align: middle; font-size: 11px; height: auto; margin-top: -15px;">
            <?php 
                foreach ($ots as $ot){
                    if ($ot["codigo"] == $_SESSION["ot"]){
                        if ($ot["finalizada"] == 1) {
                            echo '<option value="' . $ot["codigo"] . '" selected>' . $ot["descripcion"] . ' (Finalizada)</option>';
                        } elseif ($ot["finalizada"] == 0 or $ot["finalizada"] == null) {
                            echo '<option value="' . $ot["codigo"] . '" selected>' . $ot["descripcion"] . ' (En cola)</option>';
                        }elseif ($ot["finalizada"] == -1) {
                            echo '<option value="' . $ot["codigo"] . '" selected>' . $ot["descripcion"] . ' (Cancelada)</option>';
                        }elseif ($ot["finalizada"] == 2) {
                            echo '<option value="' . $ot["codigo"] . '" selected>' . $ot["descripcion"] . ' (En curso)</option>';
                        }
                    } else {
                        if ($ot["finalizada"] == 1) {
                            echo '<option value="' . $ot["codigo"] . '" >' . $ot["descripcion"] . ' (Finalizada)</option>';
                        } elseif ($ot["finalizada"] == 0 or $ot["finalizada"] == null) {
                            echo '<option value="' . $ot["codigo"] . '" >' . $ot["descripcion"] . ' (En cola)</option>';
                        }elseif ($ot["finalizada"] == -1) {
                            echo '<option value="' . $ot["codigo"] . '" >' . $ot["descripcion"] . ' (Cancelada)</option>';
                        }elseif ($ot["finalizada"] == 2) {
                            echo '<option value="' . $ot["codigo"] . '" >' . $ot["descripcion"] . ' (En curso)</option>';
                        }
                    }
                }
            ?>
        </select>
    </div>
</div>
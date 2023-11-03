<?php
session_start();
?>

<div id="div_paginacion" class="row" style="margin-top: -10px; font-size: 11px;" orderby="<?php $_SESSION["orderby"]; ?>" sentido="<?php $_SESSION["sentido"]; ?>">
    <div class="col-lg-2 col-md-3 col-sm-3  hidden-xs" style="float: left;text-align: left;margin-top: 5px; ">
        <label for="busqueda">Buscar:</label>
        <input type="text" id="busqueda" name="busqueda" value="<?php echo $_SESSION["busqueda"]; ?>" style="width: 65px;" />
        <a href="#" style="margin-left: 5px;" id="busqueda-erase" name="busqueda-erase"><span class="glyphicon glyphicon-erase" aria-hidden="true"></span></a>
        <a href="#" style="margin-left: 5px;" id="busqueda-icono" name="busqueda-icono"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></a>
    </div>

    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="float: left;text-align: left;margin-top: 0px; ">
        <label style="text-align: right; margin-right: 10px;">Seleccione <br />Evento:</label>
        <select id="select_evento" class="form-control asistencia" style="width: 65%;display: inline-block;vertical-align: middle; font-size: 11px; height: auto; margin-top: -15px;">
            <?php 
                if ($_SESSION["evento_selected"] == 0){
                    echo '<option value="0" selected>Todos</option>';
                } else {
                    echo '<option value="0">Todos</option>';
                }
                foreach ($eventos as $evento){
                    if ($evento["codigo"] == $_SESSION["evento_selected"]){
                        echo '<option value="' . $evento["codigo"] . '" selected>' . $evento["descripcion"] . '</option>';
                    } else {
                        echo '<option value="' . $evento["codigo"] . '">' . $evento["descripcion"] . '</option>';
                    }
                }
            ?>
        </select>
    </div>

    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="float: left;text-align: left;margin-top: 0px; ">
        <label style="text-align: right; margin-right: 10px;">Seleccione <br />Usuario:</label>
        <select id="select_usuario" class="form-control asistencia" style="width: 65%;display: inline-block;vertical-align: middle; font-size: 11px; height: auto; margin-top: -15px;">
            <?php 
                if ($_SESSION["usuario_selected"] == 0){
                    echo '<option value="0" selected>Todos</option>';
                } else {
                    echo '<option value="0">Todos</option>';
                }
                foreach ($usuarios as $usuario){
                    if ($usuario["usuario"] == $_SESSION["usuario_selected"]){
                        echo '<option value="' . $usuario["usuario"] . '" selected>' . $usuario["usuario"] . '</option>';
                    } else {
                        echo '<option value="' . $usuario["usuario"] . '">' . $usuario["usuario"] . '</option>';
                    }
                }
            ?>
        </select>
    </div>

    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="float: left;text-align: left;margin-top: 0px; ">
        <label style="text-align: right; margin-right: 10px;">Seleccione <br />OT's:</label>
        <select id="select_ot" class="form-control asistencia" style="width: 65%;display: inline-block;vertical-align: middle; font-size: 11px; height: auto; margin-top: -15px;">
            <?php 
                if ($_SESSION["ot_selected"] == 0){
                    echo '<option value="0" selected>Todos</option>';
                } else {
                    echo '<option value="0">Todos</option>';
                }
                foreach ($ots as $ot){
                    if ($ot["codigo"] == $_SESSION["ot_selected"]){
                        echo '<option value="' . $ot["codigo"] . '" selected>' . $ot["descripcion"] . '</option>';
                    } else {
                        echo '<option value="' . $ot["codigo"] . '">' . $ot["descripcion"] . '</option>';
                    }
                }
            ?>
        </select>
    </div>
</div>
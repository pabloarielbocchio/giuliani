<?php
session_start();
?>

<div id="div_paginacion" class="row" style="border-bottom: 1px  #DFDFDFDF solid; margin-top: -10px; margin-bottom: 10px; padding-bottom: px; font-size: 11px;" orderby="<?php $_SESSION["orderby"]; ?>" sentido="<?php $_SESSION["sentido"]; ?>">
    <div class="col-lg-2 col-md-3 col-sm-3  hidden-xs" style="float: left;text-align: left;margin-top: 5px; display: none;">
        <label for="busqueda">Buscar:</label>
        <input type="text" id="busqueda" name="busqueda" value="<?php echo $_SESSION["busqueda"]; ?>" style="width: 65px;" />
        <a href="#" style="margin-left: 5px;" id="busqueda-erase" name="busqueda-erase"><span class="glyphicon glyphicon-erase" aria-hidden="true"></span></a>
        <a href="#" style="margin-left: 5px;" id="busqueda-icono" name="busqueda-icono"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></a>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="float: left;text-align: left;margin-top: 0px;">
        <label>Elija un <br />Usuario:</label>
        <select id="select_usuario" class="form-control asistencia" style="width: 65%;display: inline-block;vertical-align: middle; font-size: 11px; height: auto; margin-top: -15px;">
            <?php 
                foreach ($usuarios as $usuario){
                    if ($usuario["codigo"] == $cod_usuario){
                        echo '<option value="' . $usuario["codigo"] . '" selected>' . $usuario["usuario"] . ' (' . $usuario["surname"] . ', ' . $usuario["name"] .')</option>';
                    } else {
                        echo '<option value="' . $usuario["codigo"] . '">' . $usuario["usuario"] . ' (' . $usuario["surname"] . ', ' . $usuario["name"] .')</option>';
                    }
                }
            ?>
        </select>
    </div>
</div>
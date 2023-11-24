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
        <label style="text-align: right; margin-right: 10px;">Seleccione <br />Tipo:</label>
        <select id="select_tipo" class="form-control asistencia" style="width: 65%;display: inline-block;vertical-align: middle; font-size: 11px; height: auto; margin-top: -15px;">
            <?php 
                if ($_SESSION["tipo_selected"] == 0){
                    echo '<option value="0" selected>Todos</option>';
                } else {
                    echo '<option value="0">Todos</option>';
                }
                foreach ($tipos as $tipo){
                    if ($tipo["codigo"] == $_SESSION["tipo_selected"]){
                        echo '<option value="' . $tipo["codigo"] . '" selected>' . $tipo["nombre"] . '</option>';
                    } else {
                        echo '<option value="' . $tipo["codigo"] . '">' . $tipo["nombre"] . '</option>';
                    }
                }
            ?>
        </select>
    </div>
</div>
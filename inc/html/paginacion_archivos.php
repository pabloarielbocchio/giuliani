<?php
session_start();
?>

<div id="div_paginacion" class="row" style="border-bottom: 1px  #DFDFDFDF solid; margin-top: -10px; margin-bottom: 10px; padding-bottom: px; font-size: 11px;" orderby="<?php $_SESSION["orderby"]; ?>" sentido="<?php $_SESSION["sentido"]; ?>">
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="float: left;text-align: left;margin-top: 5px; display: none;">
        <label for="busqueda">Buscar:</label>
        <input type="text" id="busqueda" name="busqueda" value="<?php echo $_SESSION["busqueda"]; ?>" style="width: 65px;" />
        <a href="#" style="margin-left: 5px;" id="busqueda-erase" name="busqueda-erase"><span class="glyphicon glyphicon-erase" aria-hidden="true"></span></a>
        <a href="#" style="margin-left: 5px;" id="busqueda-icono" name="busqueda-icono"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></a>
    </div>

    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="float: left;text-align: left;margin-top: 0px;">
        <label style="text-align: right; margin-right: 10px;">Opción <br />Nivel 1:</label>
        <select id="select_n1" class="form-control asistencia" style="width: 65%;display: inline-block;vertical-align: middle; font-size: 11px; height: auto; margin-top: -15px;">
            <?php 
                echo '<option class="" value="0"></option>';
                foreach ($prod_a as $aux){
                    if ($aux["codigo"] == $cod_nivel_1){
                        echo '<option class="opt_nivel1" value="' . $aux["codigo"] . '" selected>' . $aux["codigo"] . " - " .  $aux["descripcion"] . '</option>';
                    } else {
                        echo '<option class="opt_nivel1" value="' . $aux["codigo"] . '">' . $aux["codigo"] . " - " .  $aux["descripcion"] . '</option>';
                    }
                }
            ?>
        </select>
    </div>

    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="float: left;text-align: left;margin-top: 0px;">
        <label style="text-align: right; margin-right: 10px;">Opción <br />Nivel 2:</label>
        <select id="select_n2" class="form-control asistencia" style="width: 65%;display: inline-block;vertical-align: middle; font-size: 11px; height: auto; margin-top: -15px;">
            <?php 
                echo '<option class="" value="0"></option>';
                foreach ($prod_b as $aux){
                    if ($aux["codigo"] == $cod_nivel_2){
                        echo '<option class="opt_nivel2" nivel_anterior="' . $aux["cod_prod_na"] . '" value="' . $aux["codigo"] . '" selected>' . $aux["codigo"] . " - " .  $aux["descripcion"] . '</option>';
                    } else {
                        echo '<option class="opt_nivel2" nivel_anterior="' . $aux["cod_prod_na"] . '" value="' . $aux["codigo"] . '">' . $aux["codigo"] . " - " .  $aux["descripcion"] . '</option>';
                    }
                }
            ?>
        </select>
    </div>

    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="float: left;text-align: left;margin-top: 0px;">
        <label style="text-align: right; margin-right: 10px;">Opción <br />Nivel 3:</label>
        <select id="select_n3" class="form-control asistencia" style="width: 65%;display: inline-block;vertical-align: middle; font-size: 11px; height: auto; margin-top: -15px;">
            <?php 
                echo '<option class="" value="0"></option>';
                foreach ($prod_c as $aux){
                    if ($aux["codigo"] == $cod_nivel_3){
                        echo '<option class="opt_nivel3" nivel_anterior="' . $aux["cod_prod_nb"] . '" value="' . $aux["codigo"] . '" selected>' . $aux["codigo"] . " - " .  $aux["descripcion"] . '</option>';
                    } else {
                        echo '<option class="opt_nivel3" nivel_anterior="' . $aux["cod_prod_nb"] . '" value="' . $aux["codigo"] . '">' . $aux["codigo"] . " - " .  $aux["descripcion"] . '</option>';
                    }
                }
            ?>
        </select>
    </div>

    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="float: left;text-align: left;margin-top: 0px; ">
        <label style="text-align: right; margin-right: 10px;">Opción <br />Nivel 4:</label>
        <select id="select_n4" class="form-control asistencia" style="width: 65%;display: inline-block;vertical-align: middle; font-size: 11px; height: auto; margin-top: -15px;">
            <?php 
                echo '<option class="" value="0"></option>';
                foreach ($prod_d as $aux){
                    if ($aux["codigo"] == $cod_nivel_4){
                        echo '<option class="opt_nivel4" nivel_anterior="' . $aux["cod_prod_nc"] . '" value="' . $aux["codigo"] . '" selected>' . $aux["codigo"] . " - " .  $aux["descripcion"] . '</option>';
                    } else {
                        echo '<option class="opt_nivel4" nivel_anterior="' . $aux["cod_prod_nc"] . '" value="' . $aux["codigo"] . '">' . $aux["codigo"] . " - " .  $aux["descripcion"] . '</option>';
                    }
                }
            ?>
        </select>
    </div>
</div>
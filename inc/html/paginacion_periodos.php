<?php
session_start();
?>

<div id="div_paginacion" class="row" style="margin-top: -10px; font-size: 11px;" orderby="<?php $_SESSION["orderby"]; ?>" sentido="<?php $_SESSION["sentido"]; ?>">
    <div class="col-lg-4 col-md-4 col-sm-5 col-xs-12" style="float: left;text-align: left;margin-top: 0px;">
        <label style="margin-left: 5px;">Seleccione <br>una opción:</label>
        <select id="periodos" class="form-control" style="width: 75%;display: inline-block;vertical-align: middle; font-size: 11px; height: auto; margin-top: -18px;">
            <option value="-1" <?php if ($_SESSION['cod_periodo'] == -1) echo "selected"; ?> disabled>Elija una opción</option>
            <?php 
                foreach ($periodos as $pos => $val) {
                    $cl["codigo"] = $pos;
                    $cl["descripcion"] = $val["mes"] . "/" . $val["anio"];
            ?>
                <option 
                    mes="<?php echo $val['mes']; ?>" 
                    anio="<?php echo $val['anio']; ?>" 
                    value="<?php echo $cl['codigo']; ?>" 
                    <?php if ($_SESSION['cod_periodo'] == $cl['codigo']) echo "selected"; ?>
                ><?php echo $cl['descripcion']; ?></option>
            <?php  } ?>
        </select>
    </div>
</div>
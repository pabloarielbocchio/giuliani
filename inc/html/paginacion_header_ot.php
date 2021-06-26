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
        <label style="text-align: right; margin-right: 10px;">Seleccione <br />Estado:</label>
        <select id="select_estado" class="form-control asistencia" style="width: 65%;display: inline-block;vertical-align: middle; font-size: 11px; height: auto; margin-top: -15px;">
            <option class="" value="-2" <?php if ($_SESSION["estado"] == -1) { echo "selected"; } ?>>Todos</option>
            <option class="" value="0"  <?php if ($_SESSION["estado"] ==  0) { echo "selected"; } ?>>En Cola</option>
            <option class="" value="2"  <?php if ($_SESSION["estado"] ==  2) { echo "selected"; } ?>>En Curso</option>
            <option class="" value="1"  <?php if ($_SESSION["estado"] ==  1) { echo "selected"; } ?>>Finalizadas</option>
            <option class="" value="-1"  <?php if ($_SESSION["estado"] ==  1) { echo "selected"; } ?>>Canceladas</option>
        </select>
    </div>
</div>
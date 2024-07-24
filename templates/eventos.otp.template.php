
<link href="inspinia/css/plugins/dropzone/dropzone.css" rel="stylesheet">	
<legend>
    <?php 
        if ($otp["standar"] == 1) {
            echo $otp["prod_standar"] . " (" . $otp["unidad"] . ")";
        } else {
            echo $otp["prod_personalizado"] . " (" . $otp["unidad"] . ")";
        }
    ?>
</legend>
<button style="position:relative;left:80% ;bottom:210px; background-color: orangered ; color: white;font-weight: bold; width: 100px; border: transparent; border-radius: 5px; vertical-align: middle;" type="submit" name="btnPortada" id="btnPortada">PORTADA</button>
<table id="tabla" namefile="Eventos" totales="<?php echo $_SESSION["totales"]; ?>" registros="<?php echo $_SESSION['cant_reg']; ?>" pagina="<?php $_SESSION['pagina']; ?>" class="table table-striped table-hover" mes="<?php echo $mes; ?>" anio="<?php echo $anio; ?>" dia="<?php echo $dia; ?>" opcion="<?php echo $opcion; ?>"> 
    <thead>
        <tr class="row " style="background-color: transparent;">
            <th class="text-left ordena" orderby="descripcion" sentido="asc">Fecha</th>
            <th class="text-left ordena" orderby="descripcion" sentido="asc">Usuario</th>
            <th class="text-left ordena" orderby="descripcion" sentido="asc">Evento</th>
            <th class="text-left ordena" orderby="descripcion" sentido="asc">Descripción</th>
        </tr>
    </thead>
    <tbody id="body">
        <?php foreach ($registros as $usu) { ?>
            <tr class="row" codigo="<?php echo $usu["codigo"]; ?>">
                <td class="text-left" style="vertical-align: middle;"><?php echo $usu["fecha_m"]; ?></td>
                <td class="text-left" style="vertical-align: middle;"><?php echo $usu["usuario_m"]; ?></td>
                <td class="text-left" style="vertical-align: middle;"><?php echo $usu["evento"]; ?></td>
                <td class="text-left" style="vertical-align: middle;"><?php echo $usu["observaciones"]; ?></td>
            </tr>
<?php } ?>
    </tbody>
</table>
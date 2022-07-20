<table id="tabla" namefile="Planos" totales="<?php echo $_SESSION["totales"]; ?>" registros="<?php echo $_SESSION['cant_reg']; ?>" pagina="<?php $_SESSION['pagina']; ?>" class="table table-striped table-hover" mes="<?php echo $mes; ?>" anio="<?php echo $anio; ?>" dia="<?php echo $dia; ?>" opcion="<?php echo $opcion; ?>"> 
    <thead>
        <tr class="row " style="background-color: transparent;">
            <th class="text-left ordena" orderby="descripcion" sentido="asc">Descripción</th>
            <th class="text-left ordena" orderby="ruta" sentido="asc">Ruta</th>
            <th class="text-left ordena" orderby="fecha_hora" sentido="asc">Fecha</th>
            <th class="text-left ordena" orderby="activo" sentido="asc">Activo</th>
            <th class="text-center noExl">Acciones</th>
        </tr>
    </thead>
    <tbody id="body">
        <?php foreach ($registros as $usu) { ?>
            <tr class="row" 
                codigo="<?php echo $usu["codigo"]; ?>" 
                ruta="<?php echo $usu["ruta"]; ?>"
                archivo="<?php echo $usu["archivo"]; ?>"
                activo="<?php echo $usu["activo"]; ?>"
            >
                <td class="text-left" style="vertical-align: middle;"><?php echo $usu["descripcion"]; ?></td>
                <td class="text-left" style="vertical-align: middle;"><?php echo $usu["ruta"]; ?></td>
                <td class="text-left" style="vertical-align: middle;"><?php echo $usu["fecha_hora"]; ?></td>
                <td class="text-left" style="vertical-align: middle;"><?php echo $usu["activo"] == 1 ? "SI" : "NO"; ?></td>
                <td class="text-left" style="vertical-align: middle;">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-primary dropdown-toggle nuevo" id="menu" type="button" data-toggle="dropdown"  style="font-size: 10px;height: 15px; width: 100%;">
                            <div class="opciones" style="margin-top: -6px">Opciones <span class="caret"></span></div>
                        </button>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="menu">
                            <li role="presentation" class="descargar"><a role="menuitem" tabindex="-1" href="#">Descargar</a></li>
                        </ul>
                    </div>
                </td>        
            </tr>
<?php } ?>
    </tbody>
</table>

<div class="col-lg-2" style="float: left;text-align: left;margin-top: 8px; display: none;" id='leyenda_paginacion_aux'>
    <label>Mostrando <?php echo ($_SESSION["pagina"] - 1) * $_SESSION["cant_reg"] + 1; ?> a <?php
        if ($_SESSION["totales"] <= $_SESSION["pagina"] * $_SESSION["cant_reg"])
            echo $_SESSION["totales"];
        else
        if ($_SESSION["cant_reg"] == -1) {
            echo $_SESSION["totales"];
        } else {
            echo $_SESSION["pagina"] * $_SESSION["cant_reg"];
        }
        ?> de <?php echo $_SESSION["totales"]; ?></label>
</div>

<script>
    var requestSent = false;
    
    $(".descargar").click(function () {
        codigo = $(this).closest('tr').attr("codigo");
        ruta = $(this).closest('tr').attr("ruta");
        archivo = $(this).closest('tr').attr("archivo");
        var link=document.createElement('a');
        document.body.appendChild(link);
        link.download=archivo;
        link.href=ruta;
        link.click();
    });

    $(".ordena").click(function () {

        var orderby = $(this).attr("orderby");
        var sentido = $("#div_tabla").attr("sentido");

        if (sentido == "asc") {
            sentido = "desc";
        } else {
            sentido = "asc";
        }

        $("#div_tabla").attr("orderby", orderby);
        $("#div_tabla").attr("sentido", sentido);

        var registros = $("#cant_reg").val();
        var pagina = 1;
        var busqueda = $("#busqueda").val();
        getRegistros(orderby, sentido, registros, pagina, busqueda, null);
        //callGetRegistros(orderby, sentido, registros, pagina, busqueda, this);
        //return false;
    });
    
    $("tbody > tr").click(function () {
        $("tbody > tr").css("background-color", "");
        $(this).css("background-color", "#FFFFB8");
    });
</script>
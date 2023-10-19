
<style>

.divespecial {
  min-height: 25%;
  max-height: 75%;
  overflow: scroll;
  position: relative;
}

table {
  position: relative;
  border-collapse: collapse;
}

tr {
  min-height: 15em;
}

td {
  min-width: 08em;
  border: 1px solid #DEDEDE;
}

td,
th {
  padding: 0.1em;
}

thead th {
  position: -webkit-sticky; /* for Safari */
  position: sticky;
  background: #DEDEDE;
  top: 0;
}

thead th:first-child {
  left: 0;
  z-index: 3;
}

thead th{
  z-index: 1;
}

/*
._opciones {
  position: -webkit-sticky; 
  position: sticky;
  right: 0;
  left: 0;
  min-width: 10em;
  background: #DEDEDE;
  border-left: 1px solid #CCC;
}*/

.nombre {
  position: -webkit-sticky; /* for Safari */
  position: sticky;
  right: 0;
  left: 0;
  min-width: 45em;
  z-index: 2;
  background: #f1f1f1;
  font-family: 'montserrat';
  font-weight: normal;
  border-right: 1px solid #CCC;
}

</style>

<div class="divespecial m-t-lg">
    <?php if (count($archivos) > 0) { ?>

        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="float: left;text-align: left;margin-top: 0px;">
            <label style="text-align: right; margin-right: 10px;">Mostrar <br />Estado:</label>
            <select id="select_estado" class="form-control asistencia" style="width: 65%;display: inline-block;vertical-align: middle; font-size: 11px; height: auto; margin-top: -15px;">
                <?php 
                    echo '<option class="" value="100">TODOS</option>';
                    echo '<option class="" value="-1">Inactivo</option>';
                    echo '<option class="" value="0">En Proceso</option>';
                    echo '<option class="" value="1">Activo</option>';
                ?>
            </select>
        </div>

        <table id="tabla" class="table table-striped table-hover" > 
            <thead>
                <tr class="row " style="background-color: transparent;">
                    <th  class="text-left " orderby="seccion" sentido="asc">Archivo</th>
                    <th  class="text-center " orderby="seccion" sentido="asc" style="width: 5%;">Nivel</th> 
                    <th  class="text-left " orderby="seccion" sentido="asc" style="width: 5%;"></th> 
                    <th  class="text-center " orderby="seccion" sentido="asc" style="width: 15%;">Estado</th>           
                    <?php foreach($destinos as $destino) { ?>
                        <th class="text-center " orderby="item_vendido" sentido="asc"><?php echo $destino["descripcion"]; ?></th>
                    <?php } ?>     
                </tr>
            </thead>
            <tbody id="body">
                <?php 
                    $usados = [];
                    foreach ($archivos as $nivel => $archivo) { 
                ?>
                    <?php 
                        foreach ($archivo as $usu) { 
                            if (in_array($usu["codigo"], $usados)){
                                continue;
                            }
                            $usados[] = $usu["codigo"];
                    ?>
                        <tr class="row fila_tabla" 
                                codigo="<?php echo $usu["codigo"]; ?>" 
                                ruta="<?php echo $usu["ruta"]; ?>"
                                archivo="<?php echo $usu["archivo"]; ?>"
                                activo="<?php echo $usu["activo"]; ?>"
                            >
                            <th class="text-left nombre" style="vertical-align: middle;"><?php echo $usu["prefijo"] . $usu["descripcion"]; ?></th>
                            <td class="text-center" style="vertical-align: middle;"><?php echo $nivel; ?></td>
                            <td class="text-left" style="vertical-align: middle;">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-primary dropdown-toggle nuevo" id="menu" type="button" data-toggle="dropdown"  style="font-size: 10px;height: 15px; width: 100%;">
                                        <div class="opciones" style="margin-top: -6px">Opciones <span class="caret"></span></div>
                                    </button>
                                    <ul class="dropdown-menu" role="menu" aria-labelledby="menu">
                                        <li role="presentation" class="ver"><a role="menuitem" tabindex="-1" href="#">Ver</a></li>
                                        <li role="presentation" class="descargar"><a role="menuitem" tabindex="-1" href="#">Descargar</a></li>
                                    </ul>
                                </div>
                            </td>                      

                            <td class="text-center" style="vertical-align: middle;">
                                <?php 
                                    switch ($usu["activo"]){
                                        case -1:
                                            echo "Inactivo";
                                            break;
                                        case 0:
                                            echo "En Proceso";
                                            break;
                                        case 1:
                                            echo "Activo";
                                            break;
                                    }
                                ?>
                            </td>
                           
                            <?php foreach($destinos as $destino) { 
                                $permitido = 0;
                            ?>
                                <?php 
                                foreach($archivos_destinos as $ad) { 
                                    if ($ad["archivo_id"] != $usu["codigo"]){
                                        continue;
                                    }
                                    if ($ad["destino_id"] != $destino["codigo"]){
                                        continue;
                                    }
                                    $permitido = $ad["codigo"];
                                    break;
                                }
                                ?>
                                <td class="text-center" style="vertical-align: middle; width: 5%;">
                                    <?php
                                    if ($permitido > 0) {
                                        echo '<span class="glyphicon glyphicon-ok opcion ok" archivo="'.$usu["cod_archivo"].'" destino="'.$destino["codigo"].'" permitido="'.$permitido.'"  style="color: #0A0;  cursor: pointer;" aria-hidden="true"></span>';
                                    } else {
                                        echo '<span class="glyphicon glyphicon-remove opcion nook" archivo="'.$usu["cod_archivo"].'" destino="'.$destino["codigo"].'" permitido="0" style="color: #A00; cursor: pointer; " aria-hidden="true"></span>';
                                    }
                                    ?>
                                </td>
                            <?php } ?>  
                                
                        </tr>
                    <?php } ?>
                <?php } ?>
            </tbody>
        </table>
    <?php } ?>
</div>

<script>
    $(".ver").click(function () {
        codigo = $(this).closest('tr').attr("codigo");                        
        console.log(codigo);
        var win = window.open('pdf.php?archivo='+codigo, '_blank');
        if (win) {
            win.focus();
        } else {
            alert('Please allow popups for this website');
        }
    });

    $(".descargar").css("display","none");

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

    $("#select_estado").change(function () {
        var estado = $(this).val();
        if (estado == 100){
            $(".fila_tabla").css("display","table-row");
        } else {
            $(".fila_tabla").css("display","none");
            $(".fila_tabla[activo='"+estado+"']").each(function () {
                $(this).css("display","table-row");
            });
        }
    });
</script>


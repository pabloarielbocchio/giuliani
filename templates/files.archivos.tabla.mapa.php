
<table id="tabla" class="table table-striped table-hover"> 
    <thead>
        <tr class="row " style="background-color: transparent;">
            <th  class="text-left ">Archivo</th>           
            <th  class="text-center ">Estado</th>        
        </tr>
    </thead>
    <tbody id="body"> 
        <?php foreach ($devuelve as $usu) { ?>
            <tr class="row" 
                codigo="<?php echo $usu["codigo"]; ?>"
                ruta="<?php echo $usu["ruta"]; ?>"
                archivo="<?php echo $usu["descripcion"]; ?>"
            >
                <td class="text-left descargar" style="cursor: pointer; vertical-align: middle;"><?php echo $usu["descripcion"]; ?></td>
                <td class="text-center" style="vertical-align: middle; width: 15%;">
                    <?php 
                        switch ($usu["activo"]){
                            case -1:
                                echo '<span estado="-1" archivo="'.$usu["codigo"].'" class="estado_editable label label-danger m-t-lg">Inactivo</span>';
                                break;
                            case 0:
                                echo'<span estado="0" archivo="'.$usu["codigo"].'" class="estado_editable label label-warning m-t-lg" >En Proceso</span>';
                                break;
                            case 1:
                                echo '<span estado="1"  archivo="'.$usu["codigo"].'" class="estado_editable label label-info m-t-lg">Activo</span>';
                                break;
                        }
                    ?>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<script>

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

</script>
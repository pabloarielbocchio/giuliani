<?php

if (isset($_GET["codigo"])) {
    $codigo = $_GET["codigo"];

    //   $codigoDetalle = $_GET["codigoDetalle"];
}
$codigo1 = intval($_SESSION['ot']);



?>
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
        position: -webkit-sticky;
        /* for Safari */
        position: sticky;
        background: #DEDEDE;
        top: 0;
    }

    thead th:first-child {
        left: 0;
        z-index: 3;
    }

    thead th {
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
        position: -webkit-sticky;
        /* for Safari */
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

<div class="modal fade" id="myModalEliminar" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 id="name-header-modal" class="modal-title">Eliminar</h4>
            </div>
            <div class="modal-body text-center"  id="text-header-body">
                ¿Confirma que desea eliminar el registro? Tenga en cuenta que no podrá volver atrás.
            </div>
            <div class="modal-footer">
                <button type="button" id="btn-eliminar-fila" name="btn-eliminar-fila" class="btn btn-danger boton_marron_carni" data-dismiss="modal" >Eliminar</button>
                <button type="button" id="btn-cancelar" name="btn-cancelar" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 id="name-header-modal" class="modal-title">Inactivar</h4>
            </div>
            <div class="modal-body text-center" id="text-header-body">
                ¿Confirma que desea inhabilitar el archivo? Tenga en cuenta que no podrá volver atrás.
            </div>
            <div class="modal-footer">
                <button type="button" id="btn-eliminar-archivo" name="btn-eliminar-archivo" class="btn btn-danger boton_marron_carni" data-dismiss="modal">Inhabilitar</button>
                <button type="button" id="btn-cancelar" name="btn-cancelar" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<div id="valorCodigoPP" valorCodigoPP="<?php echo $valorCodigoPP; ?>"></div>

<?php if (count($archivos) > 0) { ?>
    <table id="tabla" class="table table-striped table-hover" style="display: block; overflow-x: auto; white-space: nowrap;">
        <thead>
            <tr class="row " style="background-color: transparent;">
              
                <th class="text-left " orderby="seccion" sentido="asc">Archivos</th>
                <th class="text-left " orderby="seccion" sentido="asc" style="width: 5%;"></th>
                <th class="text-left " orderby="seccion" sentido="asc">Dependencia</th>
                <th class="text-center " orderby="seccion" sentido="asc">Última Actualización</th>
                <th class="text-center " orderby="seccion" sentido="asc">Estado Actual</th>
                <?php foreach ($destinos as $destino) { ?>
                    <th class="text-center " orderby="item_vendido" sentido="asc"><?php echo $destino["descripcion"]; ?></th>
                <?php } ?>
            </tr>
        </thead>
     <!-- //if (!($_SESSION["rol"] == 1 or $_SESSION["rol"] == 5)){ -->
        <tbody id="body">
                 
            <?php
             
            foreach ($archivos as $usu) {
                if (!($_SESSION["rol_editar_files_otp"])) {
                    if ($usu["activo"] != 1) {
                        continue;
                    }
                }
            ?>
                <tr class="row" otp="<?php echo $usu["ot_produccion_id"]; ?>" otd="<?php echo $usu["ot_detalle_id"]; ?>" codigo="<?php echo $usu["codigo"]; ?>" ruta="<?php echo $usu["ruta"]; ?>" archivo="<?php echo $usu["archivo"]; ?>" archivo_id="<?php echo $usu["archivo_id"]; ?>" nuevo="<?php echo $usu["nuevo"]; ?>">
                    <th class="text-left nombre" style="vertical-align: middle;"><?php echo $usu["prefijo"] .$usu["archivo"]; ?></th>

                    <td class="text-left" style="vertical-align: middle;">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-primary dropdown-toggle nuevo" id="menu" type="button" data-toggle="dropdown" style="font-size: 10px;height: 15px; width: 100%;">
                                <div class="opciones" style="margin-top: -6px">Opciones <span class="caret"></span></div>
                            </button>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="menu">
                                <li role="presentation" class="ver"><a role="menuitem" tabindex="-1" href="#">Ver</a></li>
                                <li role="presentation" class="descargar"><a role="menuitem" tabindex="-1" href="#">Descargar</a></li>
                                <?php if ($usu["nuevo"] == 1) {  ?>
                                    <li role="presentation" class="eliminar opc_eliminar"><a role="menuitem" tabindex="-1" href="#">Eliminar</a></li>
                                <?php } ?>
                            </ul>
                        </div>
                    </td>

                    <td class="text-left" style="vertical-align: middle;" title="<?php echo $usu["dependencia"]; ?>">
                        <?php echo strlen($usu["dependencia"]) > 35 ? substr($usu["dependencia"], 0, 35) . "..." : $usu["dependencia"]; ?></td>
                    <td class="text-center" style="vertical-align: middle;"><?php echo date("d/m/Y", strtotime($usu["ultima_actualizacion"])); ?></td>
                    <td class="text-center" style="vertical-align: middle; width: 10%;">
                        <?php
                        switch ($usu["activo"]) {
                            case -1:
                                echo '<span style="cursor: pointer;" estado="-1" archivo="' . $usu["cod_archivo"] . '" class="estado_editable label label-danger m-t-lg">Inactivo</span>';
                                break;
                            case 0:
                                echo '<span style="cursor: pointer;" estado="0" archivo="' . $usu["cod_archivo"] . '" class="estado_editable label label-warning m-t-lg" >En Proceso</span>';
                                break;
                            case 1:
                                echo '<span style="cursor: pointer;" estado="1"  archivo="' . $usu["cod_archivo"] . '" class="estado_editable label label-info m-t-lg">Activo</span>';
                                break;
                        }
                        ?>
                    </td>
                    <?php foreach ($destinos as $destino) {
                        $permitido = 0;
                    ?>
                        <?php
                        foreach ($archivos_destinos as $ad) {
                            if ($ad["archivo_id"] != $usu["cod_archivo"]) {
                                continue;
                            }
                            if ($ad["destino_id"] != $destino["codigo"]) {
                                continue;
                            }
                            $permitido = $ad["codigo"];
                            break;
                        }
                        ?>
                        <td class="text-center" style="vertical-align: middle; width: 5%;">
                            <?php
                            if ($permitido > 0) {
                                echo '<span class="glyphicon glyphicon-ok opcion ok" archivo="' . $usu["cod_archivo"] . '" destino="' . $destino["codigo"] . '" permitido="' . $permitido . '"  style="color: #0A0;  cursor: pointer;" aria-hidden="true"></span>';
                            } else {
                                echo '<span class="glyphicon glyphicon-remove opcion nook" archivo="' . $usu["cod_archivo"] . '" destino="' . $destino["codigo"] . '" permitido="0" style="color: #A00; cursor: pointer; " aria-hidden="true"></span>';
                            }
                            ?>
                        </td>
                    <?php } ?>



                </tr>
            <?php } ?>
        </tbody>
    </table>
<?php } ?>
</div>

<script>
    $("#btnDescargar").click(function() {
        let valor = <?php echo $codigo1 ?>;
        let arr=<?php echo count($archivos)?>;
        
        if(arr!=0){
            window.location.href = "PDF/descargas.php?codigo="+valor;
        } else{
            alert("El archivo a descargar esta vacio");
        }
    });
    
    $("#btnPortada").click(function() {
        let combo1 = <?php echo $codigo1; ?>;
        let valorCodigoPP = <?php echo $valorCodigoPP; ?>;
        // let comboItemvendido = $("#subcategory").val();
        // alert(combo1);
        // if(combo1 != null){
        //     window.location.href="PDF/portadapdf.php?codigo="+combo1;
        //     // window.location.href="PDF/portadapdf.php?codigo="+combo1+"&codigoDetalle="+comboItemvendido;
        // }else{
        //     alert("El desplegable de los items que se vendieron no tienen datos, seleccione otra orden de trabajo");
        // }
        //  alert(combo1);
        //window.location.href = "PDF/portadapdf.php?codigo=" + combo1;
        window.open(
            "PDF/portadapdf.php?codigo=" + combo1 + "&valorCodigoPP=" + valorCodigoPP,
            '_blank' // <- This is what makes it open in a new window.
        );


    });
    var readonly = $(".container").attr("read");
    if (readonly == 1) {
        $(".opc_eliminar").css("display", "none");
    }

    // $("#botonDescargar").click(()=>{
    //     let array1=<?php $registrosPersonalizados?>
    //     array1.forEach(element => {
    //         alert(element["descripcion"]);
    //     });
    // })
    
    $(".ver").click(function () {
        codigo = $(this).closest('tr').attr("archivo_id");                        
        console.log(codigo);
        var win = window.open('pdf.php?archivo='+codigo, '_blank');
        if (win) {
            win.focus();
        } else {
            alert('Please allow popups for this website');
        }
    });

    $(".descargar").css("display","none");

    $(".descargar").click(function() {
        codigo = $(this).closest('tr').attr("codigo");
        otp = $(this).closest('tr').attr("otp");
        otd = $(this).closest('tr').attr("otd");
        ruta = $(this).closest('tr').attr("ruta");
        archivo = $(this).closest('tr').attr("archivo");
        var parametros = {
            funcion: "addOt_evento",
            detalle: otd,
            produccion: otp,
            evento: 5,
            destino: 0,
            observaciones: "Descarga archivo " + archivo + " (" + ruta + ")"
        }
        $.ajax({
            type: "POST",
            url: 'controller/ot_eventos.controller.php',
            data: parametros,
            success: function(datos) {
                if (parseInt(datos) == 0) {
                    var link = document.createElement('a');
                    document.body.appendChild(link);
                    link.download = archivo;
                    link.href = ruta;
                    link.click();
                    //location.reload();
                } else {
                    alert("Error");
                }
            },
            error: function() {
                alert("Error");
            }
        });
    });

    <?php if (in_array($_SESSION["rol"], [1,5])) { ?>
        $(".estado_editable").click(function() {
            var nuevo = $(this).closest('tr').attr("nuevo");
            var archivo = $(this).attr("archivo");
            var estado = $(this).attr("estado");
            var nuevo_estado = 0;
            if (estado == -1) {
                nuevo_estado = 0;
                return false;
            }
            if (estado == 0) {
                nuevo_estado = 1;
            }
            if (estado == 1) {
                nuevo_estado = -1;
            }

            if (estado == 1) {
                nuevo_estado = -1;
                codigo = $(this).closest('tr').attr("codigo");
                //$("#name-header-modal").html("<b>Eliminar</b>");
                //$("#text-header-body").html("¿Desea eliminar el registro ?");
                $("#btn-eliminar-archivo").attr("archivo", archivo);
                $("#btn-eliminar").css("display", "inline-block");
                $("#btn-cancelar").text("Cancelar");
                $('#myModal').modal('show');
                return false;
            }
            var readonly = $(".container").attr("read");
            if (readonly == 1) {
                return false;
            }
            var parametros = {
                funcion: "cambiar_estadoArchivo",
                codigo: archivo,
                estado: nuevo_estado,
            }
            if (nuevo == 1) {
                console.log(parametros);
                $.ajax({
                    type: "POST",
                    url: 'controller/archivo_destinos.controller.php',
                    data: parametros,
                    success: function(datos) {
                        buscarTabla();
                    },
                    error: function() {
                        alert("Error");
                    },
                    complete: function() {
                        requestSent = false;
                    }
                });
            }
        });
    <?php } ?>

    $(".opcion").click(function() {
        var nuevo = $(this).closest('tr').attr("nuevo");
        var archivo = $(this).attr("archivo");
        var destino = $(this).attr("destino");
        var permitido = $(this).attr("permitido");
        var readonly = $(".container").attr("read");
        if (readonly == 1) {
            return false;
        }
        var scrolly = $(".divespecial").scrollTop();
        var scrollx = $(".divespecial").scrollLeft();
        $("#div_tabla").attr("scrollx", scrollx);
        $("#div_tabla").attr("scrolly", scrolly);
        if (nuevo == 1) {
            if (permitido > 0) {
                var parametros = {
                    funcion: "deleteArchivo_destino",
                    codigo: permitido
                }
                console.log(parametros);
                $.ajax({
                    type: "POST",
                    url: 'controller/archivo_destinos.controller.php',
                    data: parametros,
                    success: function(datos) {
                        buscarTabla();
                    },
                    error: function() {
                        alert("Error");
                    },
                    complete: function() {
                        requestSent = false;
                    }
                });
            }
            if (permitido == 0) {
                var parametros = {
                    funcion: "addArchivo_destino",
                    archivo: archivo,
                    destino: destino,
                    observaciones: ""
                }
                console.log(parametros);
                $.ajax({
                    type: "POST",
                    url: 'controller/archivo_destinos.controller.php',
                    data: parametros,
                    success: function(datos) {
                        buscarTabla();
                    },
                    error: function() {
                        alert("Error");
                    },
                    complete: function() {
                        requestSent = false;
                    }
                });
            }
        }
    });

    $(".eliminar").click(function() {
        codigo = $(this).closest('tr').attr("codigo");        
        $('#myModalEliminar').modal('show');
        /*
        var parametros = {
            funcion: "deleteArchivoOtp",
            codigo: codigo
        }
        $.ajax({
            type: "POST",
            url: 'controller/archivos.controller.php',
            data: parametros,
            success: function(datos) {
                if (parseInt(datos) == 0) {
                    location.reload();
                } else {
                    alert("Error");
                }
            },
            error: function() {
                alert("Error");
            },
            complete: function() {
                //me.data('requestRunning', false);
                requestSent = false;
            }
        });
        event.preventDefault();
        */
    });

    $("#btn-eliminar-archivo").click(function() {
        var archivo = $(this).attr("archivo");
        var nuevo_estado = -1;
        var parametros = {
            funcion: "cambiar_estadoArchivo",
            codigo: archivo,
            estado: nuevo_estado,
        }
        $.ajax({
            type: "POST",
            url: 'controller/archivo_destinos.controller.php',
            data: parametros,
            success: function(datos) {
                location.reload();
            },
            error: function() {
                alert("Error");
            },
            complete: function() {
                requestSent = false;
            }
        });
    });

    $("#btn-eliminar-fila").click(function() {
        var parametros = {
            funcion: "deleteArchivoOtp",
            codigo: codigo
        }
        $.ajax({
            type: "POST",
            url: 'controller/archivos.controller.php',
            data: parametros,
            success: function(datos) {
                if (parseInt(datos) == 0) {
                    location.reload();
                } else {
                    alert("Error");
                }
            },
            error: function() {
                alert("Error");
            },
            complete: function() {
                //me.data('requestRunning', false);
                requestSent = false;
            }
        });
        event.preventDefault();
    });
</script>
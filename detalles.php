<?php

session_start();

//if (!($_SESSION["permisos"][basename(__FILE__, '.php') . ".php"]["access"])) {
//    header("Location: login.php");
//}

include $_SERVER['DOCUMENT_ROOT'] . "/Giuliani/controller/ot_detalles.controller.php";
$controlador = Ot_detallesController::singleton_ot_detalles();
$ots = $controlador->getOts();
$prioridades = $controlador->getPrioridades();
$estados = $controlador->getEstados();
$sectores = $controlador->getSectores();
$secciones = $controlador->getSecciones();
$unidades = $controlador->getUnidades();

$_SESSION["totales"] = $controlador->getCountOt_detalles();

// Recepcion parametros PAGINACION /*******************************************/

if (isset($_GET['pagina'])) {
    $pagina = $_GET['pagina'];
} else {
    if (isset($_POST['pagina'])) {
        $pagina = $_POST['pagina'];
    } else {
        $pagina = 1;
    }
}
$_SESSION["pagina"] = $pagina;

if (isset($_GET['cant_reg'])) {
    $cant_reg = $_GET['cant_reg'];
} else {
    if (isset($_POST['cant_reg'])) {
        $cant_reg = $_POST['cant_reg'];
    } else {
        $cant_reg = -1;
    }
}
$_SESSION["cant_reg"] = $cant_reg;

if (isset($_GET['busqueda'])) {
    $busqueda = $_GET['busqueda'];
} else {
    if (isset($_POST['busqueda'])) {
        $busqueda = $_POST['busqueda'];
    } else {
        $busqueda = "";
    }
}
$_SESSION["busqueda"] = $busqueda;

if (isset($_GET['orderby'])) {
    $orderby = $_GET['orderby'];
} else {
    if (isset($_POST['orderby'])) {
        $orderby = $_POST['orderby'];
    } else {
        $orderby = "codigo";
    }
}
$_SESSION["orderby"] = $orderby;

if (isset($_GET['sentido'])) {
    $sentido = $_GET['sentido'];
} else {
    if (isset($_POST['sentido'])) {
        $sentido = $_POST['sentido'];
    } else {
        $sentido = "asc";
    }
}
$_SESSION["sentido"] = $sentido;

// Fin Recepcion parametros PAGINACION /***************************************/

$_SESSION['menu'] = "ot_listados.php";

$_SESSION['breadcrumb'] = "Detalles de OT's";

$titlepage = "Detalles de OT's";

include 'inc/html/encabezado.php';

include 'inc/html/menu.php';

include 'inc/html/breadcrumb.php';

if ($_GET["cod_ot"] > 0){
    $_SESSION['ot'] = $_GET["cod_ot"];
}

$cod_ot = intval($_SESSION['ot']);

?>


<div class="container">

    <div class="row barra_descarga hidden">
        <div class="col-lg-7">
            <form action="upload.php?file=nueva_ot" method="post" id="selectorarchivo" enctype="multipart/form-data">
                <input type="file" name="fileToUpload" id="fileToUpload" style="font-size: 120%;font-weight: bold;">
                <input type="submit" value="Aceptar " name="submit" id="submitToUpload" style="display: none;">
            </form>
        </div>
        <div class="col-lg-5">
            <input type="button" id="descargar" name="descargar" class="btn-danger btn-sm boton_marron_carni" style="border-radius: 10px; margin-left: 10px;" value="Descargar"/>
        </div>
    </div>

    <div id="loading" class="loading"></div>
    
    <div id="modulo_paginacion">
        <?php include 'inc/html/paginacion_ots.php'; ?>
    </div>

    <div    id="div_tabla" 
            class="row col-lg-12" 
            style="float: none"
            registros="<?php echo $_SESSION['cant_reg']; ?>" 
            pagina="<?php echo $_SESSION['pagina']; ?>"
            orderby="<?php echo $_SESSION['orderby']; ?>"
            sentido="<?php echo $_SESSION['sentido']; ?>"
    >
        <!-- Devolución Ajax -->
    </div>

    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 id="name-header-modal" class="modal-title">Eliminar</h4>
                </div>
                <div class="modal-body text-center"  id="text-header-body">

                </div>
                <div class="modal-footer">
                    <button type="button" id="btn-eliminar-ot_detalle" name="btn-eliminar-ot_detalle" class="btn btn-danger boton_marron_carni" data-dismiss="modal" >Eliminar</button>
                    <button type="button" id="btn-cancelar" name="btn-cancelar" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
    
    <form id="guardarDatosOt_prodperso">
        <div class="modal fade" id="dataProdperso" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="exampleModalLabel">Producto Personalizado</h4>
                    </div>
                    <div class="modal-body">
                        <div id="datos_ajax_register"></div>
                        <div class="form-group">
                            <label for="nombre0" class="control-label">Item Producido:</label>
                            <input type="text" class="form-control" id="prodpersoAdd" name="prodpersoAdd" required maxlength="100">
                        </div>
                        <div class="form-group">
                            <label for="nombre0" class="control-label">Cantidad:</label>
                            <input type="text" class="form-control" id="cantidadpersoAdd" name="cantidadpersoAdd" required maxlength="100">
                        </div>
                        <div class="form-group" style="display: none;"> 
                            <label for="nombre0" class="control-label">Prioridad:</label>
                            <select id="prioridadpersoAdd" style="width: 100%;" class="form-control" name="prioridadpersoAdd"  required>
                                <?php 
                                    foreach ($prioridades as $aux) { 
                                ?>
                                        <option value="<?php echo $aux["codigo"]; ?>" ><?php echo $aux["descripcion"]; ?></option>
                                <?php 
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group"> 
                            <label for="nombre0" class="control-label">Unidad:</label>
                            <select id="unidadAdd" style="width: 100%;" class="form-control" name="unidadAdd"  required>
                                <?php 
                                    foreach ($unidades as $aux) { 
                                ?>
                                        <option value="<?php echo $aux["codigo"]; ?>" ><?php echo $aux["descripcion"] . " (" . $aux["descrip_abrev"] . ")"; ?></option>
                                <?php 
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="nombre0" class="control-label">Observaciones:</label>
                            <input type="text" class="form-control" id="observacionespersoAdd" name="observacionespersoAdd" >
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger boton_marron_carni" >Guardar datos</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <form id="guardarDatosOt_detalle">
        <div class="modal fade" id="dataRegister" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="exampleModalLabel">Nuevo</h4>
                    </div>
                    <div class="modal-body">
                        <div id="datos_ajax_register"></div>
                        <div class="form-group">
                            <label for="nombre0" class="control-label">Item Vendido:</label>
                            <input type="text" class="form-control" id="itemAdd" name="itemAdd" required maxlength="100">
                        </div>
                        <div class="form-group">
                            <label for="nombre0" class="control-label">Cantidad:</label>
                            <input type="text" class="form-control" id="cantidadAdd" name="cantidadAdd" required maxlength="100">
                        </div>
                        <div class="form-group"> 
                            <label for="nombre0" class="control-label">Sección:</label>
                            <input type="text" class="form-control" id="seccionAdd" name="seccionAdd" required maxlength="100">
                            <!--<select id="seccionAdd" style="width: 100%;" class="form-control" name="seccionAdd"  required>
                                <?php 
                                    foreach ($secciones as $aux) { 
                                ?>
                                        <option value="<?php echo $aux["codigo"]; ?>" ><?php echo $aux["descripcion"]; ?></option>
                                <?php 
                                    }
                                ?>
                            </select>-->
                        </div>
                        <div class="form-group"> 
                            <label for="nombre0" class="control-label">Sector:</label>
                            <input type="text" class="form-control" id="sectorAdd" name="sectorAdd" required maxlength="100">
                            <!--<select id="sectorAdd" style="width: 100%;" class="form-control" name="sectorAdd"  required>
                                <?php 
                                    foreach ($sectores as $aux) { 
                                ?>
                                        <option value="<?php echo $aux["codigo"]; ?>" ><?php echo $aux["descripcion"]; ?></option>
                                <?php 
                                    }
                                ?>
                            </select>-->
                        </div>
                        <div class="form-group" style="display: none;"> 
                            <label for="nombre0" class="control-label">Estados:</label>
                            <select id="estadoAdd" style="width: 100%;" class="form-control" name="estadoAdd"  required>
                                <?php 
                                    foreach ($estados as $aux) { 
                                ?>
                                        <option value="<?php echo $aux["codigo"]; ?>" ><?php echo $aux["descripcion"]; ?></option>
                                <?php 
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group"  style="display: none;"> 
                            <label for="nombre0" class="control-label">Prioridad:</label>
                            <select id="prioridadAdd" style="width: 100%;" class="form-control" name="prioridadAdd"  required>
                                <?php 
                                    foreach ($prioridades as $aux) { 
                                ?>
                                        <option value="<?php echo $aux["codigo"]; ?>" ><?php echo $aux["descripcion"]; ?></option>
                                <?php 
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group" style="display: none;"> 
                            <label for="nombre0" class="control-label">#OT:</label>
                            <select id="otAdd" style="width: 100%;" class="form-control" name="otAdd"  required>
                                <?php 
                                    foreach ($ots as $aux) { 
                                ?>
                                        <option value="<?php echo $aux["codigo"]; ?>" ><?php echo $aux["descripcion"]; ?></option>
                                <?php 
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="nombre0" class="control-label">Observaciones:</label>
                            <input type="text" class="form-control" id="observacionesAdd" name="observacionesAdd" >
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger boton_marron_carni" >Guardar datos</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <form id="actualidarDatosOt_detalle">
        <div class="modal fade" id="dataUpdate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="exampleModalLabel">Modificar</h4>
                    </div>
                    <div class="modal-body">
                        <div id="datos_ajax_register"></div>
                        <div class="form-group">
                            <label for="nombre0" class="control-label">Item Vendido:</label>
                            <input type="text" class="form-control" id="itemUpdate" name="itemUpdate" required maxlength="100">
                        </div>
                        <div class="form-group">
                            <label for="nombre0" class="control-label">Cantidad:</label>
                            <input type="text" class="form-control" id="cantidadUpdate" name="cantidadUpdate" required maxlength="100">
                        </div>
                        <div class="form-group"> 
                            <label for="nombre0" class="control-label">Sección:</label>
                            <input type="text" class="form-control" id="seccionUpdate" name="seccionUpdate" required maxlength="100">
                            <!--<select id="seccionUpdate" style="width: 100%;" class="form-control" name="seccionUpdate"  required>
                                <?php 
                                    foreach ($secciones as $aux) { 
                                ?>
                                        <option value="<?php echo $aux["codigo"]; ?>" ><?php echo $aux["descripcion"]; ?></option>
                                <?php 
                                    }
                                ?>
                            </select>-->
                        </div>
                        <div class="form-group"> 
                            <label for="nombre0" class="control-label">Sector:</label>
                            <input type="text" class="form-control" id="sectorUpdate" name="sectorUpdate" required maxlength="100">
                            <!--<select id="sectorUpdate" style="width: 100%;" class="form-control" name="sectorUpdate"  required>
                                <?php 
                                    foreach ($sectores as $aux) { 
                                ?>
                                        <option value="<?php echo $aux["codigo"]; ?>" ><?php echo $aux["descripcion"]; ?></option>
                                <?php 
                                    }
                                ?>
                            </select>-->
                        </div>
                        <div class="form-group"> 
                            <label for="nombre0" class="control-label">Estados:</label>
                            <select id="estadoUpdate" style="width: 100%;" class="form-control" name="estadoUpdate"  required>
                                <?php 
                                    foreach ($estados as $aux) { 
                                ?>
                                        <option value="<?php echo $aux["codigo"]; ?>" ><?php echo $aux["descripcion"]; ?></option>
                                <?php 
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group" style="display: none;"> 
                            <label for="nombre0" class="control-label">Prioridad:</label>
                            <select id="prioridadUpdate" style="width: 100%;" class="form-control" name="prioridadUpdate"  required>
                                <?php 
                                    foreach ($prioridades as $aux) { 
                                ?>
                                        <option value="<?php echo $aux["codigo"]; ?>" ><?php echo $aux["descripcion"]; ?></option>
                                <?php 
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group" style="display: none;"> 
                            <label for="nombre0" class="control-label">#OT:</label>
                            <select id="otUpdate" style="width: 100%;" class="form-control" name="otUpdate"  required>
                                <?php 
                                    foreach ($ots as $aux) { 
                                ?>
                                        <option value="<?php echo $aux["codigo"]; ?>" ><?php echo $aux["descripcion"]; ?></option>
                                <?php 
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="nombre0" class="control-label">Observaciones:</label>
                            <input type="text" class="form-control" id="observacionesUpdate" name="observacionesUpdate" >
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger boton_marron_carni" >Actualizar datos</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    
    
    <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12 right">
        <input type="button" id="export" name="export" class="btn-danger btn-sm boton_marron_carni" style="border-radius: 10px; margin-left: 10px;" value="Exportar"/>
    </div>
    <div class="hidden-lg hidden-md">
        <br /><br />
    </div>
    
</div>

<div class="div_add" style="bottom: 50px; right: 40px; position: fixed;">
    <button id="add" name="add" type="button" class="btn btn-danger btn-lg boton_marron_carni" style="width: 50px;border-radius: 50%; text-align: center; background-color: transparent;"><i style="margin-bottom: 5px;" class="glyphicon glyphicon-plus"></i></button>
</div>

<?php

include 'inc/html/footer.php';

?>

<script type="text/javascript" src="inc/js/detalles_js.js?version=<?php echo date("Y-m-d H:i:s"); ?>"></script>
<script type="text/javascript" src="inc/js/utils.js"></script>
<script type="text/javascript" src="inc/js/jquery.table2excel.js"></script>

<script>
    $("#fileToUpload").click(function () {
        //alert(1);
    });

    $("#fileToUpload").change(function (e) {
        //alert(1);
        $("#submitToUpload").click();
        //location.reload();
    });
    
    $("#download").click(function (){
        window.location.href = 'uploads/model_ot.xls';
    });
</script>
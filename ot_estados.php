<?php

session_start();

if (!($_SESSION["permisos"][basename(__FILE__, '.php') . ".php"]["access"])) {
    header("Location: login.php");
}

include $_SERVER['DOCUMENT_ROOT'] . "/Giuliani/controller/ot_estados.controller.php";
$controlador = Ot_estadosController::singleton_ot_estados();
$ots_detalles = $controlador->getOtsDetalles();
$ots_produccions = $controlador->getOtsProduccions();
$estados = $controlador->getEstados();
$destinos = $controlador->getDestinos();

$_SESSION["totales"] = $controlador->getCountOt_estados();

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
        $cant_reg = 25;
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

$_SESSION['menu'] = "ot_estados.php";

$_SESSION['breadcrumb'] = "Ot_estados";

$titlepage = "Ot_estados";

include 'inc/html/encabezado.php';

include 'inc/html/menu.php';

include 'inc/html/breadcrumb.php';

?>


<div class="container">

    <div id="loading" class="loading"></div>
    
    <div id="modulo_paginacion">
        <?php include 'inc/html/paginacion.php'; ?>
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
                    <button type="button" id="btn-eliminar-ot_estado" name="btn-eliminar-ot_estado" class="btn btn-danger boton_marron_carni" data-dismiss="modal" >Eliminar</button>
                    <button type="button" id="btn-cancelar" name="btn-cancelar" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <form id="guardarDatosOt_estado">
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
                            <label for="nombre0" class="control-label">#OT Prod:</label>
                            <select id="produccionAdd" style="width: 100%;" class="form-control" name="produccionAdd"  required>
                                <?php 
                                    foreach ($ots_produccions as $aux) { 
                                ?>
                                        <option value="<?php echo $aux["codigo"]; ?>" ><?php echo $aux["descripcion"]; ?></option>
                                <?php 
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group"> 
                            <label for="nombre0" class="control-label">Estado:</label>
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
                        <div class="form-group"> 
                            <label for="nombre0" class="control-label">Destino:</label>
                            <select id="destinoAdd" style="width: 100%;" class="form-control" name="destinoAdd"  required>
                                <?php 
                                    foreach ($destinos as $aux) { 
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

    <form id="actualidarDatosOt_estado">
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
                            <label for="nombre0" class="control-label">#OT Prod:</label>
                            <select id="produccionUpdate" style="width: 100%;" class="form-control" name="produccionUpdate"  required>
                                <?php 
                                    foreach ($ots_produccions as $aux) { 
                                ?>
                                        <option value="<?php echo $aux["codigo"]; ?>" ><?php echo $aux["descripcion"]; ?></option>
                                <?php 
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group"> 
                            <label for="nombre0" class="control-label">#OT Detalle:</label>
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
                        <div class="form-group"> 
                            <label for="nombre0" class="control-label">#OT Detalle:</label>
                            <select id="destinoUpdate" style="width: 100%;" class="form-control" name="destinoUpdate"  required>
                                <?php 
                                    foreach ($destinos as $aux) { 
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

<div style="bottom: 50px; right: 40px; position: fixed;">
    <button id="add" name="add" type="button" class="btn btn-danger btn-lg boton_marron_carni" style="width: 50px;border-radius: 50%; text-align: center; background-color: transparent;"><i style="margin-bottom: 5px;" class="glyphicon glyphicon-plus"></i></button>
</div>

<?php

include 'inc/html/footer.php';

?>

<script type="text/javascript" src="inc/js/ot_estados_js.js?version=<?php echo date("Y-m-d H:i:s"); ?>"></script>
<script type="text/javascript" src="inc/js/utils.js"></script>
<script type="text/javascript" src="inc/js/jquery.table2excel.js"></script>
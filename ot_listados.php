<?php

session_start();

if (!($_SESSION["permisos"][basename(__FILE__, '.php') . ".php"]["access"])) {
    header("Location: login.php");
}

include $_SERVER['DOCUMENT_ROOT'] . "/Giuliani/controller/ot_listados.controller.php";
$controlador = Ot_listadosController::singleton_ot_listados();

$_SESSION["totales"] = $controlador->getCountOt_listados();
$prioridades = $controlador->getPrioridades();
$tipos = $controlador->getTipos();

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
        $orderby = "fecha";
    }
}
$_SESSION["orderby"] = $orderby;

if (isset($_GET['sentido'])) {
    $sentido = $_GET['sentido'];
} else {
    if (isset($_POST['sentido'])) {
        $sentido = $_POST['sentido'];
    } else {
        $sentido = "desc";
    }
}
$_SESSION["sentido"] = $sentido;

// Fin Recepcion parametros PAGINACION /***************************************/

$_SESSION['menu'] = "ot_listados.php";

$_SESSION['breadcrumb'] = "OT's listado";

$titlepage = "OT's listado";

include 'inc/html/encabezado.php';

include 'inc/html/menu.php';

include 'inc/html/breadcrumb.php';

if ($_SESSION["permisos_globales"][$_SESSION["menu"]] == 0){
    header("Location: login.php");
    exit();
}



?>


<div class="container">

    <div id="loading" class="loading"></div>
    
    <div id="modulo_paginacion">
        <?php include 'inc/html/paginacion_header_ot_tipo.php'; ?>
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

    <div id="modulo_paginacion">
        <?php include 'inc/html/paginacion_footer.php'; ?>
    </div>

    <div class="modal fade" id="myModalEstadoAll" role="dialog">
        <div class="modal-dialog modal-sm" id="dataRegisterEstadoAll">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 id="my-name-header-modal" class="modal-title">Cambiar Estado</h4>
                </div>
                <div class="modal-body text-center"  id="my-text-header-body">
                    <div class="alert alert-info" id="info_ot_modal" style="margin-bottom: 15px; text-align: left;">
                        <strong>OT:</strong> - | <strong>Cliente:</strong> -
                    </div>
                    <?php if (in_array($_SESSION["rol"], [1,5])) { ?>
                        <div class="form-group"> 
                            <label for="nombre0" class="control-label">Estado Ingeniería:</label>
                            <select id="estadoIngAdd" style="width: 100%;" class="form-control" name="estadoIngAdd"  required>
                                <option value="0" ><?php echo "En Cola"; ?></option>
                                <option value="2" ><?php echo "En Curso"; ?></option>
                                <option value="1" ><?php echo "Finalizado"; ?></option>
                                <option value="-1" ><?php echo "Cancelado"; ?></option>
                            </select>
                        </div>                    
                    <?php } ?>
                    <?php if (in_array($_SESSION["rol"], [1,8])) { ?>
                        <div class="form-group"> 
                            <label for="nombre0" class="control-label">Estado Producción:</label>
                            <select id="estadoProdAdd" style="width: 100%;" class="form-control" name="estadoProdAdd"  required>
                                <option value="0" ><?php echo "En Cola"; ?></option>
                                <option value="2" ><?php echo "En Curso"; ?></option>
                                <option value="1" ><?php echo "Finalizado"; ?></option>
                                <option value="-1" ><?php echo "Cancelado"; ?></option>
                            </select>
                        </div>                    
                    <?php } ?>
                    <?php if (in_array($_SESSION["rol"], [1,8])) { ?>
                        <div class="form-group"> 
                            <label for="nombre0" class="control-label">Estado Despacho:</label>
                            <select id="estadoDespachoAdd" style="width: 100%;" class="form-control" name="estadoDespachoAdd"  required>
                                <option value="0" ><?php echo "En Cola"; ?></option>
                                <option value="2" ><?php echo "En Curso"; ?></option>
                                <option value="1" ><?php echo "Finalizado"; ?></option>
                                <option value="-1" ><?php echo "Cancelado"; ?></option>
                            </select>
                        </div>                    
                    <?php } ?>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btn-estado-ot_listado_all" name="btn-estado-ot_listado" class="btn btn-danger boton_marron_carni" data-dismiss="modal" >Aceptar</button>
                    <button type="button" id="btn-cancelar" name="btn-cancelar" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="myModalEstado" role="dialog">
        <div class="modal-dialog modal-sm" id="dataRegisterEstado">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 id="my-name-header-modal" class="modal-title">Cambiar Estado</h4>
                </div>
                <div class="modal-body text-center"  id="my-text-header-body">
                    <div class="alert alert-info" id="info_ot_modal_simple" style="margin-bottom: 15px; text-align: left;">
                        <strong>OT:</strong> - | <strong>Cliente:</strong> -
                    </div>
                    <div class="form-group"> 
                        <label for="nombre0" class="control-label">Estado:</label>
                        <select id="estadoAdd" style="width: 100%;" class="form-control" name="estadoAdd"  required>
                            <option value="0" ><?php echo "En Cola"; ?></option>
                            <option value="2" ><?php echo "En Curso"; ?></option>
                            <option value="1" ><?php echo "Finalizado"; ?></option>
                            <option value="-1" ><?php echo "Cancelado"; ?></option>
                        </select>
                    </div>                    
                    <div class="form-group">
                        <label for="nombre0" class="control-label">Avance:</label>
                        <input type="text" class="form-control" id="avanceAdd" name="avanceAdd" >
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btn-estado-ot_listado" name="btn-estado-ot_listado" class="btn btn-danger boton_marron_carni" data-dismiss="modal" >Aceptar</button>
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
                    <h4 id="name-header-modal" class="modal-title">Eliminar</h4>
                </div>
                <div class="modal-body text-center"  id="text-header-body">

                </div>
                <div class="modal-footer">
                    <button type="button" id="btn-eliminar-ot_listado" name="btn-eliminar-ot_listado" class="btn btn-danger boton_marron_carni" data-dismiss="modal" >Eliminar</button>
                    <button type="button" id="btn-cancelar" name="btn-cancelar" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <form id="guardarDatosOt_listado">
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
                            <label for="nombre0" class="control-label">Nro. Serie:</label>
                            <input type="text" class="form-control" id="nroserieAdd" name="nroserieAdd" required maxlength="100">
                        </div>
                        <div class="form-group">
                            <label for="nombre0" class="control-label">Cliente:</label>
                            <input type="text" class="form-control" id="clienteAdd" name="clienteAdd" required maxlength="100">
                        </div>
                        <div class="form-group"> 
                            <label for="nombre0" class="control-label">Tipo:</label>
                            <select id="tipoAdd" style="width: 100%;" class="form-control" name="tipoAdd"  required>
                                <?php 
                                    foreach ($tipos as $aux) { 
                                        $selected = "";
                                        if ($aux["codigo"] == 0){
                                            $selected = "selected";
                                        }
                                ?>
                                        <option value="<?php echo $aux["codigo"]; ?>" <?php echo $selected; ?>><?php echo $aux["nombre"]; ?></option>
                                <?php 
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group hidden"> 
                            <label for="nombre0" class="control-label">Prioridad:</label>
                            <select id="prioridadAdd" style="width: 100%;" class="form-control" name="prioridadAdd"  required>
                                <?php 
                                    foreach ($prioridades as $aux) { 
                                        $selected = "";
                                        if ($aux["prioridad"] == 4){
                                            $selected = "selected";
                                        }
                                ?>
                                        <option value="<?php echo $aux["codigo"]; ?>" <?php echo $selected; ?>><?php echo $aux["descripcion"]; ?></option>
                                <?php 
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="nombre0" class="control-label">Fecha Ingreso:</label>
                            <input type="date" class="form-control" id="fechaAdd" name="fechaAdd" required maxlength="10" value="<?php echo date("Y-m-d"); ?>" >
                        </div>
                        <div class="form-group">
                            <label for="nombre0" class="control-label">Fecha Entrega:</label>
                            <input type="date" class="form-control" id="fechaEntregaAdd" name="fechaEntregaAdd" maxlength="10" value="" >
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

    <form id="actualidarDatosOt_listado">
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
                            <label for="nombre0" class="control-label">Nro. Serie:</label>
                            <input type="text" class="form-control" id="nroserieUpdate" name="nroserieUpdate" required maxlength="100">
                        </div>
                        <div class="form-group">
                            <label for="nombre0" class="control-label">Cliente:</label>
                            <input type="text" class="form-control" id="clienteUpdate" name="clienteUpdate" required maxlength="100">
                        </div>
                        <div class="form-group"> 
                            <label for="nombre0" class="control-label">Tipo:</label>
                            <select id="tipoUpdate" style="width: 100%;" class="form-control" name="tipoUpdate"  required>
                                <?php 
                                    foreach ($tipos as $aux) { 
                                ?>
                                        <option value="<?php echo $aux["codigo"]; ?>" ><?php echo $aux["nombre"]; ?></option>
                                <?php 
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group hidden"> 
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
                        <div class="form-group">
                            <label for="nombre0" class="control-label">Fecha Ingreso:</label>
                            <input type="date" class="form-control" id="fechaUpdate" name="fechaUpdate" required maxlength="10">
                        </div>
                        <div class="form-group">
                            <label for="nombre0" class="control-label">Fecha Entrega:</label>
                            <input type="date" class="form-control" id="fechaEntregaUpdate" name="fechaEntregaUpdate" maxlength="10" value="" >
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
    <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12 right">
        <input type="button" id="seguimientos" name="seguimientos" class="btn-danger btn-sm boton_marron_carni" style="border-radius: 10px; margin-left: 10px;" value="Seguimiento"/>
    </div>
    
</div>

<?php if ($_SESSION["permisos_globales"][$_SESSION["menu"]] > 1) { ?>
    <div style="bottom: 50px; right: 40px; position: fixed;">
        <button id="add" name="add" type="button" class="btn btn-danger btn-lg boton_marron_carni" style="width: 50px;border-radius: 50%; text-align: center; background-color: transparent;"><i style="margin-bottom: 5px;" class="glyphicon glyphicon-plus"></i></button>
    </div>
<?php } ?>

<?php

include 'inc/html/footer.php';

?>

<script type="text/javascript" src="inc/js/ot_listados_js.js?version=<?php echo date("Y-m-d H:i:s"); ?>"></script>
<script type="text/javascript" src="inc/js/utils.js"></script>
<script type="text/javascript" src="inc/js/jquery.table2excel.js"></script>
<?php

session_start();

//if (!($_SESSION["permisos"][basename(__FILE__, '.php') . ".php"]["access"])) {
    //header("Location: login.php");
//}

include $_SERVER['DOCUMENT_ROOT'] . "/Giuliani/controller/ot_detalles.controller.php";
$controlador = Ot_detallesController::singleton_ot_detalles();
$ots = $controlador->getOts();
$prioridades = $controlador->getPrioridades();
$motivos = $controlador->getMotivos();
$usuarios = $controlador->getUsuarios();
$estados = $controlador->getEstados();
$sectores = $controlador->getSectores();
$secciones = $controlador->getSecciones();
$roles = $controlador->getRoles();
$destinos = $controlador->getDestinos();
$destinos_rol = $controlador->getMenuDestinos($_SESSION["rol"]);

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

$_SESSION['menu'] = "ot_seguimientos.php";

$_SESSION['breadcrumb'] = "OT - Seguimientos";

$titlepage = "OT - Seguimientos";

include 'inc/html/encabezado.php';

include 'inc/html/menu.php';

include 'inc/html/breadcrumb.php';

if ($_GET["cod_ot"] > 0){
    $_SESSION['cod_ot'] = $_GET["cod_ot"];
}

$cod_ot = intval($_SESSION['cod_ot']);


?>


<div class="container" rol="<?php echo $_SESSION["rol"]; ?>" destinos="<?php echo $_SESSION["destinos"]; ?>">

    <div id="loading" class="loading"></div>
    
    <div id="modulo_paginacion">
        <?php include 'inc/html/paginacion_ots_seg.php'; ?>
    </div>

    <div    id="div_tabla" 
            scrollx="0" scrolly="0"
            class="row col-lg-12" 
            style="float: none"
            registros="<?php echo $_SESSION['cant_reg']; ?>" 
            pagina="<?php echo $_SESSION['pagina']; ?>"
            orderby="<?php echo $_SESSION['orderby']; ?>"
            sentido="<?php echo $_SESSION['sentido']; ?>"
    >
        <!-- Devolución Ajax -->
    </div>
    
    <form id="actualidarDatosOt_produccion">
        <div class="modal fade" id="dataUpdate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="exampleModalLabel">Modificar</h4>
                    </div>
                    <div class="modal-body">
                        <div id="datos_ajax_register"></div>
                        <div class="form-group div_avance">
                            <label for="nombre0" class="control-label ">Avance:</label>
                            <input type="text" class="form-control" id="avanceUpdate" name="avanceUpdate" required maxlength="100">
                        </div>
                        <div class="form-group div_estado"> 
                            <label for="nombre0" class="control-label">Estados:</label>
                            <select id="estadoUpdate" style="width: 100%;" class="form-control" name="estadoUpdate"  required>
                                <?php 
                                    foreach ($estados as $aux) { 
                                ?>
                                        <option class="opcion_estado" estado="<?php echo $aux["codigo"]; ?>" value="<?php echo $aux["codigo"]; ?>" ><?php echo $aux["descripcion"]; ?></option>
                                <?php 
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group div_avance">
                            <label for="nombre0" class="control-label ">Observaciones:</label>
                            <input type="text" class="form-control" id="observacionesUpdate" name="observacionesUpdate" maxlength="100">
                        </div>
                        <div class="form-group div_quien div_cambio_apro">
                            <label for="nombre0" class="control-label ">Solicita:</label>
                            <select id="quienUpdate" style="width: 100%;" class="form-control" name="quienUpdate" >
                                <?php 
                                    foreach ($usuarios as $aux) { 
                                ?>
                                        <option class="opcion_estado" estado="<?php echo $aux["codigo"]; ?>" value="<?php echo $aux["codigo"]; ?>" ><?php echo $aux["usuario"]; ?></option>
                                <?php 
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group div_motivo div_cambio_apro"> 
                            <label for="nombre0" class="control-label">Motivo:</label>
                            <select id="motivoUpdate" style="width: 100%;" class="form-control" name="motivoUpdate" >
                                <?php 
                                    foreach ($motivos as $aux) { 
                                ?>
                                        <option class="opcion_estado" estado="<?php echo $aux["codigo"]; ?>" value="<?php echo $aux["codigo"]; ?>" ><?php echo $aux["descripcion"]; ?></option>
                                <?php 
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group div_descripcion div_cambio_apro">
                            <label for="nombre0" class="control-label ">Descripcion:</label>
                            <input type="text" class="form-control" id="descripcionUpdate" name="descripcionUpdate"  maxlength="100">
                        </div>
                        <div class="form-group div_alcance" style="display: none;"> 
                            <label for="nombre0" class="control-label">Alcance:</label>
                            <select id="alcanceUpdate" style="width: 100%;" class="form-control" name="alcanceUpdate"  required>
                                <option class="opcion_alcance" value="0" >En Proceso</option>
                                <option class="opcion_alcance" value="1" >Finalizado</option>
                            </select>
                        </div>
                        <div class="form-group div_plano" style="display: none;"> 
                            <label for="nombre0" class="control-label">Planos:</label>
                            <select id="planoUpdate" style="width: 100%;" class="form-control" name="planoUpdate"  required>
                                <option class="opcion_plano" value="0" >En Proceso</option>
                                <option class="opcion_plano" value="1" >Finalizado</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger boton_marron_carni " >Actualizar datos</button>
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

<?php

include 'inc/html/footer.php';

?>

<script type="text/javascript" src="inc/js/ot_seguimientos_js.js?version=<?php echo date("Y-m-d H:i:s"); ?>"></script>
<script type="text/javascript" src="inc/js/utils.js"></script>
<script type="text/javascript" src="inc/js/jquery.table2excel.js"></script>
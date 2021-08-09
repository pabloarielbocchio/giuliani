<?php

session_start();

//if (!($_SESSION["permisos"][basename(__FILE__, '.php') . ".php"]["access"])) {
    //header("Location: login.php");
//}

include $_SERVER['DOCUMENT_ROOT'] . "/Giuliani/controller/ot_detalles.controller.php";
$controlador = Ot_detallesController::singleton_ot_detalles();
$ots = $controlador->getOts();
$prioridades = $controlador->getPrioridades();
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

$_SESSION['menu'] = "ot_archivos.php";

$_SESSION['breadcrumb'] = "OT - Archivos";

$titlepage = "OT - Archivos";

include 'inc/html/encabezado.php';

include 'inc/html/menu.php';

include 'inc/html/breadcrumb.php';

if ($_GET["cod_ot"] > 0){
    $_SESSION['cod_ot'] = $_GET["cod_ot"];
}
$_SESSION['ot'] = $_SESSION['cod_ot'];
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
    
</div>

<?php

include 'inc/html/footer.php';

?>

<script type="text/javascript" src="inc/js/ot_archivos_js.js?version=<?php echo date("Y-m-d H:i:s"); ?>"></script>
<script type="text/javascript" src="inc/js/utils.js"></script>
<script type="text/javascript" src="inc/js/jquery.table2excel.js"></script>
<?php

session_start();

//if (!($_SESSION["permisos"][basename(__FILE__, '.php') . ".php"]["access"])) {
    //header("Location: login.php");
//}

include $_SERVER['DOCUMENT_ROOT'] . "/Giuliani/controller/dashboard.controller.php";
$controlador = DashboardController::singleton_dashboard();

$_SESSION['menu'] = "dashboard.php";

$_SESSION['breadcrumb'] = "Dashboard";

$titlepage = "Dashboard - Giuliani";

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
    
    <!-- Filtros -->
    <div class="panel panel-default" style="margin-bottom: 20px;">
        <div class="panel-heading">
            <h4><i class="fa fa-filter"></i> Filtros</h4>
        </div>
        <div class="panel-body">
            <form id="filtrosDashboard" class="form-inline">
                <div class="form-group" style="margin-right: 15px;">
                    <label for="fecha_desde" style="margin-right: 5px;">Fecha Desde:</label>
                    <input type="date" class="form-control" id="fecha_desde" name="fecha_desde" value="<?php echo date('Y-m-d', strtotime('-30 days')); ?>">
                </div>
                <div class="form-group" style="margin-right: 15px;">
                    <label for="fecha_hasta" style="margin-right: 5px;">Fecha Hasta:</label>
                    <input type="date" class="form-control" id="fecha_hasta" name="fecha_hasta" value="<?php echo date('Y-m-d'); ?>">
                </div>
                <button type="button" class="btn btn-primary boton_marron_carni" id="aplicarFiltros">
                    <i class="fa fa-search"></i> Aplicar
                </button>
            </form>
        </div>
    </div>

    <!-- Contenido del Dashboard -->
    <div id="content-dashboard">
        <!-- Contenido cargado por AJAX -->
    </div>
</div>

<?php
include 'inc/html/footer.php';
?>

<!-- Chartist CSS -->
<link href="inc/chartist/chartist.css" rel="stylesheet">
<!-- Chartist JS -->
<script src="inc/chartist/chartist.min.js"></script>
<script type="text/javascript" src="inc/js/dashboard_js.js?version=<?php echo date("Y-m-d H:i:s"); ?>"></script>


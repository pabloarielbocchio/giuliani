<?php
ob_start();
session_start();

if (!($_SESSION["permisos"][basename(__FILE__, '.php') . ".php"]["access"])) {
    header("Location: login.php");
}

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
    
    <!-- Filtros Globales -->
    <div class="panel panel-default" style="margin-bottom: 20px;">
        <div class="panel-heading" style="cursor: pointer;" data-toggle="collapse" data-target="#filtrosCollapse" aria-expanded="true" aria-controls="filtrosCollapse">
            <h4 style="margin: 0;">
                <i class="fa fa-filter"></i> Filtros
                <i class="fa fa-chevron-up pull-right" id="filtrosIcon" style="margin-top: 5px;"></i>
            </h4>
        </div>
        <div id="filtrosCollapse" class="panel-collapse collapse in" aria-expanded="true">
            <div class="panel-body">
                <form id="filtrosGlobales" class="form-inline">
                    <div class="form-group" style="margin-right: 15px;">
                        <label for="fecha_desde" style="margin-right: 5px;">Fecha Desde:</label>
                        <input type="date" class="form-control" id="fecha_desde" name="fecha_desde" value="<?php echo date('Y-m-d', strtotime('-7 days')); ?>">
                    </div>
                    <div class="form-group" style="margin-right: 15px;">
                        <label for="fecha_hasta" style="margin-right: 5px;">Fecha Hasta:</label>
                        <input type="date" class="form-control" id="fecha_hasta" name="fecha_hasta" value="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <button type="button" class="btn btn-primary boton_marron_carni" id="aplicarFiltrosGlobales">
                        <i class="fa fa-search"></i> Aplicar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs" role="tablist" id="dashboardTabs" style="margin-top: 20px;">
        <li role="presentation" class="active">
            <a href="#tab-ejecutivo" aria-controls="tab-ejecutivo" role="tab" data-toggle="tab">
                <i class="fa fa-dashboard"></i> Dashboard
            </a>
        </li>
        <li role="presentation">
            <a href="#tab-subidas" aria-controls="tab-subidas" role="tab" data-toggle="tab">
                <i class="fa fa-upload"></i> Archivos - Subidas
            </a>
        </li>
        <li role="presentation">
            <a href="#tab-descargas" aria-controls="tab-descargas" role="tab" data-toggle="tab">
                <i class="fa fa-download"></i> Archivos - Descargas
            </a>
        </li>
        <li role="presentation">
            <a href="#tab-usuarios" aria-controls="tab-usuarios" role="tab" data-toggle="tab">
                <i class="fa fa-users"></i> Usuarios
            </a>
        </li>
        <li role="presentation">
            <a href="#tab-ot" aria-controls="tab-ot" role="tab" data-toggle="tab">
                <i class="fa fa-tasks"></i> Órdenes de Trabajo
            </a>
        </li>
        <li role="presentation">
            <a href="#tab-proyectos" aria-controls="tab-proyectos" role="tab" data-toggle="tab">
                <i class="fa fa-folder-open"></i> Proyectos
            </a>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="dashboardContent" style="margin-top: 20px;">
        <div role="tabpanel" class="tab-pane active" id="tab-ejecutivo">
            <!-- Contenido del Dashboard Ejecutivo -->
            <div id="content-dashboard">
                <!-- Contenido cargado por AJAX -->
            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="tab-subidas">
            <!-- Contenido de Subidas -->
            <div id="content-subidas">
                <!-- Contenido cargado por AJAX -->
            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="tab-descargas">
            <!-- Contenido de Descargas -->
            <div id="content-descargas">
                <!-- Contenido cargado por AJAX -->
            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="tab-usuarios">
            <!-- Contenido de Usuarios -->
            <div id="content-usuarios">
                <!-- Contenido cargado por AJAX -->
            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="tab-ot">
            <!-- Contenido de OT -->
            <div id="content-ot">
                <!-- Contenido cargado por AJAX -->
            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="tab-proyectos">
            <!-- Contenido de Proyectos -->
            <div id="content-proyectos">
                <!-- Contenido cargado por AJAX -->
            </div>
        </div>
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

<script>
$(document).ready(function() {
    // Cambiar icono cuando se colapsa/expande
    $('#filtrosCollapse').on('show.bs.collapse', function () {
        $('#filtrosIcon').removeClass('fa-chevron-down').addClass('fa-chevron-up');
    });
    
    $('#filtrosCollapse').on('hide.bs.collapse', function () {
        $('#filtrosIcon').removeClass('fa-chevron-up').addClass('fa-chevron-down');
    });
});
</script>


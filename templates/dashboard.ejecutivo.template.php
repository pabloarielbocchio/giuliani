<?php
// Verificar si hay error
if (isset($registros['error'])) {
    echo '<div class="alert alert-danger">Error: ' . htmlspecialchars($registros['error']) . '</div>';
    return;
}

$kpis = isset($registros['kpis']) ? $registros['kpis'] : array();
$actividad = isset($registros['actividad']) ? $registros['actividad'] : array();
$top_uploads = isset($registros['top_uploads']) ? $registros['top_uploads'] : array();
$top_downloads = isset($registros['top_downloads']) ? $registros['top_downloads'] : array();
$estados_ot = isset($registros['estados_ot']) ? $registros['estados_ot'] : array();
$proyectos_activos = isset($registros['proyectos_activos']) ? $registros['proyectos_activos'] : array();
$alertas = isset($registros['alertas']) ? $registros['alertas'] : array();
?>


<!-- KPIs -->
<div class="row">
    <!-- Archivos Subidos -->
    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-upload fa-4x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?php echo isset($kpis['archivos_subidos']) ? number_format($kpis['archivos_subidos']) : 0; ?></div>
                        <div>Archivos Subidos</div>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <?php 
                $variacion = isset($kpis['variacion_uploads']) ? floatval($kpis['variacion_uploads']) : 0;
                $color = $variacion >= 0 ? 'text-success' : 'text-danger';
                $icono = $variacion >= 0 ? 'fa-arrow-up' : 'fa-arrow-down';
                ?>
                <span class="pull-left">Variación</span>
                <span class="pull-right <?php echo $color; ?>">
                    <i class="fa <?php echo $icono; ?>"></i> <?php echo number_format(abs($variacion), 1); ?>%
                </span>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    
    <!-- Descargas -->
    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
        <div class="panel panel-green">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-download fa-4x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?php echo isset($kpis['descargas']) ? number_format($kpis['descargas']) : 0; ?></div>
                        <div>Total Descargas</div>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <?php 
                $variacion = isset($kpis['variacion_downloads']) ? floatval($kpis['variacion_downloads']) : 0;
                $color = $variacion >= 0 ? 'text-success' : 'text-danger';
                $icono = $variacion >= 0 ? 'fa-arrow-up' : 'fa-arrow-down';
                ?>
                <span class="pull-left">Variación</span>
                <span class="pull-right <?php echo $color; ?>">
                    <i class="fa <?php echo $icono; ?>"></i> <?php echo number_format(abs($variacion), 1); ?>%
                </span>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    
    <!-- Total OT -->
    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
        <div class="panel panel-yellow">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-list fa-4x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?php echo isset($kpis['total_ot']) ? number_format($kpis['total_ot']) : 0; ?></div>
                        <div>Total OT</div>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <?php 
                $variacion = isset($kpis['variacion_ot']) ? floatval($kpis['variacion_ot']) : 0;
                $color = $variacion >= 0 ? 'text-success' : 'text-danger';
                $icono = $variacion >= 0 ? 'fa-arrow-up' : 'fa-arrow-down';
                ?>
                <span class="pull-left">Variación</span>
                <span class="pull-right <?php echo $color; ?>">
                    <i class="fa <?php echo $icono; ?>"></i> <?php echo number_format(abs($variacion), 1); ?>%
                </span>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    
    <!-- Usuarios Activos/Inactivos -->
    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
        <div class="panel panel-red">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-users fa-4x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?php echo isset($kpis['usuarios_activos']) ? number_format($kpis['usuarios_activos']) : 0; ?></div>
                        <div>Usuarios Activos</div>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <span class="pull-left">Inactivos: <?php echo isset($kpis['usuarios_inactivos']) ? number_format($kpis['usuarios_inactivos']) : 0; ?></span>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>

<!-- Gráfico de Actividad -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-line-chart"></i> Actividad General – Subidas y Descargas</h4>
            </div>
            <div class="panel-body">
                <div id="chart-actividad" class="ct-chart"></div>
            </div>
        </div>
    </div>
</div>

<!-- Top Usuarios -->
<div class="row">
    <!-- Top 5 Usuarios que Suben -->
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-user"></i> Top 5 Usuarios que Suben</h4>
            </div>
            <div class="panel-body">
                <?php if (!empty($top_uploads)) { ?>
                    <?php 
                    $max = max(array_column($top_uploads, 'cantidad'));
                    foreach ($top_uploads as $user) { 
                        $porcentaje = $max > 0 ? ($user['cantidad'] / $max) * 100 : 0;
                    ?>
                    <div style="margin-bottom: 15px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                            <span><strong><?php echo htmlspecialchars($user['usuario']); ?></strong></span>
                            <span><?php echo number_format($user['cantidad']); ?></span>
                        </div>
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar progress-bar-info" role="progressbar" 
                                 style="width: <?php echo $porcentaje; ?>%; line-height: 25px;">
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                <?php } else { ?>
                    <p class="text-muted">No hay datos disponibles</p>
                <?php } ?>
            </div>
        </div>
    </div>
    
    <!-- Top 5 Usuarios que Descargan -->
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-download"></i> Top 5 Usuarios que Descargan</h4>
            </div>
            <div class="panel-body">
                <?php if (!empty($top_downloads)) { ?>
                    <?php 
                    $max = max(array_column($top_downloads, 'cantidad'));
                    foreach ($top_downloads as $user) { 
                        $porcentaje = $max > 0 ? ($user['cantidad'] / $max) * 100 : 0;
                    ?>
                    <div style="margin-bottom: 15px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                            <span><strong><?php echo htmlspecialchars($user['usuario']); ?></strong></span>
                            <span><?php echo number_format($user['cantidad']); ?></span>
                        </div>
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar progress-bar-success" role="progressbar" 
                                 style="width: <?php echo $porcentaje; ?>%; line-height: 25px;">
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                <?php } else { ?>
                    <p class="text-muted">No hay datos disponibles</p>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<!-- Estados OT y Proyectos Activos -->
<div class="row">
    <!-- Estados OT -->
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-cog"></i> OT – Estados Principales</h4>
            </div>
            <div class="panel-body">
                <?php if (!empty($estados_ot)) { ?>
                    <div id="chart-estados" class="ct-chart"></div>
                    <table class="table table-striped" style="margin-top: 20px;">
                        <?php foreach ($estados_ot as $estado) { ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($estado['estado']); ?></strong></td>
                            <td class="text-right"><?php echo number_format($estado['cantidad']); ?></td>
                        </tr>
                        <?php } ?>
                    </table>
                <?php } else { ?>
                    <p class="text-muted">No hay datos disponibles</p>
                <?php } ?>
            </div>
        </div>
    </div>
    
    <!-- Proyectos más Activos -->
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-folder"></i> Proyectos con más Actividad</h4>
            </div>
            <div class="panel-body">
                <?php if (!empty($proyectos_activos)) { ?>
                    <?php 
                    $max_actividad = 0;
                    foreach ($proyectos_activos as $proj) {
                        $actividad_total = intval($proj['uploads']) + intval($proj['downloads']);
                        if ($actividad_total > $max_actividad) $max_actividad = $actividad_total;
                    }
                    foreach ($proyectos_activos as $proj) { 
                        $actividad_total = intval($proj['uploads']) + intval($proj['downloads']);
                        $porcentaje = $max_actividad > 0 ? ($actividad_total / $max_actividad) * 100 : 0;
                    ?>
                    <div style="margin-bottom: 15px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                            <span><strong><?php echo htmlspecialchars($proj['cliente'] ? $proj['cliente'] : 'OT #' . $proj['nro_serie']); ?></strong></span>
                            <span><?php echo number_format($actividad_total); ?> (<?php echo $proj['uploads']; ?>↑ / <?php echo $proj['downloads']; ?>↓)</span>
                        </div>
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar progress-bar-warning" role="progressbar" 
                                 style="width: <?php echo $porcentaje; ?>%; line-height: 25px;">
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                <?php } else { ?>
                    <p class="text-muted">No hay datos disponibles</p>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<!-- Alertas Ejecutivas -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-bell"></i> Alertas Ejecutivas</h4>
            </div>
            <div class="panel-body">
                <div class="row">
                    <!-- OT Atrasadas -->
                    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                        <div class="alert alert-danger">
                            <h4><i class="fa fa-exclamation-triangle"></i> OT Atrasadas</h4>
                            <p style="font-size: 24px; font-weight: bold;">
                                <?php echo isset($alertas['ot_atrasadas']) ? number_format($alertas['ot_atrasadas']) : 0; ?>
                            </p>
                        </div>
                    </div>
                    
                    <!-- Usuarios sin Actividad -->
                    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                        <div class="alert alert-warning">
                            <h4><i class="fa fa-user-times"></i> Usuarios Inactivos</h4>
                            <p style="font-size: 24px; font-weight: bold;">
                                <?php echo isset($alertas['usuarios_sin_actividad']) ? count($alertas['usuarios_sin_actividad']) : 0; ?>
                            </p>
                            <?php if (!empty($alertas['usuarios_sin_actividad']) && count($alertas['usuarios_sin_actividad']) > 0) { ?>
                                <small>
                                    <?php 
                                    $usuarios_list = array_slice($alertas['usuarios_sin_actividad'], 0, 3);
                                    foreach ($usuarios_list as $u) {
                                        echo htmlspecialchars($u['usuario']) . '<br>';
                                    }
                                    if (count($alertas['usuarios_sin_actividad']) > 3) {
                                        echo '... y ' . (count($alertas['usuarios_sin_actividad']) - 3) . ' más';
                                    }
                                    ?>
                                </small>
                            <?php } ?>
                        </div>
                    </div>
                    
                    <!-- Proyectos sin Movimientos -->
                    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                        <div class="alert alert-info">
                            <h4><i class="fa fa-folder-open"></i> Proyectos Sin Movimiento</h4>
                            <p style="font-size: 24px; font-weight: bold;">
                                <?php echo isset($alertas['proyectos_sin_mov']) ? count($alertas['proyectos_sin_mov']) : 0; ?>
                            </p>
                        </div>
                    </div>
                    
                    <!-- Resumen de Actividad -->
                    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                        <div class="alert alert-success">
                            <h4><i class="fa fa-check-circle"></i> Estado General</h4>
                            <p style="font-size: 18px;">
                                <?php 
                                $total_alertas = (isset($alertas['ot_atrasadas']) ? $alertas['ot_atrasadas'] : 0) + 
                                                (isset($alertas['usuarios_sin_actividad']) ? count($alertas['usuarios_sin_actividad']) : 0) + 
                                                (isset($alertas['proyectos_sin_mov']) ? count($alertas['proyectos_sin_mov']) : 0);
                                if ($total_alertas == 0) {
                                    echo '<strong>Todo OK</strong>';
                                } else {
                                    echo '<strong>' . $total_alertas . ' alertas</strong>';
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.panel-primary { border-color: #1ab394; }
.panel-primary > .panel-heading { background-color: #1ab394; border-color: #1ab394; color: white; }
.panel-green { border-color: #1c84c6; }
.panel-green > .panel-heading { background-color: #1c84c6; border-color: #1c84c6; color: white; }
.panel-yellow { border-color: #f8ac59; }
.panel-yellow > .panel-heading { background-color: #f8ac59; border-color: #f8ac59; color: white; }
.panel-red { border-color: #ed5565; }
.panel-red > .panel-heading { background-color: #ed5565; border-color: #ed5565; color: white; }
.huge { font-size: 40px; font-weight: bold; }
.ct-chart { height: 300px; }
</style>

<script>
$(document).ready(function() {
    // Gráfico de Actividad
    <?php 
    // Preparar datos para el gráfico
    $labels = array();
    $uploads_data = array();
    $downloads_data = array();
    
    // Obtener fechas desde los datos recibidos
    $fecha_desde_grafico = isset($_POST['fecha_desde']) ? $_POST['fecha_desde'] : date('Y-m-d', strtotime('-30 days'));
    $fecha_hasta_grafico = isset($_POST['fecha_hasta']) ? $_POST['fecha_hasta'] : date('Y-m-d');
    
    // Crear array de fechas
    $fecha_actual = new DateTime($fecha_desde_grafico);
    $fecha_fin = new DateTime($fecha_hasta_grafico);
    $fecha_fin->modify('+1 day');
    $interval = new DateInterval('P1D');
    $period = new DatePeriod($fecha_actual, $interval, $fecha_fin);
    
    $uploads_map = array();
    if (isset($actividad['uploads'])) {
        foreach ($actividad['uploads'] as $item) {
            $uploads_map[$item['fecha']] = intval($item['uploads']);
        }
    }
    
    $downloads_map = array();
    if (isset($actividad['downloads'])) {
        foreach ($actividad['downloads'] as $item) {
            $downloads_map[$item['fecha']] = intval($item['downloads']);
        }
    }
    
    foreach ($period as $date) {
        $fecha_str = $date->format('Y-m-d');
        $labels[] = $date->format('d/m');
        $uploads_data[] = isset($uploads_map[$fecha_str]) ? $uploads_map[$fecha_str] : 0;
        $downloads_data[] = isset($downloads_map[$fecha_str]) ? $downloads_map[$fecha_str] : 0;
    }
    ?>
    
    // Gráfico de líneas con Chartist
    if (typeof Chartist !== 'undefined') {
        new Chartist.Line('#chart-actividad', {
            labels: <?php echo json_encode($labels); ?>,
            series: [
                <?php echo json_encode($uploads_data); ?>,
                <?php echo json_encode($downloads_data); ?>
            ]
        }, {
            fullWidth: true,
            chartPadding: {
                right: 40
            },
            lineSmooth: Chartist.Interpolation.cardinal({
                tension: 0.5
            }),
            axisX: {
                showGrid: false
            },
            axisY: {
                onlyInteger: true
            }
        });
        
        // Gráfico de Estados OT (Pie/Donut)
        <?php if (!empty($estados_ot)) { 
            $estados_labels = array();
            $estados_data = array();
            foreach ($estados_ot as $estado) {
                $estados_labels[] = $estado['estado'];
                $estados_data[] = intval($estado['cantidad']);
            }
        ?>
        new Chartist.Pie('#chart-estados', {
            labels: <?php echo json_encode($estados_labels); ?>,
            series: <?php echo json_encode($estados_data); ?>
        }, {
            donut: true,
            donutWidth: 60,
            donutSolid: true,
            startAngle: 270,
            showLabel: true
        });
        <?php } ?>
    } else {
        // Fallback si Chartist no está disponible - usar gráficos simples con CSS
        console.log('Chartist no está disponible');
    }
});
</script>


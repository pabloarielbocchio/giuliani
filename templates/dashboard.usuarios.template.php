<?php
// Verificar si hay error
if (isset($registros['error'])) {
    echo '<div class="alert alert-danger">Error: ' . htmlspecialchars($registros['error']) . '</div>';
    return;
}

$kpis = isset($registros['kpis']) ? $registros['kpis'] : array();
$evolucion = isset($registros['evolucion']) ? $registros['evolucion'] : array();
$top_uploads = isset($registros['top_uploads']) ? $registros['top_uploads'] : array();
$top_downloads = isset($registros['top_downloads']) ? $registros['top_downloads'] : array();
$ultima_actividad = isset($registros['ultima_actividad']) ? $registros['ultima_actividad'] : array();
$usuarios_por_rol = isset($registros['usuarios_por_rol']) ? $registros['usuarios_por_rol'] : array();
$usuarios_inactivos_detalle = isset($registros['usuarios_inactivos_detalle']) ? $registros['usuarios_inactivos_detalle'] : array();
$funnel = isset($registros['funnel']) ? $registros['funnel'] : array();
$tabla_detalle = isset($registros['tabla_detalle']) ? $registros['tabla_detalle'] : array();
$granularidad = isset($registros['granularidad']) ? $registros['granularidad'] : 'dia';
?>

<!-- KPIs -->
<div class="row">
    <!-- Usuarios Totales -->
    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-users fa-4x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?php echo isset($kpis['usuarios_totales']) ? number_format($kpis['usuarios_totales']) : 0; ?></div>
                        <div>Usuarios Totales</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Usuarios Activos -->
    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
        <div class="panel panel-green">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-user-circle fa-4x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?php echo isset($kpis['usuarios_activos']) ? number_format($kpis['usuarios_activos']) : 0; ?></div>
                        <div>Usuarios Activos</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Usuarios Inactivos -->
    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
        <div class="panel panel-yellow">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-user-times fa-4x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?php echo isset($kpis['usuarios_inactivos']) ? number_format($kpis['usuarios_inactivos']) : 0; ?></div>
                        <div>Usuarios Inactivos</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Gráfico de Evolución -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-lg-6">
                        <h4><i class="fa fa-line-chart"></i> Actividad de Usuarios en el Tiempo</h4>
                    </div>
                    <div class="col-lg-6 text-right">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-default granularidad-btn <?php echo $granularidad == 'dia' ? 'active' : ''; ?>" data-granularidad="dia">Día</button>
                            <button type="button" class="btn btn-sm btn-default granularidad-btn <?php echo $granularidad == 'semana' ? 'active' : ''; ?>" data-granularidad="semana">Semana</button>
                            <button type="button" class="btn btn-sm btn-default granularidad-btn <?php echo $granularidad == 'mes' ? 'active' : ''; ?>" data-granularidad="mes">Mes</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <div id="chart-evolucion" class="ct-chart"></div>
            </div>
        </div>
    </div>
</div>

<!-- Top Usuarios -->
<div class="row">
    <!-- Top 10 que Más Suben -->
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-upload"></i> Top 10 Usuarios que Más Suben</h4>
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
    
    <!-- Top Usuarios que Más Descargan -->
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-download"></i> Top 10 Usuarios que Más Descargan</h4>
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

<!-- Última Actividad y Usuarios por Rol -->
<div class="row">
    <!-- Última Actividad por Usuario -->
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-clock-o"></i> Última Actividad por Usuario</h4>
            </div>
            <div class="panel-body">
                <div style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Usuario</th>
                                <th>Última Actividad</th>
                                <th>Días</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($ultima_actividad)) { ?>
                                <?php foreach ($ultima_actividad as $item) { 
                                    $dias = $item['ultima_actividad'] != '1900-01-01 00:00:00' ? floor((time() - strtotime($item['ultima_actividad'])) / 86400) : 'N/A';
                                ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($item['usuario']); ?></strong></td>
                                    <td><?php echo $item['ultima_actividad'] != '1900-01-01 00:00:00' ? date('d/m/Y H:i', strtotime($item['ultima_actividad'])) : 'Sin actividad'; ?></td>
                                    <td><?php echo is_numeric($dias) ? $dias . ' días' : $dias; ?></td>
                                </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="3" class="text-center">No hay datos disponibles</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Usuarios por Rol -->
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-user-secret"></i> Usuarios por Rol</h4>
            </div>
            <div class="panel-body">
                <?php if (!empty($usuarios_por_rol)) { ?>
                    <div id="chart-roles" class="ct-chart" style="height: 300px;"></div>
                    <table class="table table-striped" style="margin-top: 20px;">
                        <thead>
                            <tr>
                                <th>Rol</th>
                                <th class="text-right">Cantidad</th>
                                <th class="text-right">%</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total_roles = array_sum(array_column($usuarios_por_rol, 'cantidad'));
                            foreach ($usuarios_por_rol as $rol) { 
                                $porcentaje = $total_roles > 0 ? ($rol['cantidad'] / $total_roles) * 100 : 0;
                            ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($rol['rol']); ?></strong></td>
                                <td class="text-right"><?php echo number_format($rol['cantidad']); ?></td>
                                <td class="text-right"><?php echo number_format($porcentaje, 1); ?>%</td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } else { ?>
                    <p class="text-muted">No hay datos disponibles</p>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<!-- Usuarios Inactivos -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-user-times"></i> Usuarios Inactivos</h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <input type="text" class="form-control" id="search-inactivos" placeholder="Buscar en la tabla...">
                </div>
                <div style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-striped table-hover" id="tabla-inactivos">
                        <thead>
                            <tr>
                                <th>Usuario</th>
                                <th>Última Actividad</th>
                                <th>Días sin Actividad</th>
                                <th class="text-right">Subidas (último mes)</th>
                                <th class="text-right">Descargas (último mes)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($usuarios_inactivos_detalle)) { ?>
                                <?php foreach ($usuarios_inactivos_detalle as $item) { ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($item['usuario']); ?></strong></td>
                                    <td><?php echo $item['ultima_actividad'] != '1900-01-01 00:00:00' ? date('d/m/Y H:i', strtotime($item['ultima_actividad'])) : 'Sin actividad'; ?></td>
                                    <td><span class="label label-warning"><?php echo number_format($item['dias_sin_actividad']); ?> días</span></td>
                                    <td class="text-right"><?php echo number_format($item['uploads_ultimo_mes']); ?></td>
                                    <td class="text-right"><?php echo number_format($item['downloads_ultimo_mes']); ?></td>
                                </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="5" class="text-center">No hay usuarios inactivos</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Perfil de Comportamiento (Funnel) -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-bar-chart"></i> Perfil de Comportamiento del Usuario</h4>
            </div>
            <div class="panel-body">
                <div style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Usuario</th>
                                <th class="text-right">Subidas</th>
                                <th class="text-right">Descargas</th>
                                <th class="text-right">OT Gestionadas</th>
                                <th class="text-right">OT Cerradas</th>
                                <th class="text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($funnel)) { ?>
                                <?php foreach ($funnel as $item) { 
                                    $total = intval($item['subidas']) + intval($item['descargas']) + intval($item['ot_gestionadas']) + intval($item['ot_cerradas']);
                                ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($item['usuario']); ?></strong></td>
                                    <td class="text-right"><?php echo number_format($item['subidas']); ?></td>
                                    <td class="text-right"><?php echo number_format($item['descargas']); ?></td>
                                    <td class="text-right"><?php echo number_format($item['ot_gestionadas']); ?></td>
                                    <td class="text-right"><?php echo number_format($item['ot_cerradas']); ?></td>
                                    <td class="text-right"><strong><?php echo number_format($total); ?></strong></td>
                                </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="6" class="text-center">No hay datos disponibles</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Registro Detallado -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-list"></i> Registro Detallado por Usuario</h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <input type="text" class="form-control" id="search-detalle" placeholder="Buscar en la tabla...">
                </div>
                <div style="max-height: 500px; overflow-y: auto;">
                    <table class="table table-striped table-hover" id="tabla-detalle">
                        <thead>
                            <tr>
                                <th>Fecha / Hora</th>
                                <th>Usuario</th>
                                <th>Tipo</th>
                                <th>Proyecto</th>
                                <th>OT</th>
                                <th>Archivo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($tabla_detalle)) { ?>
                                <?php foreach ($tabla_detalle as $item) { ?>
                                <tr>
                                    <td><?php echo date('d/m/Y H:i', strtotime($item['fecha_hora'])); ?></td>
                                    <td><?php echo htmlspecialchars($item['usuario']); ?></td>
                                    <td><span class="label label-<?php echo $item['tipo_accion'] == 'Subida' ? 'info' : 'success'; ?>"><?php echo htmlspecialchars($item['tipo_accion']); ?></span></td>
                                    <td><?php echo htmlspecialchars($item['cliente']); ?></td>
                                    <td><?php echo htmlspecialchars($item['nro_serie']); ?></td>
                                    <td><?php echo htmlspecialchars($item['archivo']); ?></td>
                                </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="6" class="text-center">No hay datos disponibles</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
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
.huge { font-size: 36px; font-weight: bold; }
.ct-chart { height: 300px; }
.granularidad-btn.active { background-color: #1ab394; color: white; }
</style>

<script>
$(document).ready(function() {
    // Gráfico de Evolución
    <?php if (!empty($evolucion)) { 
        $labels = array();
        $data = array();
        foreach ($evolucion as $item) {
            $labels[] = $item['fecha'];
            $data[] = intval($item['cantidad']);
        }
    ?>
    setTimeout(function() {
        if (typeof Chartist !== 'undefined' && $('#chart-evolucion').length) {
            new Chartist.Line('#chart-evolucion', {
                labels: <?php echo json_encode($labels); ?>,
                series: [<?php echo json_encode($data); ?>]
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
        }
    }, 100);
    <?php } ?>
    
    // Gráfico de Roles (Pie)
    <?php if (!empty($usuarios_por_rol)) { 
        $roles_labels = array();
        $roles_data = array();
        foreach ($usuarios_por_rol as $rol) {
            $roles_labels[] = $rol['rol'];
            $roles_data[] = intval($rol['cantidad']);
        }
    ?>
    setTimeout(function() {
        if (typeof Chartist !== 'undefined' && $('#chart-roles').length) {
            new Chartist.Pie('#chart-roles', {
                labels: <?php echo json_encode($roles_labels); ?>,
                series: <?php echo json_encode($roles_data); ?>
            }, {
                donut: true,
                donutWidth: 60,
                showLabel: true
            });
        }
    }, 100);
    <?php } ?>
    
    // Búsqueda en tablas
    $('#search-inactivos').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('#tabla-inactivos tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });
    
    $('#search-detalle').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('#tabla-detalle tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });
    
    // Cambio de granularidad
    $('.granularidad-btn').click(function() {
        $('.granularidad-btn').removeClass('active');
        $(this).addClass('active');
        var granularidad = $(this).data('granularidad');
        if (typeof loadUsuarios === 'function') {
            loadUsuarios(granularidad);
        } else {
            var parametros = {
                funcion: 'getDashboardUsuarios',
                fecha_desde: $("#fecha_desde").val(),
                fecha_hasta: $("#fecha_hasta").val(),
                granularidad: granularidad || 'dia'
            };
            
            $.ajax({
                type: "POST",
                url: 'controller/dashboard.controller.php',
                data: parametros,
                success: function (datos) {
                    $("#content-usuarios").html(datos);
                },
                error: function () {
                    $("#content-usuarios").html('<div class="alert alert-danger">Error al cargar los datos</div>');
                }
            });
        }
    });
});
</script>


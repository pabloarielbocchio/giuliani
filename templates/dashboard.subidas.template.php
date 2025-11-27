<?php
// Verificar si hay error
if (isset($registros['error'])) {
    echo '<div class="alert alert-danger">Error: ' . htmlspecialchars($registros['error']) . '</div>';
    return;
}

$kpis = isset($registros['kpis']) ? $registros['kpis'] : array();
$evolucion = isset($registros['evolucion']) ? $registros['evolucion'] : array();
$top_usuarios = isset($registros['top_usuarios']) ? $registros['top_usuarios'] : array();
$heatmap = isset($registros['heatmap']) ? $registros['heatmap'] : array();
$subidas_proyectos = isset($registros['subidas_proyectos']) ? $registros['subidas_proyectos'] : array();
$subidas_tipos = isset($registros['subidas_tipos']) ? $registros['subidas_tipos'] : array();
$tabla_detalle = isset($registros['tabla_detalle']) ? $registros['tabla_detalle'] : array();
$granularidad = isset($registros['granularidad']) ? $registros['granularidad'] : 'dia';
?>


<!-- KPIs -->
<div class="row">
    <!-- Total Subidas -->
    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-upload fa-4x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?php echo isset($kpis['total_subidas']) ? number_format($kpis['total_subidas']) : 0; ?></div>
                        <div>Total Subidas</div>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <?php 
                $variacion = isset($kpis['variacion_total']) ? floatval($kpis['variacion_total']) : 0;
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
    
    <!-- Promedio por Día -->
    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
        <div class="panel panel-green">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-calendar fa-4x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?php echo isset($kpis['promedio_dia']) ? number_format($kpis['promedio_dia'], 1) : 0; ?></div>
                        <div>Subidas / Día (prom)</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Usuarios que Subieron -->
    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
        <div class="panel panel-yellow">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-users fa-4x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?php echo isset($kpis['usuarios_activos']) ? number_format($kpis['usuarios_activos']) : 0; ?></div>
                        <div>Usuarios que Subieron</div>
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
                        <h4><i class="fa fa-line-chart"></i> Evolución de Subidas</h4>
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

<!-- Top Usuarios y Heatmap -->
<div class="row">
    <!-- Top 10 Usuarios -->
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-user"></i> Top 10 Usuarios que Más Suben</h4>
            </div>
            <div class="panel-body">
                <?php if (!empty($top_usuarios)) { ?>
                    <?php 
                    $max = max(array_column($top_usuarios, 'cantidad'));
                    foreach ($top_usuarios as $user) { 
                        $porcentaje = $max > 0 ? ($user['cantidad'] / $max) * 100 : 0;
                        $porc_total = isset($kpis['total_subidas']) && $kpis['total_subidas'] > 0 ? ($user['cantidad'] / $kpis['total_subidas']) * 100 : 0;
                    ?>
                    <div style="margin-bottom: 15px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                            <span><strong><?php echo htmlspecialchars($user['usuario']); ?></strong></span>
                            <span><?php echo number_format($user['cantidad']); ?> (<?php echo number_format($porc_total, 1); ?>%)</span>
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
    
    <!-- Heatmap Horas Pico -->
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-clock-o"></i> Horas Pico de Subida</h4>
            </div>
            <div class="panel-body">
                <div id="heatmap-container" style="overflow-x: auto;">
                    <table class="table table-bordered" style="font-size: 11px;">
                        <thead>
                            <tr>
                                <th>Hora</th>
                                <th>Dom</th>
                                <th>Lun</th>
                                <th>Mar</th>
                                <th>Mié</th>
                                <th>Jue</th>
                                <th>Vie</th>
                                <th>Sáb</th>
                            </tr>
                        </thead>
                        <tbody id="heatmap-body">
                            <!-- Se llenará con JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Subidas por Proyecto y Tipo -->
<div class="row">
    <!-- Subidas por Proyecto/OT -->
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-folder"></i> Subidas por Proyecto / OT</h4>
            </div>
            <div class="panel-body">
                <div style="max-height: 400px; overflow-y: auto;">
                    <?php if (!empty($subidas_proyectos)) { ?>
                        <?php 
                        $max_proyecto = max(array_column($subidas_proyectos, 'cantidad'));
                        foreach ($subidas_proyectos as $proj) { 
                            $porcentaje = $max_proyecto > 0 ? ($proj['cantidad'] / $max_proyecto) * 100 : 0;
                        ?>
                        <div style="margin-bottom: 15px;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                <span><strong><?php echo htmlspecialchars($proj['cliente'] ? $proj['cliente'] : 'OT #' . $proj['nro_serie']); ?></strong></span>
                                <span><?php echo number_format($proj['cantidad']); ?></span>
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
    
    <!-- Subidas por Tipo de Archivo -->
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-file"></i> Subidas por Tipo de Archivo</h4>
            </div>
            <div class="panel-body">
                <?php if (!empty($subidas_tipos)) { ?>
                    <div id="chart-tipos" class="ct-chart" style="height: 300px;"></div>
                    <table class="table table-striped" style="margin-top: 20px;">
                        <thead>
                            <tr>
                                <th>Tipo</th>
                                <th class="text-right">Cantidad</th>
                                <th class="text-right">%</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total_tipos = array_sum(array_column($subidas_tipos, 'cantidad'));
                            foreach ($subidas_tipos as $tipo) { 
                                $porcentaje = $total_tipos > 0 ? ($tipo['cantidad'] / $total_tipos) * 100 : 0;
                            ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($tipo['tipo']); ?></strong></td>
                                <td class="text-right"><?php echo number_format($tipo['cantidad']); ?></td>
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

<!-- Tabla Detallada -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-list"></i> Registro Detallado de Subidas</h4>
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
                                <th>Proyecto</th>
                                <th>OT</th>
                                <th>Tipo</th>
                                <th>Nombre Archivo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($tabla_detalle)) { ?>
                                <?php foreach ($tabla_detalle as $item) { ?>
                                <tr>
                                    <td><?php echo date('d/m/Y H:i', strtotime($item['fecha_hora'])); ?></td>
                                    <td><?php echo htmlspecialchars($item['usuario']); ?></td>
                                    <td><?php echo htmlspecialchars($item['cliente'] ? $item['cliente'] : '-'); ?></td>
                                    <td><?php echo htmlspecialchars($item['nro_serie'] ? $item['nro_serie'] : '-'); ?></td>
                                    <td><span class="label label-info"><?php echo htmlspecialchars($item['tipo_archivo']); ?></span></td>
                                    <td><?php echo htmlspecialchars($item['nombre_archivo']); ?></td>
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
    if (typeof Chartist !== 'undefined') {
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
    <?php } ?>
    
    // Gráfico de Tipos (Pie)
    <?php if (!empty($subidas_tipos)) { 
        $tipos_labels = array();
        $tipos_data = array();
        foreach ($subidas_tipos as $tipo) {
            $tipos_labels[] = $tipo['tipo'];
            $tipos_data[] = intval($tipo['cantidad']);
        }
    ?>
    if (typeof Chartist !== 'undefined') {
        new Chartist.Pie('#chart-tipos', {
            labels: <?php echo json_encode($tipos_labels); ?>,
            series: <?php echo json_encode($tipos_data); ?>
        }, {
            donut: true,
            donutWidth: 60,
            showLabel: true
        });
    }
    <?php } ?>
    
    // Heatmap
    <?php 
    $heatmap_data = array();
    foreach ($heatmap as $item) {
        $heatmap_data[$item['hora']][$item['dia_semana']] = intval($item['cantidad']);
    }
    $max_heatmap = 0;
    foreach ($heatmap_data as $hora => $dias) {
        foreach ($dias as $dia => $cant) {
            if ($cant > $max_heatmap) $max_heatmap = $cant;
        }
    }
    ?>
    var heatmapData = <?php echo json_encode($heatmap_data); ?>;
    var maxHeatmap = <?php echo $max_heatmap; ?>;
    var diasSemana = ['', 'Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
    var tbody = $('#heatmap-body');
    
    for (var h = 0; h < 24; h++) {
        var row = $('<tr></tr>');
        row.append('<td><strong>' + h + ':00</strong></td>');
        for (var d = 1; d <= 7; d++) {
            var cantidad = heatmapData[h] && heatmapData[h][d] ? heatmapData[h][d] : 0;
            var intensidad = maxHeatmap > 0 ? (cantidad / maxHeatmap) * 100 : 0;
            var color = 'rgba(26, 179, 148, ' + (intensidad / 100) + ')';
            row.append('<td style="background-color: ' + color + '; text-align: center; min-width: 50px;">' + cantidad + '</td>');
        }
        tbody.append(row);
    }
    
    // Búsqueda en tabla
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
        if (typeof loadSubidas === 'function') {
            loadSubidas(granularidad);
        } else {
            // Si la función no está disponible, recargar la página del tab
            var parametros = {
                funcion: 'getActividadSubidas',
                fecha_desde: $("#fecha_desde").val(),
                fecha_hasta: $("#fecha_hasta").val(),
                usuario: '0',
                proyecto: 0,
                tipo_archivo: '',
                granularidad: granularidad || 'dia'
            };
            
            $.ajax({
                type: "POST",
                url: 'controller/dashboard.controller.php',
                data: parametros,
                success: function (datos) {
                    $("#content-subidas").html(datos);
                },
                error: function () {
                    $("#content-subidas").html('<div class="alert alert-danger">Error al cargar los datos</div>');
                }
            });
        }
    });
});
</script>


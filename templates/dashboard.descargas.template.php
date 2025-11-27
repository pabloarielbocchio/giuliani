<?php
// Verificar si hay error
if (isset($registros['error'])) {
    echo '<div class="alert alert-danger">Error: ' . htmlspecialchars($registros['error']) . '</div>';
    return;
}

$kpis = isset($registros['kpis']) ? $registros['kpis'] : array();
$evolucion = isset($registros['evolucion']) ? $registros['evolucion'] : array();
$top_usuarios = isset($registros['top_usuarios']) ? $registros['top_usuarios'] : array();
$top_archivos = isset($registros['top_archivos']) ? $registros['top_archivos'] : array();
$descargas_proyectos = isset($registros['descargas_proyectos']) ? $registros['descargas_proyectos'] : array();
$heatmap = isset($registros['heatmap']) ? $registros['heatmap'] : array();
$tabla_detalle = isset($registros['tabla_detalle']) ? $registros['tabla_detalle'] : array();
$granularidad = isset($registros['granularidad']) ? $registros['granularidad'] : 'dia';
?>

<!-- KPIs -->
<div class="row">
    <!-- Total Descargas -->
    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-download fa-4x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?php echo isset($kpis['total_descargas']) ? number_format($kpis['total_descargas']) : 0; ?></div>
                        <div>Total Descargas</div>
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
                        <div>Descargas / Día (prom)</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Usuarios que Descargaron -->
    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
        <div class="panel panel-yellow">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-users fa-4x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?php echo isset($kpis['usuarios_activos']) ? number_format($kpis['usuarios_activos']) : 0; ?></div>
                        <div>Usuarios que Descargaron</div>
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
                        <h4><i class="fa fa-line-chart"></i> Evolución de Descargas</h4>
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

<!-- Top Usuarios y Top Archivos -->
<div class="row">
    <!-- Top 10 Usuarios -->
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-user"></i> Top 10 Usuarios que Más Descargan</h4>
            </div>
            <div class="panel-body">
                <?php if (!empty($top_usuarios)) { ?>
                    <?php 
                    $max = max(array_column($top_usuarios, 'cantidad'));
                    foreach ($top_usuarios as $user) { 
                        $porcentaje = $max > 0 ? ($user['cantidad'] / $max) * 100 : 0;
                        $porc_total = isset($kpis['total_descargas']) && $kpis['total_descargas'] > 0 ? ($user['cantidad'] / $kpis['total_descargas']) * 100 : 0;
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
    
    <!-- Top 10 Archivos -->
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-file"></i> Top 10 Archivos Más Descargados</h4>
            </div>
            <div class="panel-body">
                <div style="max-height: 400px; overflow-y: auto;">
                    <?php if (!empty($top_archivos)) { ?>
                        <?php 
                        $max_archivo = max(array_column($top_archivos, 'cantidad'));
                        foreach ($top_archivos as $archivo) { 
                            $porcentaje = $max_archivo > 0 ? ($archivo['cantidad'] / $max_archivo) * 100 : 0;
                        ?>
                        <div style="margin-bottom: 15px;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                <span><strong><?php echo htmlspecialchars($archivo['nombre_archivo']); ?></strong></span>
                                <span><?php echo number_format($archivo['cantidad']); ?></span>
                            </div>
                            <div style="font-size: 11px; color: #666; margin-bottom: 5px;">
                                <?php echo htmlspecialchars($archivo['cliente']); ?> - OT: <?php echo htmlspecialchars($archivo['nro_serie']); ?>
                                <span class="label label-info" style="margin-left: 5px;"><?php echo htmlspecialchars($archivo['tipo_archivo']); ?></span>
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
</div>

<!-- Descargas por Proyecto y Heatmap -->
<div class="row">
    <!-- Descargas por Proyecto/OT -->
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-folder"></i> Descargas por Proyecto / OT</h4>
            </div>
            <div class="panel-body">
                <div style="max-height: 400px; overflow-y: auto;">
                    <?php if (!empty($descargas_proyectos)) { ?>
                        <?php 
                        $max_proyecto = max(array_column($descargas_proyectos, 'cantidad'));
                        foreach ($descargas_proyectos as $proj) { 
                            $porcentaje = $max_proyecto > 0 ? ($proj['cantidad'] / $max_proyecto) * 100 : 0;
                        ?>
                        <div style="margin-bottom: 15px;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                <span><strong><?php echo htmlspecialchars($proj['cliente'] ? $proj['cliente'] : 'OT #' . $proj['nro_serie']); ?></strong></span>
                                <span><?php echo number_format($proj['cantidad']); ?></span>
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
    
    <!-- Heatmap Horarios -->
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-clock-o"></i> Horarios de Descarga</h4>
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

<!-- Tabla Detallada -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-list"></i> Registro Detallado de Descargas</h4>
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
                                <th>Archivo</th>
                                <th>Tipo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($tabla_detalle)) { ?>
                                <?php foreach ($tabla_detalle as $item) { ?>
                                <tr>
                                    <td><?php echo date('d/m/Y H:i', strtotime($item['fecha_m'])); ?></td>
                                    <td><?php echo htmlspecialchars($item['usuario']); ?></td>
                                    <td><?php echo htmlspecialchars($item['cliente']); ?></td>
                                    <td><?php echo htmlspecialchars($item['nro_serie']); ?></td>
                                    <td><?php echo htmlspecialchars($item['nombre_archivo'] ? $item['nombre_archivo'] : '-'); ?></td>
                                    <td><span class="label label-info"><?php echo htmlspecialchars($item['tipo_archivo'] ? $item['tipo_archivo'] : '-'); ?></span></td>
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
    <?php } else { ?>
    console.log('No hay datos de evolución para mostrar');
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
        if (typeof loadDescargas === 'function') {
            loadDescargas(granularidad);
        } else {
            // Si la función no está disponible, recargar la página del tab
            var parametros = {
                funcion: 'getActividadDescargas',
                fecha_desde: $("#fecha_desde").val(),
                fecha_hasta: $("#fecha_hasta").val(),
                granularidad: granularidad || 'dia'
            };
            
            $.ajax({
                type: "POST",
                url: 'controller/dashboard.controller.php',
                data: parametros,
                success: function (datos) {
                    $("#content-descargas").html(datos);
                },
                error: function () {
                    $("#content-descargas").html('<div class="alert alert-danger">Error al cargar los datos</div>');
                }
            });
        }
    });
});
</script>


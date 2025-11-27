<?php
// Verificar si hay error
if (isset($registros['error'])) {
    echo '<div class="alert alert-danger">Error: ' . htmlspecialchars($registros['error']) . '</div>';
    return;
}

$kpis = isset($registros['kpis']) ? $registros['kpis'] : array();
$ot_por_estado = isset($registros['ot_por_estado']) ? $registros['ot_por_estado'] : array();
$evolucion_creadas = isset($registros['evolucion_creadas']) ? $registros['evolucion_creadas'] : array();
$evolucion_cerradas = isset($registros['evolucion_cerradas']) ? $registros['evolucion_cerradas'] : array();
$ot_por_responsable = isset($registros['ot_por_responsable']) ? $registros['ot_por_responsable'] : array();
$ot_por_proyecto = isset($registros['ot_por_proyecto']) ? $registros['ot_por_proyecto'] : array();
$tiempo_promedio = isset($registros['tiempo_promedio']) ? $registros['tiempo_promedio'] : array();
$ot_atrasadas_detalle = isset($registros['ot_atrasadas_detalle']) ? $registros['ot_atrasadas_detalle'] : array();
$registro_completo = isset($registros['registro_completo']) ? $registros['registro_completo'] : array();
$granularidad = isset($registros['granularidad']) ? $registros['granularidad'] : 'dia';
?>

<!-- KPIs -->
<div class="row">
    <!-- Total OT -->
    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-tasks fa-4x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?php echo isset($kpis['total_ot']) ? number_format($kpis['total_ot']) : 0; ?></div>
                        <div>Total OT</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- OT Activas -->
    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
        <div class="panel panel-green">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-play-circle fa-4x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?php echo isset($kpis['ot_activas']) ? number_format($kpis['ot_activas']) : 0; ?></div>
                        <div>OT Activas</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- OT Atrasadas -->
    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
        <div class="panel panel-yellow">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-exclamation-triangle fa-4x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?php echo isset($kpis['ot_atrasadas']) ? number_format($kpis['ot_atrasadas']) : 0; ?></div>
                        <div>OT Atrasadas</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- OT por Estado y Evolución -->
<div class="row">
    <!-- OT por Estado -->
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-pie-chart"></i> OT por Estado</h4>
            </div>
            <div class="panel-body">
                <?php if (!empty($ot_por_estado)) { ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Estado</th>
                                <th class="text-right">Cantidad</th>
                                <th class="text-right">%</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total_estados = array_sum(array_column($ot_por_estado, 'cantidad'));
                            foreach ($ot_por_estado as $estado) { 
                                $porcentaje = $total_estados > 0 ? ($estado['cantidad'] / $total_estados) * 100 : 0;
                            ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($estado['estado']); ?></strong></td>
                                <td class="text-right"><?php echo number_format($estado['cantidad']); ?></td>
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
    
    <!-- Evolución de Creación y Cierre -->
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-lg-6">
                        <h4><i class="fa fa-line-chart"></i> Evolución de Creación y Cierre</h4>
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

<!-- OT por Responsable y Proyecto -->
<div class="row">
    <!-- OT por Responsable -->
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-user"></i> OT por Responsable</h4>
            </div>
            <div class="panel-body">
                <div style="max-height: 400px; overflow-y: auto;">
                    <?php if (!empty($ot_por_responsable)) { ?>
                        <?php 
                        $max_resp = max(array_column($ot_por_responsable, 'cantidad'));
                        foreach ($ot_por_responsable as $resp) { 
                            $porcentaje = $max_resp > 0 ? ($resp['cantidad'] / $max_resp) * 100 : 0;
                        ?>
                        <div style="margin-bottom: 15px;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                <span><strong><?php echo htmlspecialchars($resp['responsable']); ?></strong></span>
                                <span><?php echo number_format($resp['cantidad']); ?></span>
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
    </div>
    
    <!-- OT por Proyecto -->
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-folder"></i> OT por Proyecto</h4>
            </div>
            <div class="panel-body">
                <div style="max-height: 400px; overflow-y: auto;">
                    <?php if (!empty($ot_por_proyecto)) { ?>
                        <?php 
                        $max_proy = max(array_column($ot_por_proyecto, 'cantidad'));
                        foreach ($ot_por_proyecto as $proy) { 
                            $porcentaje = $max_proy > 0 ? ($proy['cantidad'] / $max_proy) * 100 : 0;
                        ?>
                        <div style="margin-bottom: 15px;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                <span><strong><?php echo htmlspecialchars($proy['proyecto']); ?></strong></span>
                                <span><?php echo number_format($proy['cantidad']); ?></span>
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

<!-- Tiempo Promedio de Cierre -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-clock-o"></i> Tiempo Promedio de Cierre de OT</h4>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-4 text-center">
                        <h3 style="margin: 0; color: #1ab394;">
                            <?php echo isset($tiempo_promedio['promedio_dias']) ? number_format($tiempo_promedio['promedio_dias'], 1) : '0'; ?>
                        </h3>
                        <p>Días Promedio</p>
                    </div>
                    <div class="col-lg-4 text-center">
                        <h3 style="margin: 0; color: #1c84c6;">
                            <?php echo isset($tiempo_promedio['minimo_dias']) ? number_format($tiempo_promedio['minimo_dias'], 0) : '0'; ?>
                        </h3>
                        <p>Mínimo (días)</p>
                    </div>
                    <div class="col-lg-4 text-center">
                        <h3 style="margin: 0; color: #f8ac59;">
                            <?php echo isset($tiempo_promedio['maximo_dias']) ? number_format($tiempo_promedio['maximo_dias'], 0) : '0'; ?>
                        </h3>
                        <p>Máximo (días)</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- OT Atrasadas -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-exclamation-triangle"></i> OT Atrasadas</h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <input type="text" class="form-control" id="search-atrasadas" placeholder="Buscar en la tabla...">
                </div>
                <div style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-striped table-hover" id="tabla-atrasadas">
                        <thead>
                            <tr>
                                <th>Número OT</th>
                                <th>Proyecto</th>
                                <th>Responsable</th>
                                <th>Fecha Creación</th>
                                <th>Días Abiertos</th>
                                <th>Días Atrasados</th>
                                <th>Última Actividad</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($ot_atrasadas_detalle)) { ?>
                                <?php foreach ($ot_atrasadas_detalle as $item) { ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($item['nro_serie']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($item['cliente']); ?></td>
                                    <td><?php echo htmlspecialchars($item['responsable']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($item['fecha_creacion'])); ?></td>
                                    <td><span class="label label-info"><?php echo number_format($item['dias_abiertos']); ?> días</span></td>
                                    <td><span class="label label-danger"><?php echo number_format($item['dias_atrasados']); ?> días</span></td>
                                    <td><?php echo $item['ultima_actividad'] != '1900-01-01 00:00:00' ? date('d/m/Y H:i', strtotime($item['ultima_actividad'])) : 'Sin actividad'; ?></td>
                                </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="7" class="text-center">No hay OT atrasadas</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Registro Completo de OT -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-list"></i> Registro Completo de OT</h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <input type="text" class="form-control" id="search-registro" placeholder="Buscar en la tabla...">
                </div>
                <div style="max-height: 500px; overflow-y: auto;">
                    <table class="table table-striped table-hover" id="tabla-registro">
                        <thead>
                            <tr>
                                <th>OT</th>
                                <th>Proyecto</th>
                                <th>Responsable</th>
                                <th>Estado</th>
                                <th>Fecha Creación</th>
                                <th>Fecha Cierre</th>
                                <th>Tiempo Transcurrido</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($registro_completo)) { ?>
                                <?php foreach ($registro_completo as $item) { ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($item['nro_serie']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($item['cliente']); ?></td>
                                    <td><?php echo htmlspecialchars($item['responsable']); ?></td>
                                    <td><span class="label label-default"><?php echo htmlspecialchars($item['estado']); ?></span></td>
                                    <td><?php echo date('d/m/Y', strtotime($item['fecha_creacion'])); ?></td>
                                    <td><?php echo $item['fecha_cierre'] ? date('d/m/Y', strtotime($item['fecha_cierre'])) : '-'; ?></td>
                                    <td><?php echo number_format($item['tiempo_transcurrido']); ?> días</td>
                                </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="7" class="text-center">No hay datos disponibles</td>
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
    
    // Gráfico de Evolución (Líneas múltiples)
    <?php 
    // Combinar fechas de creadas y cerradas
    $fechas_combinadas = array();
    $creadas_data = array();
    $cerradas_data = array();
    
    // Mapear creadas
    $creadas_map = array();
    foreach ($evolucion_creadas as $item) {
        $creadas_map[$item['fecha']] = intval($item['cantidad']);
        if (!in_array($item['fecha'], $fechas_combinadas)) {
            $fechas_combinadas[] = $item['fecha'];
        }
    }
    
    // Mapear cerradas
    $cerradas_map = array();
    foreach ($evolucion_cerradas as $item) {
        $cerradas_map[$item['fecha']] = intval($item['cantidad']);
        if (!in_array($item['fecha'], $fechas_combinadas)) {
            $fechas_combinadas[] = $item['fecha'];
        }
    }
    
    sort($fechas_combinadas);
    
    foreach ($fechas_combinadas as $fecha) {
        $creadas_data[] = isset($creadas_map[$fecha]) ? $creadas_map[$fecha] : 0;
        $cerradas_data[] = isset($cerradas_map[$fecha]) ? $cerradas_map[$fecha] : 0;
    }
    ?>
    setTimeout(function() {
        if (typeof Chartist !== 'undefined' && $('#chart-evolucion').length && <?php echo count($fechas_combinadas); ?> > 0) {
            new Chartist.Line('#chart-evolucion', {
                labels: <?php echo json_encode($fechas_combinadas); ?>,
                series: [
                    <?php echo json_encode($creadas_data); ?>,
                    <?php echo json_encode($cerradas_data); ?>
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
                },
                series: {
                    'series-0': {
                        name: 'Creadas'
                    },
                    'series-1': {
                        name: 'Cerradas'
                    }
                }
            });
        }
    }, 100);
    
    // Búsqueda en tablas
    $('#search-atrasadas').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('#tabla-atrasadas tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });
    
    $('#search-registro').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('#tabla-registro tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });
    
    // Cambio de granularidad
    $('.granularidad-btn').click(function() {
        $('.granularidad-btn').removeClass('active');
        $(this).addClass('active');
        var granularidad = $(this).data('granularidad');
        if (typeof loadOT === 'function') {
            loadOT(granularidad);
        } else {
            var parametros = {
                funcion: 'getOrdenesTrabajo',
                fecha_desde: $("#fecha_desde").val(),
                fecha_hasta: $("#fecha_hasta").val(),
                granularidad: granularidad || 'dia'
            };
            
            $.ajax({
                type: "POST",
                url: 'controller/dashboard.controller.php',
                data: parametros,
                success: function (datos) {
                    $("#content-ot").html(datos);
                },
                error: function () {
                    $("#content-ot").html('<div class="alert alert-danger">Error al cargar los datos</div>');
                }
            });
        }
    });
});
</script>


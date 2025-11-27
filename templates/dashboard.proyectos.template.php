<?php
// Verificar si hay error
if (isset($registros['error'])) {
    echo '<div class="alert alert-danger">Error: ' . htmlspecialchars($registros['error']) . '</div>';
    return;
}

$kpis = isset($registros['kpis']) ? $registros['kpis'] : array();
$actividad_proyectos = isset($registros['actividad_proyectos']) ? $registros['actividad_proyectos'] : array();
$documentacion_proyectos = isset($registros['documentacion_proyectos']) ? $registros['documentacion_proyectos'] : array();
$ot_por_proyecto_estado = isset($registros['ot_por_proyecto_estado']) ? $registros['ot_por_proyecto_estado'] : array();
$tipos_proyecto = isset($registros['tipos_proyecto']) ? $registros['tipos_proyecto'] : array();
$actividad_temporal = isset($registros['actividad_temporal']) ? $registros['actividad_temporal'] : array();
$comparativo = isset($registros['comparativo']) ? $registros['comparativo'] : array();
$alertas = isset($registros['alertas']) ? $registros['alertas'] : array();
$proyecto_seleccionado = isset($registros['proyecto_seleccionado']) ? $registros['proyecto_seleccionado'] : 0;
$granularidad = isset($registros['granularidad']) ? $registros['granularidad'] : 'dia';
?>

<!-- KPIs -->
<div class="row">
    <!-- Total Proyectos -->
    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-folder-open fa-4x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?php echo isset($kpis['total_proyectos']) ? number_format($kpis['total_proyectos']) : 0; ?></div>
                        <div>Total Proyectos</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Proyectos con OT -->
    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
        <div class="panel panel-green">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-tasks fa-4x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?php echo isset($kpis['proyectos_con_ot']) ? number_format($kpis['proyectos_con_ot']) : 0; ?></div>
                        <div>Proyectos con OT</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Proyectos sin Actividad -->
    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
        <div class="panel panel-yellow">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-pause-circle fa-4x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?php echo isset($kpis['proyectos_sin_actividad']) ? number_format($kpis['proyectos_sin_actividad']) : 0; ?></div>
                        <div>Proyectos sin Actividad</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Subidas + Descargas por Proyecto -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-bar-chart"></i> Subidas + Descargas por Proyecto</h4>
            </div>
            <div class="panel-body">
                <?php if (!empty($actividad_proyectos)) { ?>
                    <div style="max-height: 500px; overflow-y: auto;">
                        <?php 
                        $max_total = 0;
                        foreach ($actividad_proyectos as $proj) {
                            $total = intval($proj['subidas']) + intval($proj['descargas']);
                            if ($total > $max_total) $max_total = $total;
                        }
                        foreach ($actividad_proyectos as $proj) { 
                            $total = intval($proj['subidas']) + intval($proj['descargas']);
                            $porc_subidas = $total > 0 ? (intval($proj['subidas']) / $total) * 100 : 0;
                            $porc_descargas = $total > 0 ? (intval($proj['descargas']) / $total) * 100 : 0;
                            $porc_total = $max_total > 0 ? ($total / $max_total) * 100 : 0;
                        ?>
                        <div style="margin-bottom: 20px;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                <span><strong><?php echo htmlspecialchars($proj['proyecto']); ?></strong></span>
                                <span>Total: <?php echo number_format($total); ?> (Subidas: <?php echo number_format($proj['subidas']); ?>, Descargas: <?php echo number_format($proj['descargas']); ?>)</span>
                            </div>
                            <div class="progress" style="height: 30px;">
                                <div class="progress-bar progress-bar-info" role="progressbar" 
                                     style="width: <?php echo $porc_subidas * ($porc_total / 100); ?>%; line-height: 30px;"
                                     title="Subidas: <?php echo number_format($proj['subidas']); ?>">
                                    <?php echo number_format($proj['subidas']); ?>
                                </div>
                                <div class="progress-bar progress-bar-success" role="progressbar" 
                                     style="width: <?php echo $porc_descargas * ($porc_total / 100); ?>%; line-height: 30px;"
                                     title="Descargas: <?php echo number_format($proj['descargas']); ?>">
                                    <?php echo number_format($proj['descargas']); ?>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                <?php } else { ?>
                    <p class="text-muted">No hay datos disponibles</p>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<!-- Documentación y OT por Proyecto -->
<div class="row">
    <!-- Documentación por Proyecto -->
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-files-o"></i> Documentación por Proyecto</h4>
            </div>
            <div class="panel-body">
                <div style="max-height: 400px; overflow-y: auto;">
                    <?php if (!empty($documentacion_proyectos)) { ?>
                        <?php 
                        $max_docs = max(array_column($documentacion_proyectos, 'total_archivos'));
                        foreach ($documentacion_proyectos as $doc) { 
                            $porcentaje = $max_docs > 0 ? ($doc['total_archivos'] / $max_docs) * 100 : 0;
                        ?>
                        <div style="margin-bottom: 15px;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                <span><strong><?php echo htmlspecialchars($doc['proyecto']); ?></strong></span>
                                <span><?php echo number_format($doc['total_archivos']); ?> archivos</span>
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
    
    <!-- OT por Proyecto y Estado -->
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-tasks"></i> OT por Proyecto y Estado</h4>
            </div>
            <div class="panel-body">
                <div style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Proyecto</th>
                                <th>Estado</th>
                                <th class="text-right">Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($ot_por_proyecto_estado)) { ?>
                                <?php foreach ($ot_por_proyecto_estado as $item) { ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($item['proyecto']); ?></strong></td>
                                    <td><span class="label label-default"><?php echo htmlspecialchars($item['estado']); ?></span></td>
                                    <td class="text-right"><?php echo number_format($item['cantidad']); ?></td>
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
</div>

<!-- Tipos de Archivo por Proyecto -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-file"></i> Tipos de Archivo por Proyecto</h4>
            </div>
            <div class="panel-body">
                <?php if (!empty($tipos_proyecto)) { 
                    // Agrupar por proyecto
                    $tipos_por_proyecto = array();
                    foreach ($tipos_proyecto as $tipo) {
                        if (!isset($tipos_por_proyecto[$tipo['proyecto']])) {
                            $tipos_por_proyecto[$tipo['proyecto']] = array();
                        }
                        $tipos_por_proyecto[$tipo['proyecto']][] = $tipo;
                    }
                ?>
                    <div style="max-height: 400px; overflow-y: auto;">
                        <?php foreach ($tipos_por_proyecto as $proyecto => $tipos) { 
                            $total_tipos = array_sum(array_column($tipos, 'cantidad'));
                        ?>
                        <div style="margin-bottom: 25px; border-bottom: 1px solid #ddd; padding-bottom: 15px;">
                            <h5><strong><?php echo htmlspecialchars($proyecto); ?></strong></h5>
                            <div class="progress" style="height: 30px;">
                                <?php 
                                $acumulado = 0;
                                $colores = array('info', 'success', 'warning', 'danger', 'primary', 'default');
                                $i = 0;
                                foreach ($tipos as $tipo) {
                                    $porcentaje = $total_tipos > 0 ? ($tipo['cantidad'] / $total_tipos) * 100 : 0;
                                    $left = $acumulado;
                                    $acumulado += $porcentaje;
                                ?>
                                <div class="progress-bar progress-bar-<?php echo $colores[$i % count($colores)]; ?>" 
                                     role="progressbar" 
                                     style="width: <?php echo $porcentaje; ?>%; left: <?php echo $left; ?>%; line-height: 30px; position: absolute;"
                                     title="<?php echo htmlspecialchars($tipo['tipo']); ?>: <?php echo number_format($tipo['cantidad']); ?>">
                                    <?php if ($porcentaje > 5) { echo htmlspecialchars($tipo['tipo']) . ' (' . number_format($tipo['cantidad']) . ')'; } ?>
                                </div>
                                <?php 
                                    $i++;
                                } 
                                ?>
                            </div>
                            <div style="margin-top: 5px; font-size: 11px;">
                                <?php foreach ($tipos as $tipo) { ?>
                                    <span class="label label-default" style="margin-right: 5px;">
                                        <?php echo htmlspecialchars($tipo['tipo']); ?>: <?php echo number_format($tipo['cantidad']); ?>
                                    </span>
                                <?php } ?>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                <?php } else { ?>
                    <p class="text-muted">No hay datos disponibles</p>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<!-- Actividad Temporal del Proyecto Seleccionado -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-lg-6">
                        <h4><i class="fa fa-line-chart"></i> Actividad Temporal del Proyecto</h4>
                    </div>
                    <div class="col-lg-6">
                        <select class="form-control" id="select-proyecto-temporal" style="display: inline-block; width: auto;">
                            <option value="0">Seleccionar Proyecto...</option>
                            <?php 
                            $proyectos_unicos = array();
                            foreach ($actividad_proyectos as $proj) {
                                if (!in_array($proj['proyecto'], $proyectos_unicos)) {
                                    $proyectos_unicos[] = $proj['proyecto'];
                                }
                            }
                            foreach ($actividad_proyectos as $proj) {
                                if (!isset($proyectos_con_codigo[$proj['proyecto']])) {
                                    $proyectos_con_codigo[$proj['proyecto']] = $proj['codigo_ot'];
                                }
                            }
                            foreach ($proyectos_unicos as $proy) { 
                                $codigo_ot = isset($proyectos_con_codigo[$proy]) ? $proyectos_con_codigo[$proy] : 0;
                                $selected = ($proyecto_seleccionado > 0 && $codigo_ot == $proyecto_seleccionado) ? 'selected' : '';
                            ?>
                            <option value="<?php echo $codigo_ot; ?>" <?php echo $selected; ?>>
                                <?php echo htmlspecialchars($proy); ?>
                            </option>
                            <?php } ?>
                        </select>
                        <div class="btn-group" role="group" style="margin-left: 10px;">
                            <button type="button" class="btn btn-sm btn-default granularidad-btn <?php echo $granularidad == 'dia' ? 'active' : ''; ?>" data-granularidad="dia">Día</button>
                            <button type="button" class="btn btn-sm btn-default granularidad-btn <?php echo $granularidad == 'semana' ? 'active' : ''; ?>" data-granularidad="semana">Semana</button>
                            <button type="button" class="btn btn-sm btn-default granularidad-btn <?php echo $granularidad == 'mes' ? 'active' : ''; ?>" data-granularidad="mes">Mes</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <div id="chart-actividad-temporal" class="ct-chart"></div>
            </div>
        </div>
    </div>
</div>

<!-- Cuadro Comparativo -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-table"></i> Cuadro Comparativo entre Proyectos</h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <input type="text" class="form-control" id="search-comparativo" placeholder="Buscar en la tabla...">
                </div>
                <div style="max-height: 500px; overflow-y: auto;">
                    <table class="table table-striped table-hover" id="tabla-comparativo">
                        <thead>
                            <tr>
                                <th>Proyecto</th>
                                <th class="text-right">Total Subidas</th>
                                <th class="text-right">Total Descargas</th>
                                <th class="text-right">Total Archivos</th>
                                <th class="text-right">Total OT</th>
                                <th class="text-right">OT Cerradas</th>
                                <th class="text-right">OT Abiertas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($comparativo)) { ?>
                                <?php foreach ($comparativo as $item) { 
                                    $porc_cerradas = intval($item['total_ot']) > 0 ? (intval($item['ot_cerradas']) / intval($item['total_ot'])) * 100 : 0;
                                ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($item['proyecto']); ?></strong></td>
                                    <td class="text-right"><?php echo number_format($item['total_subidas']); ?></td>
                                    <td class="text-right"><?php echo number_format($item['total_descargas']); ?></td>
                                    <td class="text-right"><?php echo number_format($item['total_archivos']); ?></td>
                                    <td class="text-right"><?php echo number_format($item['total_ot']); ?></td>
                                    <td class="text-right">
                                        <span class="label label-success"><?php echo number_format($item['ot_cerradas']); ?></span>
                                        <small>(<?php echo number_format($porc_cerradas, 1); ?>%)</small>
                                    </td>
                                    <td class="text-right">
                                        <span class="label label-warning"><?php echo number_format($item['ot_abiertas']); ?></span>
                                    </td>
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

<!-- Alertas de Proyectos -->
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <h4><i class="fa fa-exclamation-triangle"></i> Proyectos sin Actividad</h4>
            </div>
            <div class="panel-body">
                <?php if (!empty($alertas['proyectos_sin_actividad'])) { ?>
                    <ul class="list-group">
                        <?php foreach ($alertas['proyectos_sin_actividad'] as $proj) { ?>
                        <li class="list-group-item">
                            <i class="fa fa-folder"></i> <?php echo htmlspecialchars($proj['proyecto']); ?>
                        </li>
                        <?php } ?>
                    </ul>
                <?php } else { ?>
                    <p class="text-muted">No hay proyectos sin actividad</p>
                <?php } ?>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="panel panel-danger">
            <div class="panel-heading">
                <h4><i class="fa fa-exclamation-circle"></i> Proyectos con OT Atrasadas</h4>
            </div>
            <div class="panel-body">
                <?php if (!empty($alertas['proyectos_ot_atrasadas'])) { ?>
                    <ul class="list-group">
                        <?php foreach ($alertas['proyectos_ot_atrasadas'] as $proj) { ?>
                        <li class="list-group-item">
                            <i class="fa fa-folder"></i> <strong><?php echo htmlspecialchars($proj['proyecto']); ?></strong>
                            <span class="badge"><?php echo number_format($proj['ot_atrasadas']); ?> OT</span>
                        </li>
                        <?php } ?>
                    </ul>
                <?php } else { ?>
                    <p class="text-muted">No hay proyectos con OT atrasadas</p>
                <?php } ?>
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
.progress { position: relative; }
</style>

<script>
$(document).ready(function() {
    // Gráfico de Actividad Temporal
    <?php if (!empty($actividad_temporal)) { 
        // Separar subidas y descargas
        $fechas_unicas = array();
        $subidas_data = array();
        $descargas_data = array();
        
        foreach ($actividad_temporal as $item) {
            if (!in_array($item['fecha'], $fechas_unicas)) {
                $fechas_unicas[] = $item['fecha'];
            }
        }
        sort($fechas_unicas);
        
        foreach ($fechas_unicas as $fecha) {
            $subidas = 0;
            $descargas = 0;
            foreach ($actividad_temporal as $item) {
                if ($item['fecha'] == $fecha) {
                    if ($item['tipo'] == 'Subidas') {
                        $subidas = intval($item['cantidad']);
                    } else {
                        $descargas = intval($item['cantidad']);
                    }
                }
            }
            $subidas_data[] = $subidas;
            $descargas_data[] = $descargas;
        }
    ?>
    setTimeout(function() {
        if (typeof Chartist !== 'undefined' && $('#chart-actividad-temporal').length) {
            new Chartist.Line('#chart-actividad-temporal', {
                labels: <?php echo json_encode($fechas_unicas); ?>,
                series: [
                    <?php echo json_encode($subidas_data); ?>,
                    <?php echo json_encode($descargas_data); ?>
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
        }
    }, 300);
    <?php } ?>
    
    // Búsqueda en tabla comparativa
    $('#search-comparativo').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('#tabla-comparativo tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });
    
    // Cambio de proyecto seleccionado
    $('#select-proyecto-temporal').change(function() {
        var proyecto = $(this).val();
        var granularidad = $('.granularidad-btn.active').data('granularidad') || 'dia';
        loadProyectos(proyecto, granularidad);
    });
    
    // Cambio de granularidad
    $('.granularidad-btn').click(function() {
        $('.granularidad-btn').removeClass('active');
        $(this).addClass('active');
        var granularidad = $(this).data('granularidad');
        var proyecto = $('#select-proyecto-temporal').val();
        loadProyectos(proyecto, granularidad);
    });
    
    function loadProyectos(proyecto, granularidad) {
        if (typeof loadProyectosTab === 'function') {
            loadProyectosTab(proyecto, granularidad);
        } else {
            var parametros = {
                funcion: 'getProyectos',
                fecha_desde: $("#fecha_desde").val(),
                fecha_hasta: $("#fecha_hasta").val(),
                proyecto_seleccionado: proyecto || 0,
                granularidad: granularidad || 'dia'
            };
            
            $.ajax({
                type: "POST",
                url: 'controller/dashboard.controller.php',
                data: parametros,
                success: function (datos) {
                    $("#content-proyectos").html(datos);
                },
                error: function () {
                    $("#content-proyectos").html('<div class="alert alert-danger">Error al cargar los datos</div>');
                }
            });
        }
    }
});
</script>


<div class="row" style="margin-top: 20px;">
    <div class="col-lg-12">
        <h3><i class="fa fa-clock-o"></i> Tiempos y Fechas</h3>
        <hr>
    </div>
</div>

<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
        <div class="panel panel-danger">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-exclamation-triangle fa-4x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?php echo isset($registros['entregas_vencidas']) ? $registros['entregas_vencidas'] : 0; ?></div>
                        <div>Entregas Vencidas</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-calendar fa-4x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?php echo isset($registros['entregas_proximas']) ? $registros['entregas_proximas'] : 0; ?></div>
                        <div>Próximas 7 Días</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-question-circle fa-4x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?php echo isset($registros['sin_fecha_entrega']) ? $registros['sin_fecha_entrega'] : 0; ?></div>
                        <div>Sin Fecha Entrega</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
        <div class="panel panel-success">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-clock-o fa-4x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?php echo isset($registros['dias_promedio']) ? number_format($registros['dias_promedio'], 0) : 0; ?></div>
                        <div>Días Promedio</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-bar-chart"></i> Análisis de Tiempos</h4>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Indicador</th>
                                    <th class="text-center">Cantidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="label label-danger">Entregas Vencidas</span></td>
                                    <td class="text-center"><strong><?php echo isset($registros['entregas_vencidas']) ? $registros['entregas_vencidas'] : 0; ?></strong></td>
                                </tr>
                                <tr>
                                    <td><span class="label label-warning">Próximas 7 Días</span></td>
                                    <td class="text-center"><strong><?php echo isset($registros['entregas_proximas']) ? $registros['entregas_proximas'] : 0; ?></strong></td>
                                </tr>
                                <tr>
                                    <td><span class="label label-info">Sin Fecha Entrega</span></td>
                                    <td class="text-center"><strong><?php echo isset($registros['sin_fecha_entrega']) ? $registros['sin_fecha_entrega'] : 0; ?></strong></td>
                                </tr>
                                <tr>
                                    <td><strong>Total OTs</strong></td>
                                    <td class="text-center"><strong><?php echo isset($registros['total_ots']) ? $registros['total_ots'] : 0; ?></strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h5><i class="fa fa-info-circle"></i> Días Promedio de Procesamiento</h5>
                            </div>
                            <div class="panel-body text-center">
                                <div class="huge" style="font-size: 48px; color: #1ab394;">
                                    <?php 
                                    $dias = isset($registros['dias_promedio']) ? floatval($registros['dias_promedio']) : 0;
                                    echo number_format($dias, 1); 
                                    ?>
                                </div>
                                <p class="text-muted">Días promedio entre fecha de ingreso y entrega</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.panel-danger { border-color: #ed5565; }
.panel-danger > .panel-heading { background-color: #ed5565; border-color: #ed5565; color: white; }
.panel-warning { border-color: #f8ac59; }
.panel-warning > .panel-heading { background-color: #f8ac59; border-color: #f8ac59; color: white; }
.panel-info { border-color: #1c84c6; }
.panel-info > .panel-heading { background-color: #1c84c6; border-color: #1c84c6; color: white; }
.panel-success { border-color: #1ab394; }
.panel-success > .panel-heading { background-color: #1ab394; border-color: #1ab394; color: white; }
.huge { font-size: 32px; font-weight: bold; }
</style>


<div class="row" style="margin-top: 20px;">
    <div class="col-lg-12">
        <h3><i class="fa fa-cogs"></i> Producción</h3>
        <hr>
    </div>
</div>

<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-list-alt fa-4x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?php echo isset($registros['total_produccion']) ? $registros['total_produccion'] : 0; ?></div>
                        <div>Total Producción</div>
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
                        <i class="fa fa-clock-o fa-4x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?php echo isset($registros['en_cola']) ? $registros['en_cola'] : 0; ?></div>
                        <div>En Cola</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-spinner fa-4x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?php echo isset($registros['en_curso']) ? $registros['en_curso'] : 0; ?></div>
                        <div>En Curso</div>
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
                        <i class="fa fa-check fa-4x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?php echo isset($registros['finalizadas']) ? $registros['finalizadas'] : 0; ?></div>
                        <div>Finalizadas</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-percent"></i> Avance Promedio</h4>
            </div>
            <div class="panel-body text-center">
                <div class="huge" style="font-size: 48px; color: #1ab394;">
                    <?php 
                    $avance = isset($registros['avance_promedio']) ? floatval($registros['avance_promedio']) : 0;
                    echo number_format($avance, 2); 
                    ?>%
                </div>
                <div class="progress" style="margin-top: 20px; height: 30px;">
                    <div class="progress-bar progress-bar-success progress-bar-striped active" 
                         role="progressbar" 
                         style="width: <?php echo min($avance, 100); ?>%; line-height: 30px;">
                        <?php echo number_format($avance, 1); ?>%
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-cubes"></i> Cantidad Total</h4>
            </div>
            <div class="panel-body text-center">
                <div class="huge" style="font-size: 48px; color: #1c84c6;">
                    <?php 
                    $cantidad = isset($registros['cantidad_total']) ? floatval($registros['cantidad_total']) : 0;
                    echo number_format($cantidad, 0); 
                    ?>
                </div>
                <p class="text-muted">Unidades en producción</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-bar-chart"></i> Distribución de Estados</h4>
            </div>
            <div class="panel-body">
                <?php 
                $total = isset($registros['total_produccion']) ? intval($registros['total_produccion']) : 1;
                $porc_cola = $total > 0 ? (intval($registros['en_cola']) / $total) * 100 : 0;
                $porc_curso = $total > 0 ? (intval($registros['en_curso']) / $total) * 100 : 0;
                $porc_fin = $total > 0 ? (intval($registros['finalizadas']) / $total) * 100 : 0;
                ?>
                <div class="progress" style="height: 40px;">
                    <div class="progress-bar progress-bar-warning" style="width: <?php echo $porc_cola; ?>%; line-height: 40px;">
                        En Cola: <?php echo $registros['en_cola']; ?>
                    </div>
                    <div class="progress-bar progress-bar-info" style="width: <?php echo $porc_curso; ?>%; line-height: 40px;">
                        En Curso: <?php echo $registros['en_curso']; ?>
                    </div>
                    <div class="progress-bar progress-bar-success" style="width: <?php echo $porc_fin; ?>%; line-height: 40px;">
                        Finalizadas: <?php echo $registros['finalizadas']; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.panel-info { border-color: #1c84c6; }
.panel-info > .panel-heading { background-color: #1c84c6; border-color: #1c84c6; color: white; }
.panel-warning { border-color: #f8ac59; }
.panel-warning > .panel-heading { background-color: #f8ac59; border-color: #f8ac59; color: white; }
.panel-primary { border-color: #1ab394; }
.panel-primary > .panel-heading { background-color: #1ab394; border-color: #1ab394; color: white; }
.panel-success { border-color: #1c84c6; }
.panel-success > .panel-heading { background-color: #1c84c6; border-color: #1c84c6; color: white; }
.huge { font-size: 32px; font-weight: bold; }
</style>


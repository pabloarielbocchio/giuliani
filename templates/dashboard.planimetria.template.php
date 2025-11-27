<div class="row" style="margin-top: 20px;">
    <div class="col-lg-12">
        <h3><i class="fa fa-file-pdf-o"></i> Planimetría</h3>
        <hr>
    </div>
</div>

<div class="row">
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-files-o fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?php echo isset($registros['total_archivos']) ? $registros['total_archivos'] : 0; ?></div>
                        <div>Total Archivos</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <div class="panel panel-success">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-check-circle fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?php echo isset($registros['activos']) ? $registros['activos'] : 0; ?></div>
                        <div>Archivos Activos</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-ban fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?php echo isset($registros['inactivos']) ? $registros['inactivos'] : 0; ?></div>
                        <div>Archivos Inactivos</div>
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
                <h4><i class="fa fa-pie-chart"></i> Distribución de Archivos</h4>
            </div>
            <div class="panel-body">
                <?php 
                $total = isset($registros['total_archivos']) ? intval($registros['total_archivos']) : 1;
                $activos = isset($registros['activos']) ? intval($registros['activos']) : 0;
                $inactivos = isset($registros['inactivos']) ? intval($registros['inactivos']) : 0;
                $porc_activos = $total > 0 ? ($activos / $total) * 100 : 0;
                $porc_inactivos = $total > 0 ? ($inactivos / $total) * 100 : 0;
                ?>
                
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <table class="table table-striped">
                            <tr>
                                <td><strong>Total Archivos:</strong></td>
                                <td class="text-right"><span class="badge badge-primary"><?php echo $total; ?></span></td>
                            </tr>
                            <tr>
                                <td><span class="label label-success">Activos</span></td>
                                <td class="text-right"><strong><?php echo $activos; ?></strong> (<?php echo number_format($porc_activos, 1); ?>%)</td>
                            </tr>
                            <tr>
                                <td><span class="label label-warning">Inactivos</span></td>
                                <td class="text-right"><strong><?php echo $inactivos; ?></strong> (<?php echo number_format($porc_inactivos, 1); ?>%)</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="progress" style="height: 50px;">
                            <div class="progress-bar progress-bar-success" 
                                 style="width: <?php echo $porc_activos; ?>%; line-height: 50px; font-size: 16px;">
                                Activos: <?php echo $activos; ?>
                            </div>
                            <div class="progress-bar progress-bar-warning" 
                                 style="width: <?php echo $porc_inactivos; ?>%; line-height: 50px; font-size: 16px;">
                                Inactivos: <?php echo $inactivos; ?>
                            </div>
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
.panel-success { border-color: #1c84c6; }
.panel-success > .panel-heading { background-color: #1c84c6; border-color: #1c84c6; color: white; }
.panel-warning { border-color: #f8ac59; }
.panel-warning > .panel-heading { background-color: #f8ac59; border-color: #f8ac59; color: white; }
.huge { font-size: 40px; font-weight: bold; }
</style>


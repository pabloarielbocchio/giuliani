<div class="row" style="margin-top: 20px;">
    <div class="col-lg-12">
        <h3><i class="fa fa-bar-chart"></i> Análisis por Tipo</h3>
        <hr>
    </div>
</div>

<?php if (!empty($registros)) { ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-list"></i> Distribución de OTs por Tipo</h4>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Tipo</th>
                                <th class="text-center">Cantidad</th>
                                <th class="text-center">Avance Promedio</th>
                                <th class="text-center">Porcentaje</th>
                                <th class="text-center">Visualización</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total_cantidad = 0;
                            foreach ($registros as $item) {
                                $total_cantidad += intval($item['cantidad']);
                            }
                            
                            foreach ($registros as $item) { 
                                $cantidad = intval($item['cantidad']);
                                $porcentaje = $total_cantidad > 0 ? ($cantidad / $total_cantidad) * 100 : 0;
                                $avance = floatval($item['avance_promedio']);
                            ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($item['tipo_nombre'] ? $item['tipo_nombre'] : 'Sin Tipo'); ?></strong></td>
                                <td class="text-center">
                                    <span class="badge badge-primary" style="font-size: 14px;">
                                        <?php echo $cantidad; ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="progress" style="margin: 0; height: 20px;">
                                        <div class="progress-bar progress-bar-success" 
                                             role="progressbar" 
                                             style="width: <?php echo min($avance, 100); ?>%; line-height: 20px;">
                                            <?php echo number_format($avance, 1); ?>%
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <strong><?php echo number_format($porcentaje, 1); ?>%</strong>
                                </td>
                                <td>
                                    <div class="progress" style="height: 25px; margin: 0;">
                                        <div class="progress-bar progress-bar-info" 
                                             role="progressbar" 
                                             style="width: <?php echo $porcentaje; ?>%; line-height: 25px;">
                                            <?php echo number_format($porcentaje, 1); ?>%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td><strong>TOTAL</strong></td>
                                <td class="text-center">
                                    <span class="badge badge-success" style="font-size: 16px;">
                                        <?php echo $total_cantidad; ?>
                                    </span>
                                </td>
                                <td colspan="3"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-pie-chart"></i> Gráfico de Distribución</h4>
            </div>
            <div class="panel-body">
                <div class="row">
                    <?php foreach ($registros as $item) { 
                        $cantidad = intval($item['cantidad']);
                        $porcentaje = $total_cantidad > 0 ? ($cantidad / $total_cantidad) * 100 : 0;
                    ?>
                    <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12" style="margin-bottom: 15px;">
                        <div class="panel panel-default">
                            <div class="panel-body text-center">
                                <h5><?php echo htmlspecialchars($item['tipo_nombre'] ? $item['tipo_nombre'] : 'Sin Tipo'); ?></h5>
                                <div class="huge" style="font-size: 36px; color: #1ab394;">
                                    <?php echo $cantidad; ?>
                                </div>
                                <div class="progress" style="margin-top: 10px;">
                                    <div class="progress-bar progress-bar-info" 
                                         role="progressbar" 
                                         style="width: <?php echo $porcentaje; ?>%">
                                        <?php echo number_format($porcentaje, 1); ?>%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } else { ?>
<div class="alert alert-info">
    <i class="fa fa-info-circle"></i> No hay datos disponibles para el análisis.
</div>
<?php } ?>

<style>
.huge { font-size: 36px; font-weight: bold; }
.badge-primary { background-color: #1ab394; }
.badge-success { background-color: #1c84c6; }
</style>


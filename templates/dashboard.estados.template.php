<div class="row" style="margin-top: 20px;">
    <div class="col-lg-12">
        <h3><i class="fa fa-tasks"></i> Estados por Área</h3>
        <hr>
    </div>
</div>

<?php if (!empty($registros)) { ?>
<div class="row">
    <?php foreach ($registros as $area) { ?>
    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12" style="margin-bottom: 20px;">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-building"></i> <?php echo htmlspecialchars($area['area']); ?></h4>
            </div>
            <div class="panel-body">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Estado</th>
                            <th class="text-center">Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><span class="label label-info">En Cola</span></td>
                            <td class="text-center"><strong><?php echo $area['en_cola']; ?></strong></td>
                        </tr>
                        <tr>
                            <td><span class="label label-warning">En Curso</span></td>
                            <td class="text-center"><strong><?php echo $area['en_curso']; ?></strong></td>
                        </tr>
                        <tr>
                            <td><span class="label label-success">Finalizadas</span></td>
                            <td class="text-center"><strong><?php echo $area['finalizadas']; ?></strong></td>
                        </tr>
                        <tr>
                            <td><span class="label label-danger">Canceladas</span></td>
                            <td class="text-center"><strong><?php echo $area['canceladas']; ?></strong></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td><strong>Total</strong></td>
                            <td class="text-center">
                                <strong><?php echo $area['en_cola'] + $area['en_curso'] + $area['finalizadas'] + $area['canceladas']; ?></strong>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                
                <!-- Gráfico de barras simple -->
                <div style="margin-top: 15px;">
                    <?php 
                    $total = $area['en_cola'] + $area['en_curso'] + $area['finalizadas'] + $area['canceladas'];
                    $porc_cola = $total > 0 ? ($area['en_cola'] / $total) * 100 : 0;
                    $porc_curso = $total > 0 ? ($area['en_curso'] / $total) * 100 : 0;
                    $porc_fin = $total > 0 ? ($area['finalizadas'] / $total) * 100 : 0;
                    $porc_canc = $total > 0 ? ($area['canceladas'] / $total) * 100 : 0;
                    ?>
                    <div class="progress" style="height: 25px; margin-bottom: 5px;">
                        <div class="progress-bar progress-bar-info" style="width: <?php echo $porc_cola; ?>%">
                            <?php echo $area['en_cola']; ?>
                        </div>
                        <div class="progress-bar progress-bar-warning" style="width: <?php echo $porc_curso; ?>%">
                            <?php echo $area['en_curso']; ?>
                        </div>
                        <div class="progress-bar progress-bar-success" style="width: <?php echo $porc_fin; ?>%">
                            <?php echo $area['finalizadas']; ?>
                        </div>
                        <div class="progress-bar progress-bar-danger" style="width: <?php echo $porc_canc; ?>%">
                            <?php echo $area['canceladas']; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
</div>
<?php } else { ?>
<div class="alert alert-info">
    <i class="fa fa-info-circle"></i> No hay datos disponibles.
</div>
<?php } ?>


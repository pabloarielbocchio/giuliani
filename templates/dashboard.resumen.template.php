<div class="row" style="margin-top: 20px;">
    <div class="col-lg-12">
        <h3><i class="fa fa-dashboard"></i> Resumen General</h3>
        <hr>
    </div>
</div>

<div class="row">
    <!-- Total OTs -->
    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-list fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?php echo isset($registros['total_ots']) ? $registros['total_ots'] : 0; ?></div>
                        <div>Total OTs</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Ingeniería -->
    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
        <div class="panel panel-green">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-cog fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?php echo isset($registros['en_curso_ing']) ? $registros['en_curso_ing'] : 0; ?></div>
                        <div>Ingeniería en Curso</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Producción -->
    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
        <div class="panel panel-yellow">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-industry fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?php echo isset($registros['en_curso_prod']) ? $registros['en_curso_prod'] : 0; ?></div>
                        <div>Producción en Curso</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Despacho -->
    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
        <div class="panel panel-red">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-truck fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?php echo isset($registros['en_curso_desp']) ? $registros['en_curso_desp'] : 0; ?></div>
                        <div>Despacho en Curso</div>
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
                <h4><i class="fa fa-check-circle"></i> Estados Ingeniería</h4>
            </div>
            <div class="panel-body">
                <table class="table table-striped">
                    <tr>
                        <td><strong>En Cola:</strong></td>
                        <td class="text-right"><span class="badge badge-info"><?php echo isset($registros['en_cola_ing']) ? $registros['en_cola_ing'] : 0; ?></span></td>
                    </tr>
                    <tr>
                        <td><strong>En Curso:</strong></td>
                        <td class="text-right"><span class="badge badge-warning"><?php echo isset($registros['en_curso_ing']) ? $registros['en_curso_ing'] : 0; ?></span></td>
                    </tr>
                    <tr>
                        <td><strong>Finalizadas:</strong></td>
                        <td class="text-right"><span class="badge badge-success"><?php echo isset($registros['finalizadas_ing']) ? $registros['finalizadas_ing'] : 0; ?></span></td>
                    </tr>
                    <tr>
                        <td><strong>Canceladas:</strong></td>
                        <td class="text-right"><span class="badge badge-danger"><?php echo isset($registros['canceladas_ing']) ? $registros['canceladas_ing'] : 0; ?></span></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
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
                <div class="progress" style="margin-top: 20px;">
                    <div class="progress-bar progress-bar-success" role="progressbar" 
                         style="width: <?php echo min($avance, 100); ?>%">
                    </div>
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
.panel-red { border-color: #ed5565; }
.panel-red > .panel-heading { background-color: #ed5565; border-color: #ed5565; color: white; }
.huge { font-size: 40px; font-weight: bold; }
</style>


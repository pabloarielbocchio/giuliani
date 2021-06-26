<?php

session_start();

if (!($_SESSION["permisos"][basename(__FILE__, '.php') . ".php"]["access"])) {
    header("Location: login.php");
}

include $_SERVER['DOCUMENT_ROOT'] . "/Giuliani/controller/archivos.controller.php";
$controlador = ArchivosController::singleton_archivos();
$prod_a = $controlador->getProductosA();
$prod_b = $controlador->getProductosB();
$prod_c = $controlador->getProductosC();
$prod_d = $controlador->getProductosD();
$prod_e = $controlador->getProductosE();
$prod_f = $controlador->getProductosF();
$prod_s = $controlador->getProductosS();
$prod_p = $controlador->getProductosP();

$_SESSION["totales"] = $controlador->getCountArchivos();

// Recepcion parametros PAGINACION /*******************************************/

if (isset($_GET['pagina'])) {
    $pagina = $_GET['pagina'];
} else {
    if (isset($_POST['pagina'])) {
        $pagina = $_POST['pagina'];
    } else {
        $pagina = 1;
    }
}
$_SESSION["pagina"] = $pagina;

if (isset($_GET['cant_reg'])) {
    $cant_reg = $_GET['cant_reg'];
} else {
    if (isset($_POST['cant_reg'])) {
        $cant_reg = $_POST['cant_reg'];
    } else {
        $cant_reg = 25;
    }
}
$_SESSION["cant_reg"] = $cant_reg;

if (isset($_GET['busqueda'])) {
    $busqueda = $_GET['busqueda'];
} else {
    if (isset($_POST['busqueda'])) {
        $busqueda = $_POST['busqueda'];
    } else {
        $busqueda = "";
    }
}
$_SESSION["busqueda"] = $busqueda;

if (isset($_GET['orderby'])) {
    $orderby = $_GET['orderby'];
} else {
    if (isset($_POST['orderby'])) {
        $orderby = $_POST['orderby'];
    } else {
        $orderby = "codigo";
    }
}
$_SESSION["orderby"] = $orderby;

if (isset($_GET['sentido'])) {
    $sentido = $_GET['sentido'];
} else {
    if (isset($_POST['sentido'])) {
        $sentido = $_POST['sentido'];
    } else {
        $sentido = "asc";
    }
}
$_SESSION["sentido"] = $sentido;

// Fin Recepcion parametros PAGINACION /***************************************/

$_SESSION['menu'] = "archivos.php";

$_SESSION['breadcrumb'] = "Archivos";

$titlepage = "Archivos";

include 'inc/html/encabezado.php';

include 'inc/html/menu.php';

include 'inc/html/breadcrumb.php';

?>


<div class="container">

    <div id="loading" class="loading"></div>
    
    <div id="modulo_paginacion">
        <?php include 'inc/html/paginacion.php'; ?>
    </div>

    <div    id="div_tabla" 
            class="row col-lg-12" 
            style="float: none"
            registros="<?php echo $_SESSION['cant_reg']; ?>" 
            pagina="<?php echo $_SESSION['pagina']; ?>"
            orderby="<?php echo $_SESSION['orderby']; ?>"
            sentido="<?php echo $_SESSION['sentido']; ?>"
    >
        <!-- Devolución Ajax -->
    </div>

    <div class="modal fade" id="myModal" archivoe="dialog"> 
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 id="name-header-modal" class="modal-title">Eliminar</h4>
                </div>
                <div class="modal-body text-center"  id="text-header-body">

                </div>
                <div class="modal-footer">
                    <button type="button" id="btn-eliminar-archivo" name="btn-eliminar-archivo" class="btn btn-danger boton_marron_carni" data-dismiss="modal" >Eliminar</button>
                    <button type="button" id="btn-cancelar" name="btn-cancelar" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <form id="guardarDatosArchivo">
        <div class="modal fade" id="dataRegister" tabindex="-1" archivoe="dialog" aria-labelledby="exampleModalLabel">
            <div class="modal-dialog" archivoe="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="exampleModalLabel">Nuevo</h4>
                    </div>
                    <div class="modal-body">
                        <div id="datos_ajax_register"></div>
                        <div class="form-group">
                            <label for="nombre0" class="control-label">Nombre:</label>
                            <input type="text" class="form-control" id="descripcionAdd" name="descripcionAdd" required maxlength="100">
                        </div>
                        <div class="form-group">
                            <label for="nombre0" class="control-label">Path:</label>
                            <input type="text" class="form-control" id="rutaAdd" name="rutaAdd" required maxlength="100">
                        </div>
                        <div class="form-group">
                            <label for="nombre0" class="control-label">Fecha:</label>
                            <input type="date" class="form-control" id="fechaAdd" name="fechaAdd" required maxlength="10" value="<?php echo date("Y-m-d"); ?>" >
                        </div>
                        <div class="form-group"> 
                            <label for="nombre0" class="control-label">Activo:</label>
                            <select id="activoAdd" style="width: 100%;" class="form-control" name="activoAdd"  required>
                                <option value="0" >NO</option>
                                <option value="1" >SI</option>
                            </select>
                        </div>
                        <div class="form-group"> 
                            <label for="nombre0" class="control-label">Producto Nivel 1:</label>
                            <select id="prodaAdd" style="width: 100%;" class="form-control" name="prodaAdd"  required>
                                <?php 
                                    foreach ($prod_a as $aux) { 
                                ?>
                                        <option value="<?php echo $aux["codigo"]; ?>" ><?php echo $aux["descripcion"]; ?></option>
                                <?php 
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group"> 
                            <label for="nombre0" class="control-label">Producto Nivel 2:</label>
                            <select id="prodbAdd" style="width: 100%;" class="form-control" name="prodbAdd"  required>
                                <?php 
                                    foreach ($prod_b as $aux) { 
                                ?>
                                        <option value="<?php echo $aux["codigo"]; ?>" ><?php echo $aux["descripcion"]; ?></option>
                                <?php 
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group"> 
                            <label for="nombre0" class="control-label">Producto Nivel 3:</label>
                            <select id="prodcAdd" style="width: 100%;" class="form-control" name="prodcAdd"  required>
                                <?php 
                                    foreach ($prod_c as $aux) { 
                                ?>
                                        <option value="<?php echo $aux["codigo"]; ?>" ><?php echo $aux["descripcion"]; ?></option>
                                <?php 
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group"> 
                            <label for="nombre0" class="control-label">Producto Nivel 4:</label>
                            <select id="proddAdd" style="width: 100%;" class="form-control" name="proddAdd"  required>
                                <?php 
                                    foreach ($prod_d as $aux) { 
                                ?>
                                        <option value="<?php echo $aux["codigo"]; ?>" ><?php echo $aux["descripcion"]; ?></option>
                                <?php 
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group"> 
                            <label for="nombre0" class="control-label">Producto Nivel 5:</label>
                            <select id="prodeAdd" style="width: 100%;" class="form-control" name="prodeAdd"  required>
                                <?php 
                                    foreach ($prod_e as $aux) { 
                                ?>
                                        <option value="<?php echo $aux["codigo"]; ?>" ><?php echo $aux["descripcion"]; ?></option>
                                <?php 
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group"> 
                            <label for="nombre0" class="control-label">Producto Nivel 6:</label>
                            <select id="prodfAdd" style="width: 100%;" class="form-control" name="prodfAdd"  required>
                                <?php 
                                    foreach ($prod_f as $aux) { 
                                ?>
                                        <option value="<?php echo $aux["codigo"]; ?>" ><?php echo $aux["descripcion"]; ?></option>
                                <?php 
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group"> 
                            <label for="nombre0" class="control-label">Producto Personalizado:</label>
                            <select id="prodpAdd" style="width: 100%;" class="form-control" name="prodpAdd"  required>
                                <?php 
                                    foreach ($prod_p as $aux) { 
                                ?>
                                        <option value="<?php echo $aux["codigo"]; ?>" ><?php echo $aux["descripcion"]; ?></option>
                                <?php 
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group"> 
                            <label for="nombre0" class="control-label">Producto Standard:</label>
                            <select id="prodsAdd" style="width: 100%;" class="form-control" name="prodsAdd"  required>
                                <?php 
                                    foreach ($prod_s as $aux) { 
                                ?>
                                        <option value="<?php echo $aux["codigo"]; ?>" ><?php echo $aux["descripcion"]; ?></option>
                                <?php 
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger boton_marron_carni" >Guardar datos</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <form id="actualidarDatosArchivo">
        <div class="modal fade" id="dataUpdate" tabindex="-1" archivoe="dialog" aria-labelledby="exampleModalLabel">
            <div class="modal-dialog" archivoe="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="exampleModalLabel">Modificar</h4>
                    </div>
                    <div class="modal-body">
                        <div id="datos_ajax_register"></div>
                        <div class="form-group">
                            <label for="nombre0" class="control-label">Nombre:</label>
                            <input type="text" class="form-control" id="descripcionUpdate" name="descripcionUpdate" required maxlength="100">
                        </div>
                        <div class="form-group">
                            <label for="nombre0" class="control-label">Path:</label>
                            <input type="text" class="form-control" id="rutaUpdate" name="rutaUpdate" required maxlength="100">
                        </div>
                        <div class="form-group">
                            <label for="nombre0" class="control-label">Fecha:</label>
                            <input type="date" class="form-control" id="fechaUpdate" name="fechaUpdate" required maxlength="10" value="<?php echo date("Y-m-d"); ?>" >
                        </div>
                        <div class="form-group"> 
                            <label for="nombre0" class="control-label">Activo:</label>
                            <select id="activoUpdate" style="width: 100%;" class="form-control" name="activoUpdate"  required>
                                <option value="0" >NO</option>
                                <option value="1" >SI</option>
                            </select>
                        </div>
                        <div class="form-group"> 
                            <label for="nombre0" class="control-label">Producto Nivel 1:</label>
                            <select id="prodaUpdate" style="width: 100%;" class="form-control" name="prodaUpdate"  required>
                                <?php 
                                    foreach ($prod_a as $aux) { 
                                ?>
                                        <option value="<?php echo $aux["codigo"]; ?>" ><?php echo $aux["descripcion"]; ?></option>
                                <?php 
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group"> 
                            <label for="nombre0" class="control-label">Producto Nivel 2:</label>
                            <select id="prodbUpdate" style="width: 100%;" class="form-control" name="prodbUpdate"  required>
                                <?php 
                                    foreach ($prod_b as $aux) { 
                                ?>
                                        <option value="<?php echo $aux["codigo"]; ?>" ><?php echo $aux["descripcion"]; ?></option>
                                <?php 
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group"> 
                            <label for="nombre0" class="control-label">Producto Nivel 3:</label>
                            <select id="prodcUpdate" style="width: 100%;" class="form-control" name="prodcUpdate"  required>
                                <?php 
                                    foreach ($prod_c as $aux) { 
                                ?>
                                        <option value="<?php echo $aux["codigo"]; ?>" ><?php echo $aux["descripcion"]; ?></option>
                                <?php 
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group"> 
                            <label for="nombre0" class="control-label">Producto Nivel 4:</label>
                            <select id="proddUpdate" style="width: 100%;" class="form-control" name="proddUpdate"  required>
                                <?php 
                                    foreach ($prod_d as $aux) { 
                                ?>
                                        <option value="<?php echo $aux["codigo"]; ?>" ><?php echo $aux["descripcion"]; ?></option>
                                <?php 
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group"> 
                            <label for="nombre0" class="control-label">Producto Nivel 5:</label>
                            <select id="prodeUpdate" style="width: 100%;" class="form-control" name="prodeUpdate"  required>
                                <?php 
                                    foreach ($prod_e as $aux) { 
                                ?>
                                        <option value="<?php echo $aux["codigo"]; ?>" ><?php echo $aux["descripcion"]; ?></option>
                                <?php 
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group"> 
                            <label for="nombre0" class="control-label">Producto Nivel 6:</label>
                            <select id="prodfUpdate" style="width: 100%;" class="form-control" name="prodfUpdate"  required>
                                <?php 
                                    foreach ($prod_f as $aux) { 
                                ?>
                                        <option value="<?php echo $aux["codigo"]; ?>" ><?php echo $aux["descripcion"]; ?></option>
                                <?php 
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group"> 
                            <label for="nombre0" class="control-label">Producto Personalizado:</label>
                            <select id="prodpUpdate" style="width: 100%;" class="form-control" name="prodpUpdate"  required>
                                <?php 
                                    foreach ($prod_p as $aux) { 
                                ?>
                                        <option value="<?php echo $aux["codigo"]; ?>" ><?php echo $aux["descripcion"]; ?></option>
                                <?php 
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group"> 
                            <label for="nombre0" class="control-label">Producto Standard:</label>
                            <select id="prodsUpdate" style="width: 100%;" class="form-control" name="prodsUpdate"  required>
                                <?php 
                                    foreach ($prod_s as $aux) { 
                                ?>
                                        <option value="<?php echo $aux["codigo"]; ?>" ><?php echo $aux["descripcion"]; ?></option>
                                <?php 
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger boton_marron_carni" >Actualizar datos</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    
    
    <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12 right">
        <input type="button" id="export" name="export" class="btn-danger btn-sm boton_marron_carni" style="border-radius: 10px; margin-left: 10px;" value="Exportar"/>
    </div>
    <div class="hidden-lg hidden-md">
        <br /><br />
    </div>
    
</div>

<div style="bottom: 50px; right: 40px; position: fixed;">
    <button id="add" name="add" type="button" class="btn btn-danger btn-lg boton_marron_carni" style="width: 50px;border-radius: 50%; text-align: center; background-color: transparent;"><i style="margin-bottom: 5px;" class="glyphicon glyphicon-plus"></i></button>
</div>

<?php

include 'inc/html/footer.php';

?>

<script type="text/javascript" src="inc/js/archivos_js.js?version=<?php echo date("Y-m-d H:i:s"); ?>"></script>
<script type="text/javascript" src="inc/js/utils.js"></script>
<script type="text/javascript" src="inc/js/jquery.table2excel.js"></script>
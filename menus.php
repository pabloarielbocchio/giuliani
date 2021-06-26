<?php

session_start();

if (!($_SESSION["permisos"][basename(__FILE__, '.php') . ".php"]["access"])) {
    //header("Location: inicio.php");
}

include $_SERVER['DOCUMENT_ROOT'] . "/Giuliani/controller/menus.controller.php";
$controlador    = MenusController::singleton_menus();
$menues         = $controlador->getMenusAll();
$niveles        = array();
$subniveles     = array();
$opciones       = array();
foreach ($menues as $m){
    $nivel = 1;
    if ($m["nivel"] > 0){
        $niveles[] = $m;
        $nivel++;
    }
    if ($m["subnivel"] > 0){
        $subniveles[] = $m;
        $nivel++;
    }
    //$m["nombre"] = "NIVEL: " . $nivel . " - " . $m["nombre"];
    $opciones[] = $m;
}
$_SESSION["totales"] = $controlador->getCountMenus();

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
        $orderby = "id desc";
    }
}
$_SESSION["orderby"] = $orderby;

if (isset($_GET['sentido'])) {
    $sentido = $_GET['sentido'];
} else {
    if (isset($_POST['sentido'])) {
        $sentido = $_POST['sentido'];
    } else {
        $sentido = "";
    }
}
$_SESSION["sentido"] = $sentido;

// Fin Recepcion parametros PAGINACION /***************************************/

$_SESSION['menu'] = basename(__FILE__, '.php') . ".php";

$_SESSION['breadcrumb'] = "Menus";

$titlepage = "Giuliani - Menus";

include 'inc/html/encabezado.php';

include 'inc/html/menu.php';

include 'inc/html/breadcrumb.php';

?>

<style>
    label{
        font-size: 11px;
    }
</style>

<div class="container" style="width: 96%; margin-left: 2%;">

    <div id="loading" class="loading"></div>
    
    <div id="modulo_paginacion" style="display: none;">
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

    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 id="name-header-modal" class="modal-title">Eliminar</h4>
                </div>
                <div class="modal-body text-center"  id="text-header-body">

                </div>
                <div class="modal-footer">
                    <button type="button" id="btn-eliminar-menu" name="btn-eliminar-menu" class="btn btn-danger boton_marron_carni" data-dismiss="modal" >Eliminar</button>
                    <button type="button" id="btn-cancelar" name="btn-cancelar" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <form id="guardarDatosUsuario">
        <div class="modal fade" id="dataRegister" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="exampleModalLabel">Nuevo Menu</h4>
                    </div>
                    <div class="modal-body">
                        <div id="datos_ajax_register"></div>
                                         
                        <div class="form-group" style="display: flex;">
                            <label for="label0" class="control-label encabezado-form" style="margin-left:1%; margin-top:10px; width: 15%; text-align: right;">Nivel:</label>
                            <select id="nivelAdd" style="margin-left:1%; width: 30%;" class="form-control" name="nivelAdd">
                                <option type="text" value="1" >Nivel 1</option>
                                <option type="text" value="2" >Nivel 2</option>
                                <option type="text" value="3" >Nivel 3</option>
                            </select>
                            <label for="label0" class="control-label encabezado-form" style="margin-left:1%; margin-top:10px; width: 15%; text-align: right;">Visible:</label>
                            <select id="visibleAdd" style="margin-left:1%; width: 30%;" class="form-control" name="visibleAdd">
                                <option type="text" value="1" >SI</option>
                                <option type="text" value="0" >NO</option>
                            </select>
                        </div>
                        
                        <div class="form-group" style="display: flex;">
                            <label for="label0" class="control-label encabezado-form" style="margin-left:1%; margin-top:10px; width: 15%; text-align: right;">Nombre:</label>
                            <input type="text" style="margin-left:1%;width: 77%; " class="form-control" id="descripcionAdd" name="descripcionAdd" required >
                        </div>
                        
                        <div class="form-group" style="display: flex;">
                            <label for="label0" class="control-label encabezado-form" style="margin-left:1%; margin-top:10px; width: 15%; text-align: right;">Destino:</label>
                            <input type="text" style="margin-left:1%;width: 30%; " class="form-control" id="destinoAdd" name="destinoAdd" required >
                            <label for="label0" class="control-label encabezado-form" style="margin-left:1%; margin-top:10px; width: 15%; text-align: right;">Ícono:</label>
                            <input type="text" style="margin-left:1%;width: 30%; " class="form-control" id="iconoAdd" name="iconoAdd" required >
                        </div>
                        
                        <div class="form-group dependencia_nivel" style="display: flex;">
                            <label for="label0" class="control-label encabezado-form" style="margin-left:1%; margin-top:10px; width: 15%; text-align: right;">Nivel 1:</label>
                            <select id="nivelesAdd" style="margin-left:1%; width: 77%;" class="form-control" name="nivelesAdd" >
                                <?php foreach ($niveles as $aux) { ?>
                                    <option class="nivelAdd" value="<?php echo $aux["codigo"]; ?>" nivel="<?php echo $aux["nivel"]; ?>" subnivel="<?php echo $aux["subnivel"]; ?>" ><?php echo $aux["nombre"]; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        
                        <div class="form-group dependencia_subnivel" style="display: flex;">
                            <label for="label0" class="control-label encabezado-form" style="margin-left:1%; margin-top:10px; width: 15%; text-align: right;">Nivel 2:</label>
                            <select id="subnivelesAdd" style="margin-left:1%; width: 77%;" class="form-control" name="subnivelesAdd" >
                                <?php foreach ($subniveles as $aux) { ?>
                                    <option class="subnivelAdd" value="<?php echo $aux["codigo"]; ?>" nivel="<?php echo $aux["nivel"]; ?>" subnivel="<?php echo $aux["subnivel"]; ?>" ><?php echo $aux["nombre"]; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        
                        <div class="form-group " style="display: flex;">
                            <label for="label0" class="control-label encabezado-form" style="margin-left:1%; margin-top:10px; width: 15%; text-align: right;">Después de:</label>
                            <select id="ordenAdd" style="margin-left:1%; width: 77%;" class="form-control" name="ordenAdd" >
                                <?php foreach ($opciones as $aux) { ?>
                                    <option class="opcionAdd" value="<?php echo $aux["codigo"]; ?>" nivel="<?php echo $aux["nivel"]; ?>" subnivel="<?php echo $aux["subnivel"]; ?>" ><?php echo $aux["nombre"]; ?></option>
                                <?php } ?>
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

    <form id="actualidarDatosUsuario">
        <div class="modal fade" id="dataUpdate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="exampleModalLabel">Modificar Menu</h4>
                    </div>
                    <div class="modal-body">
                        <div id="datos_ajax_register"></div>
                                         
                        <div class="form-group" style="display: flex;">
                            <label for="label0" class="control-label encabezado-form" style="margin-left:1%; margin-top:10px; width: 15%; text-align: right;">Nivel:</label>
                            <select id="nivelUpdate" style="margin-left:1%; width: 30%;" class="form-control" name="nivelUpdate">
                                <option type="text" value="1" >Nivel 1</option>
                                <option type="text" value="2" >Nivel 2</option>
                                <option type="text" value="3" >Nivel 3</option>
                            </select>
                            <label for="label0" class="control-label encabezado-form" style="margin-left:1%; margin-top:10px; width: 15%; text-align: right;">Visible:</label>
                            <select id="visibleUpdate" style="margin-left:1%; width: 30%;" class="form-control" name="visibleUpdate">
                                <option type="text" value="1" >SI</option>
                                <option type="text" value="0" >NO</option>
                            </select>
                        </div>
                        
                        <div class="form-group" style="display: flex;">
                            <label for="label0" class="control-label encabezado-form" style="margin-left:1%; margin-top:10px; width: 15%; text-align: right;">Nombre:</label>
                            <input type="text" style="margin-left:1%;width: 77%; " class="form-control" id="descripcionUpdate" name="descripcionUpdate" required >
                        </div>
                        
                        <div class="form-group" style="display: flex;">
                            <label for="label0" class="control-label encabezado-form" style="margin-left:1%; margin-top:10px; width: 15%; text-align: right;">Destino:</label>
                            <input type="text" style="margin-left:1%;width: 30%; " class="form-control" id="destinoUpdate" name="destinoUpdate" required >
                            <label for="label0" class="control-label encabezado-form" style="margin-left:1%; margin-top:10px; width: 15%; text-align: right;">Ícono:</label>
                            <input type="text" style="margin-left:1%;width: 30%; " class="form-control" id="iconoUpdate" name="iconoUpdate" required >
                        </div>
                        
                        <div class="form-group dependencia_nivel" style="display: flex;">
                            <label for="label0" class="control-label encabezado-form" style="margin-left:1%; margin-top:10px; width: 15%; text-align: right;">Nivel 1:</label>
                            <select id="nivelesUpdate" style="margin-left:1%; width: 77%;" class="form-control" name="nivelesUpdate" >
                                <?php foreach ($niveles as $aux) { ?>
                                    <option class="nivelUpdate" value="<?php echo $aux["codigo"]; ?>" nivel="<?php echo $aux["nivel"]; ?>" subnivel="<?php echo $aux["subnivel"]; ?>" ><?php echo $aux["nombre"]; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        
                        <div class="form-group dependencia_subnivel" style="display: flex;">
                            <label for="label0" class="control-label encabezado-form" style="margin-left:1%; margin-top:10px; width: 15%; text-align: right;">Nivel 2:</label>
                            <select id="subnivelesUpdate" style="margin-left:1%; width: 77%;" class="form-control" name="subnivelesUpdate" >
                                <?php foreach ($subniveles as $aux) { ?>
                                    <option class="subnivelUpdate" value="<?php echo $aux["codigo"]; ?>" nivel="<?php echo $aux["nivel"]; ?>" subnivel="<?php echo $aux["subnivel"]; ?>" ><?php echo $aux["nombre"]; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        
                        <div class="form-group " style="display: flex;">
                            <label for="label0" class="control-label encabezado-form" style="margin-left:1%; margin-top:10px; width: 15%; text-align: right;">Después de:</label>
                            <select id="ordenUpdate" style="margin-left:1%; width: 77%;" class="form-control" name="ordenUpdate" >
                                <?php foreach ($opciones as $aux) { ?>
                                    <option class="opcionUpdate" value="<?php echo $aux["codigo"]; ?>" nivel="<?php echo $aux["nivel"]; ?>" subnivel="<?php echo $aux["subnivel"]; ?>" ><?php echo $aux["nombre"]; ?></option>
                                <?php } ?>
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
    
    
    <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12 right hidden">
        <input type="button" id="export" name="export" class="btn-danger btn-sm boton_marron_carni" style="border-radius: 10px; margin-left: 10px;" value="Exportar"/>
    </div>
    <div class="hidden-lg hidden-md">
        <br /><br />
    </div>
    
</div>

<?php if ($_SESSION["permisos"][$_SESSION['menu']]["new"]) { ?>
    <div style="bottom: 50px; right: 40px; position: fixed;">
        <button id="add" name="add" type="button" class="btn btn-danger btn-lg boton_marron_carni" style="width: 50px;border-radius: 50%; text-align: center; background-color: transparent;"><i style="margin-bottom: 5px;" class="glyphicon glyphicon-plus"></i></button>
    </div>
<?php } ?>

<?php

include 'inc/html/footer.php';

?>

<script type="text/javascript" src="inc/js/menus_js.js?version=<?php echo date("Y-m-d H:i:s"); ?>"></script>
<script type="text/javascript" src="inc/js/utils.js"></script>
<script type="text/javascript" src="inc/js/jquery.table2excel.js"></script>
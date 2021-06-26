<?php

session_start();

include $_SERVER['DOCUMENT_ROOT'] . "/Giuliani/controller/usuarios.controller.php";
$controlador    = UsuariosController::singleton_usuarios();
$destinos       = $controlador->getDestinos();
$usuarios       = $controlador->getUsuarios();

$_SESSION["totales"] = $controlador->getCountUsuarios();

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

$cod_usuario = 0;
if (isset($_GET['cod_usuario'])) {
    $cod_usuario = $_GET['cod_usuario'];
} 

// Fin Recepcion parametros PAGINACION /***************************************/

$_SESSION['menu'] = basename(__FILE__, '.php') . ".php";

$_SESSION['breadcrumb'] = "Usuarios";

$titlepage = "Giuliani - Usuarios";

include 'inc/html/encabezado.php';

include 'inc/html/menu.php';

include 'inc/html/breadcrumb.php';

?>


<div class="container" >

    <div id="loading" class="loading"></div>
    
    <div id="modulo_paginacion">
        <?php include 'inc/html/paginacion_usuarios.php'; ?>
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

    <div class="hidden-lg hidden-md">
        <br /><br />
    </div>
    
</div>
<?php if ($_SESSION["permisos"][$_SESSION['menu']]["new"]) { ?>
    <div style="bottom: 50px; right: 40px; position: fixed;">
        <button id="add" name="add" type="button" class="btn btn-danger btn-lg boton_marron_carni" style="width: 50px;border-radius: 50%; text-align: center; background-color: transparent;"><i style="margin-bottom: 5px; color: #FF6700;" class="glyphicon glyphicon-plus"></i></button>
    </div>
<?php } ?>

<?php

include 'inc/html/footer.php';

?>

<script type="text/javascript" src="inc/js/usuarios_destinos_js.js?version=<?php echo date("Y-m-d H:i:s"); ?>"></script>
<script type="text/javascript" src="inc/js/utils.js"></script>
<script type="text/javascript" src="inc/js/jquery.table2excel.js"></script>
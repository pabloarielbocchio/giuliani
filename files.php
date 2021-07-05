<?php

session_start();

//if (!($_SESSION["permisos"][basename(__FILE__, '.php') . ".php"]["access"])) {
//    header("Location: login.php");
//}

include $_SERVER['DOCUMENT_ROOT'] . "/Giuliani/controller/productos.controller.php";
$controlador = ProductosController::singleton_productos();
$prod_a = $controlador->getProductosA();
$prod_b = $controlador->getProductosB();
$prod_c = $controlador->getProductosC();
$prod_d = $controlador->getProductosD();
$prod_e = $controlador->getProductosE();
$prod_f = $controlador->getProductosF();

$_SESSION["totales"] = $controlador->getCountProductos();

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
        $cant_reg = -1;
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

$_SESSION['menu'] = "productos.php";

$_SESSION['breadcrumb'] = "Productos";

$titlepage = "Productos";

include 'inc/html/encabezado.php';

include 'inc/html/menu.php';

include 'inc/html/breadcrumb.php';


$cod_nivel_1 = intval($_SESSION['n1']);
$cod_nivel_2 = intval($_SESSION['n2']);
$cod_nivel_3 = intval($_SESSION['n3']);
$cod_nivel_4 = intval($_SESSION['n4']);

?>


<div class="container"
    cod_nivel_1="<?php echo $cod_nivel_1; ?>" 
    cod_nivel_2="<?php echo $cod_nivel_2; ?>" 
    cod_nivel_3="<?php echo $cod_nivel_3; ?>" 
    cod_nivel_4="<?php echo $cod_nivel_4; ?>" 
    opc="<?php echo intval($_GET["opc"]); ?>" 
    grupo="<?php echo intval($_GET["grupo"]); ?>" 
>

    <div id="loading" class="loading"></div>
    
    <div id="modulo_paginacion" style="display: none;">
        <?php include 'inc/html/paginacion_archivos.php'; ?>
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
    
</div>


<?php

include 'inc/html/footer.php';

?>

<script type="text/javascript" src="inc/js/files_js.js?version=<?php echo date("Y-m-d H:i:s"); ?>"></script>
<script type="text/javascript" src="inc/js/utils.js"></script>
<script type="text/javascript" src="inc/js/jquery.table2excel.js"></script>
<?php

session_start();

if (!($_SESSION["permisos"][basename(__FILE__, '.php') . ".php"]["access"])) {
    header("Location: inicio.php");
}

include $_SERVER['DOCUMENT_ROOT'] . "/Giuliani/controller/usuarios.controller.php";
$controlador    = UsuariosController::singleton_usuarios();
$destinos       = $controlador->getDestinos();
$roles          = $controlador->getRoles();
$prioridad = 0;
foreach ($roles as $aux) { 
    if ($_SESSION["rol"] == $aux["id"]){
        $prioridad = $aux["prioridad"];
        break;
    }
}

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
                    <button type="button" id="btn-eliminar-usuario" name="btn-eliminar-usuario" class="btn btn-danger boton_marron_carni" data-dismiss="modal" >Eliminar</button>
                    <button type="button" id="btn-cancelar" name="btn-cancelar" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <form id="guardarDatosUsuario">
        <div class="modal fade" id="dataRegister" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="exampleModalLabel">Nuevo Usuario</h4>
                    </div>
                    <div class="modal-body">
                        <div id="datos_ajax_register"></div>
                        <div class="form-group">
                            <label for="nombre0" class="control-label">Nombre de Usuario:</label>
                            <input type="text" class="form-control" id="usuarioAdd" name="usuarioAdd" required maxlength="100">
                        </div>
                        <div class="form-group">
                            <label for="nombre0" class="control-label">Password:</label>
                            <input type="password" class="form-control" id="passwordAdd" name="passwordAdd" required maxlength="100">
                        </div>                        
                        <div class="form-group">
                            <label for="nombre0" class="control-label">Nombre:</label>
                            <input type="text" class="form-control" id="nombreAdd" name="nombreAdd" required maxlength="100">
                        </div>
                        <div class="form-group">
                            <label for="nombre0" class="control-label">Apellido:</label>
                            <input type="text" class="form-control" id="apellidoAdd" name="apellidoAdd" required maxlength="100">
                        </div>                        
                        <div class="form-group">
                            <label for="nombre0" class="control-label">Mail:</label>
                            <input type="text" class="form-control" id="mailAdd" name="mailAdd" required maxlength="100">
                        </div>                        
                        <div class="form-group"> 
                            <label for="nombre0" class="control-label">Rol:</label>
                            <select id="rolAdd" style="width: 100%;" class="form-control" name="rolAdd"  required>
                                <?php 
                                    foreach ($roles as $aux) { 
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

    <form id="actualidarDatosUsuario">
        <div class="modal fade" id="dataUpdate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="exampleModalLabel">Modificar Usuario</h4>
                    </div>
                    <div class="modal-body">
                        <div id="datos_ajax_register"></div>
                        <div class="form-group">
                            <label for="nombre0" class="control-label">Nombre de Usuario:</label>
                            <input type="text" class="form-control" id="usuarioUpdate" name="usuarioUpdate" readonly maxlength="100">
                        </div>
                        <div class="form-group">
                            <label for="nombre0" class="control-label">Password (Debe ingresar un nuevo password):</label>
                            <input type="password" class="form-control" id="passwordUpdate" name="passwordUpdate" required maxlength="100">
                        </div>
                        <div class="form-group">
                            <label for="nombre0" class="control-label">Nombre:</label>
                            <input type="text" class="form-control" id="nombreUpdate" name="nombreUpdate" required maxlength="100">
                        </div>
                        <div class="form-group">
                            <label for="nombre0" class="control-label">Apellido:</label>
                            <input type="text" class="form-control" id="apellidoUpdate" name="apellidoUpdate" required maxlength="100">
                        </div>                  
                        <div class="form-group">
                            <label for="nombre0" class="control-label">Mail:</label>
                            <input type="text" class="form-control" id="mailUpdate" name="mailUpdate" required maxlength="100">
                        </div>   
                        <div class="form-group"> 
                            <label for="nombre0" class="control-label">Rol:</label>
                            <select id="rolUpdate" style="width: 100%;" class="form-control" name="rolUpdate"  required>
                                <?php 
                                    foreach ($roles as $aux) { 
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
<?php if ($_SESSION["permisos"][$_SESSION['menu']]["new"]) { ?>
    <div style="bottom: 50px; right: 40px; position: fixed;">
        <button id="add" name="add" type="button" class="btn btn-danger btn-lg boton_marron_carni" style="width: 50px;border-radius: 50%; text-align: center; background-color: transparent;"><i style="margin-bottom: 5px; color: #FF6700;" class="glyphicon glyphicon-plus"></i></button>
    </div>
<?php } ?>

<?php

include 'inc/html/footer.php';

?>

<script type="text/javascript" src="inc/js/usuarios_js.js?version=<?php echo date("Y-m-d H:i:s"); ?>"></script>
<script type="text/javascript" src="inc/js/utils.js"></script>
<script type="text/javascript" src="inc/js/jquery.table2excel.js"></script>
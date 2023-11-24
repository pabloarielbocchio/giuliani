<?php

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT);
session_start();

if (!isset($_SESSION["usuario"]) || !isset($_SESSION['password'])) {
    header("Location: login.php");
}


include_once $_SERVER['DOCUMENT_ROOT'] . "/Giuliani/controller/index.controller.php";
$controlador_       = IndexController::singleton_index();
$menu_              = $controlador_->getMenu();
$menu_user_login    = $controlador_->getMenuUser($_SESSION["user_id"]);                                
$menu_user_roles    = $controlador_->getMenuRoles($_SESSION["rol"]);                                   
$menu_user_roles_id = $controlador_->getMenuRolesId($_SESSION["user_id"]);                                
$menu_user_destinos = $controlador_->getMenuDestinos($_SESSION["rol"]);                                

$favicon            = "imagenes/favicon.ico";
$bootstrap          = "inc/bootstrap/css/bootstrap.css";
$estilos            = "inc/css/asistencias_css.css";
$imagen             = "imagenes/logo alargado-05.png";
$chartist           = "inc/chartist/chartist.css";
$menu               = array();

$_SESSION["permisos_globales"][$_SESSION["menu"]] = 0;
//if ($_SESSION["sistemas"] != 1 or $_SESSION["rol"] != 1){
if ($_SESSION["rol"] != 1){
    foreach($menu_user_roles_id as $rm){
        if ($rm["menu_desc"] == $_SESSION["menu"]){
            $_SESSION["permisos_globales"][$_SESSION["menu"]] = $rm["permiso"];
        }
    }
} else {
    $_SESSION["permisos_globales"][$_SESSION["menu"]] = 3;
}

foreach ($menu_ as $me){
    $prohibido = 1;
    if ($_SESSION["sistemas"] != 1){
        foreach ($menu_user_login as $du){
            if ($me["codigo"] == $du["cod_menu"] and $du["cod_usuario"] == $_SESSION["user_id"]){
                if($du["permiso"] == 1){
                    $prohibido = 0;
                }
            }
        }
    } else {
        $prohibido = 0;
    }
    if ($prohibido == 1){
        continue;
    }    

    $prohibido = 0;
    foreach($menu_user_roles as $rm){
        if ($rm["menu_id"] == $me["codigo"]){
            if ($rm["permiso"] == 0) {
                $prohibido = 1;
            }
        }
    }

    if ($prohibido == 1){
        continue;
    }    

    switch ($me["nivel"]) {
        case 0:
            $me["nombre"] = strtoupper($me["nombre"]);
            break;
        default:
            if ($me["destino"] == "#"){
                $me["nombre"] = strtoupper($me["nombre"]);
            }
            break;
    }
    $aux    = [$me["nombre"], $me["destino"],$me["nivel"],$me["icono"],$me["subnivel"]];

    /**
     * Menus
     * 1 - Inicio
     * 2 - Usuarios
     * 3 - Cerrar
     * 4 - Configuraciones
     * 34 - Productos
     * 35 - Planos
     * 36 - OT
     * 37 - Monitoreo
     * 43 - Eventos
     */
    /*
    switch ($_SESSION["rol"]) {
        case 8: //Planificacion
            if (in_array($me["codigo"], [2,4,43])){
                $prohibido = 1;
            }
            break;
        case 7: //Logistica
            if (in_array($me["codigo"], [2,4,43])){
                $prohibido = 1;
            }
            break;
        case 6: //Compras
            if (in_array($me["codigo"], [2,4,43])){
                $prohibido = 1;
            }
            break;
        case 5: //Ingenieria
            if (in_array($me["codigo"], [2,4,43])){
                $prohibido = 1;
            }
            break;
        case 4: //Produccion
            if (in_array($me["codigo"], [2,4,43])){
                $prohibido = 1;
            }
            break;
        case 3: //Calidad
            if (in_array($me["codigo"], [2,4,43])){
                $prohibido = 1;
            }
            break;
        case 2: //Gerencial
            if (in_array($me["codigo"], [-1])){
                $prohibido = 1;
            }
            break;
        case 1: //Administrador
            if (in_array($me["codigo"], [-1])){
                $prohibido = 1;
            }
            break;
        default:
            break;
    }
    if ($prohibido == 1){
        continue;
    }   
    */
    $menu[] = $aux;
}
/*
$_home              = ["HOME", "inicio.php",0,"fa-home"];
$_usuarios          = ["USUARIOS", "usuarios.php",0,"fa-address-card"];
$_salir             = ["SALIR", "cerrar.php", 0, "fa-sign-out"];    
$menu[] = $_home;
if ($_SESSION["cargo"] == "Administrador") {
    $menu[] = $_usuarios;
}
$menu[] = $_salir;
*/

?>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<html>
    <head>
        <link href="<?php echo $favicon; ?>" rel="icon" type="image/png"/>
        <link href="<?php echo $estilos; ?>" rel="stylesheet">
        <title><?php echo $titlepage; ?></title>   
        <link href="inc/bootstrap/css/select2.min.css" rel="stylesheet">
        <link href="inspinia/css/bootstrap.min.css" rel="stylesheet">
        <link href="inspinia/font-awesome/css/font-awesome.css" rel="stylesheet">
        <link href="inspinia/css/animate.css" rel="stylesheet">
        <link href="inspinia/css/style.css" rel="stylesheet">
        <link href="inspinia/css/plugins/select2/select2.min.css" rel="stylesheet">	
        <link href="inspinia/css/plugins/iCheck/custom.css" rel="stylesheet">	
        <link href="inspinia/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">
    </head>
    <body style="background-color: #EEEEEE;"> 
        <audio id="audio" src="audio/audio.mp3"></audio>
        <div class="modal fade" id="helpModal" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 id="name-header-modal" class="modal-title">AYUDA</h4>
                    </div>
                    <div class="modal-body text-left"  id="text-header-body-help">
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btn-cancelar" name="btn-cancelar" class="btn btn-default" data-dismiss="modal">Entendido!</button>
                    </div>
                </div>
            </div>
        </div>   
        
		<div id="wrapper">
			<nav class="navbar-default navbar-static-side" role="navigation">
                                <div class="sidebar-collapse">
					<ul class="nav metismenu" id="side-menu">
						<li class="nav-header">
							<div class="dropdown profile-element"> 
								<span>
                                                                    <img alt="image" height="40px;" width="40px;" class="img-circle" src="<?php echo "imagenes/favicon.ico"; ?>" />
								</span>
								<a data-toggle="dropdown" class="dropdown-toggle" href="#">
									<span class="clear"> <span class="block m-t-xs"> <strong class="font-bold"><?php echo $_SESSION["nombre"] . " " . $_SESSION["apellido"]; ?></strong>
									 </span> <span class="text-muted text-xs block"><?php echo $_SESSION["usuario"]; ?> <b class="caret"></b></span> </span> 
								 </a>
								<ul class="dropdown-menu animated fadeInRight m-t-xs">
									<li><a href="cerrar.php">Salir</a></li>
								</ul>
							</div>
							<div class="logo-element">
                                                            <img alt="image" height="30px;" width="30px;" class="img-circle" src="<?php echo "imagenes/favicon.ico"; ?>" />
							</div>
						</li>					
						<?php
						foreach ($menu as $m) {
							if ($m[2] == 0){
								if ($m[1] == $_SESSION['menu']) {
									echo '<li data-toggle="tooltip" title="' . $m[0] . '" class="active"><a href="' . $m[1] . '"><i class="fa ' . $m[3] . '"></i><span class="nav-label"><b>' . $m[0] . '</b></span></a></li>';
								} else {
									echo '<li data-toggle="tooltip" title="' . $m[0] . '" ><a href="' . $m[1] . '"><i class="fa ' . $m[3] . '"></i><span class="nav-label"><b>' . $m[0] . '</b></span></a></li>';
								}
							} 
							if ($m[2] > 0){
                                                                echo '<li><a data-toggle="tooltip" title="' . $m[0] . '" href="#"><i class="fa ' . $m[3] . '"></i><span class="nav-label"><b>' . $m[0] . '</b></span><span class="fa arrow"></span></a>';
                                                                echo '<ul class="nav nav-second-level">';
                                                                foreach ($menu as $men) {
                                                                        if ($men[2] * -1 == $m[2]){
                                                                                if ($men[4] > 0){
                                                                                    echo '<li>';
                                                                                    echo '<a href="#"><b>' . $men[0] . '</b> <span class="fa arrow"></span></a>';
                                                                                    echo '<ul class="nav nav-third-level">';
                                                                                    foreach ($menu as $_m) {
                                                                                        if ($men[4] * -1 == $_m[4]){
                                                                                            echo '<li>';
                                                                                            echo '<a href="' . $_m[1] . '"><b>' . $_m[0] . '</b></a>';
                                                                                            echo '</li>';
                                                                                        }
                                                                                    }    
                                                                                    echo '</ul>';
                                                                                    echo '</li>';
                                                                                } elseif ($men[4] < 0){

                                                                                } else {
                                                                                    if ($men[1] == $_SESSION['menu']) {
                                                                                            echo '<li class="active"><a href="' . $men[1] . '"><b>' . $men[0] . '</b></a></li>';
                                                                                    } else {
                                                                                            echo '<li><a href="' . $men[1] . '"><b>' . $men[0] . '</b></a></li>';
                                                                                    }
                                                                                }
                                                                        }
                                                                }
                                                                echo '</ul>';
                                                                echo '</li>';
							}
						}
						?>
					</ul>

				</div>
			</nav>

			<div id="page-wrapper" class="gray-bg">
				<div class="row border-bottom">
					<nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
						<div class="navbar-header">
							<a class="navbar-minimalize minimalize-styl-2 btn btn-primary boton_marron_carni" href="#"><i class="fa fa-bars"></i> </a>
							<form role="search" class="navbar-form-custom" >
                                                                <div class="form-group" style="width: 250%;">
                                                                    <input style="cursor: pointer;" type="text" placeholder="Search for something..." class="form-control" name="top-search" id="top-search" value="<?php echo $_SESSION["breadcrumb"]; ?>" disabled>
								</div>
							</form>								
						</div>
						<ul class="nav navbar-top-links navbar-right">
                                                        <li id="alertas_notificaciones">
                                                            <a class="dropdown-toggle count-info" data-toggle="" href="#">
                                                                <i class="fa fa-bell"></i> <?php echo '<span style="display: none;" class="label label-primary bell_nuevos_pedidos">' . count($nuevos_pedidos) . '</span>';  ?>
                                                            </a>
                                                        </li> 
							<li class="<?php if ($_SESSION["last_cliente"] > 0){ echo "switchable"; }?>">
                                                                <span class="m-r-sm text-muted welcome-message">
                                                                    <?php echo strtoupper($_SESSION["ultimo_cliente"]); ?>
                                                                </span>
							</li>
							<li>
								<a href="cerrar.php">
									<i class="fa fa-sign-out"></i> Salir
								</a>
							</li>
						</ul>
                                            

					</nav>
				</div>
                                <br />
                                
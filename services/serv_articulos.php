<?php

session_start();
unset($_SESSION["JSON_API"]);
unset($_SESSION["JSON"]);

if (!isset($_POST['funcion'])){
    $_POST = $_GET;
}

$_SESSION["JSON_API"]   = true;

include $_SERVER['DOCUMENT_ROOT'] . "/Giuliani/controller/utils.controller.php";
$controlador_utils      = UtilsController::singleton_utils();
$cliente                = $controlador_utils->getCliente($_POST["cliente_id"]);
if ($cliente){
    $id_usuariowp = $cliente["id_usuariowp"];
    $_SESSION["db"] = "_" . $id_usuariowp;
}

include $_SERVER['DOCUMENT_ROOT'] . "/Giuliani/controller/index.controller.php";
$controlador_index      = IndexController::singleton_index();
$logueo                 = $controlador_index->logueoEncryptClient($_POST["usuario"], $_POST["pass"]);

if ($logueo == 0){
    
    $_SESSION["user_id"]    = 99;
    include $_SERVER['DOCUMENT_ROOT'] . "/Giuliani/controller/articulos.controller.php";
    $controlador            = ArticulosController::singleton_articulos();

    /*if (isset($_POST['funcion'])) {
        if (function_exists($_POST['funcion'])) {
            $_POST['funcion']();
        }
    }*/

    echo json_encode($_SESSION["JSON"]);

}

session_destroy();
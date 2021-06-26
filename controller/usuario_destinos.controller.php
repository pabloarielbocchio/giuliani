<?php

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT);

session_start();

if (isset($_POST['funcion'])) {
    if (function_exists($_POST['funcion'])) {
        $_POST['funcion']();
    }
}

function getCountUsuario_destinos(){
    $controlador = Usuario_destinosController::singleton_usuario_destinos();
    $devuelve = $controlador->getCountUsuario_destinos();
    if (!$_POST["json"]){
        echo $devuelve;
    } 
}

function getRegistrosFiltro(){
    $controlador = Usuario_destinosController::singleton_usuario_destinos();
    $devuelve = $controlador->getRegistrosFiltro($_POST['orderby'], $_POST['sentido'], $_POST['registros'], $_POST['pagina'], $_POST['busqueda']);
    if (!$_POST["json"]){
        echo $devuelve;
    } 
}

function addUsuario_destino() {
    $controlador = Usuario_destinosController::singleton_usuario_destinos();
    $devuelve = $controlador->addUsuario_destino  (    
        $_POST['usuario'], 
        $_POST['destino']
    );
    if (!$_POST["json"]){
        echo $devuelve;
    } 
}

function updateUsuario_destino() {
    $controlador = Usuario_destinosController::singleton_usuario_destinos();
    $devuelve = $controlador->updateUsuario_destino(   
        $_POST['codigo'], 
        $_POST['usuario'], 
        $_POST['destino']
    );
    if (!$_POST["json"]){
        echo $devuelve;
    } 
}

function deleteUsuario_destino() {
    $controlador = Usuario_destinosController::singleton_usuario_destinos();    
    $devuelve = $controlador->deleteUsuario_destino(
        $_POST['codigo']
    );
    if (!$_POST["json"]){
        echo $devuelve;
    } 
}

function getUsuario_destino() {
    $controlador = Usuario_destinosController::singleton_usuario_destinos();
    $devuelve = $controlador->getUsuario_destino(
        $_POST['codigo']
    );
    if (!$_POST["json"]){
        echo $devuelve;
    } 
}

class Usuario_destinosController {

    public static $utils;
    private static $instancia;
    private $conn;

    public function __construct() {
        try {
            include $_SERVER['DOCUMENT_ROOT']."/Giuliani/model/usuario_destinos.model.php";
            $this->conn = Usuario_destinosModel::singleton_usuario_destinos();
        } catch (Exception $e) {
            $error = "Error!: " . $e->getMessage();
            return $error;
        }
    }

    public static function singleton_usuario_destinos() {
        if (!isset(self::$instancia)) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        include_once $_SERVER['DOCUMENT_ROOT']."/Giuliani/controller/utils.controller.php";
        self::$instancia->utils= UtilsController::singleton_utils();
        return self::$instancia;
    }
    
    public function getCountUsuario_destinos(){
        return intval($this->conn->getCountUsuario_destinos()[0]);
    }
    
    public function getRegistrosFiltro($orderby, $sentido, $registros, $pagina, $busqueda){
        $this->utils->newController($_SERVER["REQUEST_URI"], $_SESSION['menu']);
        $this->utils->newFunction(__FUNCTION__, $_SERVER["REQUEST_URI"]);            
        $_SESSION["pagina"] = $pagina;        
        $_SESSION["cant_reg"] = $registros;        
        $_SESSION["busqueda"] = $busqueda;
        $_SESSION['orderby'] = $orderby;
        $_SESSION['sentido'] = $sentido;        
        $usuarios      = $this->getUsuarios();
        $destinos      = $this->getDestinos();
        $devuelve = $this->conn->getRegistrosFiltro($orderby, $sentido, $registros, $pagina, $busqueda);        
        $prioridad = 0;
        $registros = $devuelve;        
        $_SESSION['registros'] = $registros;
        if (!$_SESSION["JSON_API"]){
            include $_SERVER['DOCUMENT_ROOT']."/Giuliani/templates/usuario_destinos.busqueda.template.php";        
        }
    }
    
    public function addUsuario_destino($usuario, $destino) {
        $this->utils->newController($_SERVER["REQUEST_URI"], $_SESSION['menu']);
        $this->utils->newFunction(__FUNCTION__, $_SERVER["REQUEST_URI"]);          
        $devuelve = $this->conn->addUsuario_destino($usuario, $destino);        
        return $devuelve;
    }
    
    public function updateUsuario_destino($codigo, $usuario, $destino) {
        $this->utils->newController($_SERVER["REQUEST_URI"], $_SESSION['menu']);
        $this->utils->newFunction(__FUNCTION__, $_SERVER["REQUEST_URI"]);    
        $devuelve = $this->conn->updateUsuario_destino($codigo,$usuario, $destino);        
        return $devuelve;
    }
    
    public function deleteUsuario_destino($codigo) {
        $this->utils->newController($_SERVER["REQUEST_URI"], $_SESSION['menu']);
        $this->utils->newFunction(__FUNCTION__, $_SERVER["REQUEST_URI"]);     
        $devuelve = $this->conn->deleteUsuario_destino($codigo);       
        return $devuelve;
    }
    
    public function getUsuario_destino($codigo) {
        $this->utils->newController($_SERVER["REQUEST_URI"], $_SESSION['menu']);
        $this->utils->newFunction(__FUNCTION__, $_SERVER["REQUEST_URI"]);    
        $devuelve = $this->conn->getUsuario_destino($codigo);        
        return json_encode($devuelve[0]);        
    }
    
    public function getUsuarios() {
        $devuelve = $this->conn->getUsuarios();
        return $devuelve;        
    }
    
    public function getDestinos() {
        $devuelve = $this->conn->getDestinos();
        return $devuelve;        
    }
    
}

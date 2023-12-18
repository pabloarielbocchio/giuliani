<?php

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT);

session_start();

if (isset($_POST['funcion'])) {
    if (function_exists($_POST['funcion'])) {
        $_POST['funcion']();
    }
}

function logueo() {
    $controlador = IndexController::singleton_index();    
    echo $controlador->logueo($_POST['nombre'], $_POST['password']);
}

function updateFrase() {
    $controlador = IndexController::singleton_index();    
    echo $controlador->updateFrase($_POST['textarea']);
}

function updateBell() {
    $controlador = IndexController::singleton_index();    
    echo $controlador->updateBell();
}

class IndexController {

    public static $utils;
    private static $instancia;
    private $conn;

    public function __construct() {
        try {
            include_once $_SERVER['DOCUMENT_ROOT']."/Giuliani/model/index.model.php";
            $this->conn = IndexModel::singleton_index();
        } catch (Exception $e) {
            $error = "Error!: " . $e->getMessage();
            return $error;
        }
    }

    public static function singleton_index() {
        if (!isset(self::$instancia)) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        include_once $_SERVER['DOCUMENT_ROOT']."/Giuliani/controller/utils.controller.php";
        self::$instancia->utils= UtilsController::singleton_utils();
        return self::$instancia;
    }
    
    public function logueo($user, $pass){
        $devuelve = $this->utils->logueo($user, $pass);        
        return $devuelve;
    }
    
    public function logueoEncrypt($user, $pass){
        return $this->conn->logueoEncrypt($user, $pass);        
    }
    
    public function logueoEncryptClient($user, $pass){
        $devuelve = $this->utils->logueoEncrypt($user, $pass, $_SESSION["cliente_id"]);        
        if ($devuelve == 0){
            $this->conn->getSucursales();
        }
        return $devuelve;
    }
    
    public function getMenu(){
        return $this->utils->getMenu();
    }
    
    public function getMenuUser($user){
        return $this->utils->getMenuUser($user);
    }
    
    public function getMenuRoles($rol){
        return $this->conn->getMenuRoles($rol);
    }
    
    public function getMenuRolesId($rol){
        return $this->conn->getMenuRolesId($rol);
    }
    
    public function getMenuDestinos($rol){
        return $this->conn->getMenuDestinos($rol);
    }
    
    public function updateFrase($textarea){
        $this->conn->updateFrase($textarea);
        return 0;
    }
    
    public function updateBell(){
        return $this->conn->updateBell();
    }
    
    public function getAlertasStock(){
        $devuelve               = $this->conn->getProductos();                                
        foreach ($devuelve as $pos => $dev){
            $dev["stock"] = 0;
            $dev["stock"] += floatval($dev["inicial"]);
            $dev["stock"] += floatval($dev["producido"]);
            $dev["stock"] -= floatval($dev["vendido"]);
            $ventas = $this->conn->getVentas($dev["codigo"]);
            $ajustes = $this->conn->getAjustes($dev["codigo"]);
            foreach ($ventas as $p => $d){
                $dev["stock"] -= floatval($d["cantidad"]);
            }
            foreach ($ajustes as $p => $d){
                $dev["stock"] += floatval($d["cantidad"] * $d["impacto"]);
            }
            $devuelve[$pos] = $dev;
        }
        $registros                  = $devuelve;        
        return $registros;
    }

    public function getDisponibleCheques(){
        return $this->conn->getDisponibleCheques()[0]["disponible"];
    }

    public function getDisponibleBancos(){
        return $this->conn->getDisponibleBancos()[0]["disponible"];
    }

    public function getDisponibleCaja(){
        return $this->conn->getDisponibleCaja()[0]["disponible"];
    }

    public function getCtasCtesClientes(){
        return $this->conn->getCtasCtesClientes();
    }

    public function getCtasCtesProv(){
        return $this->conn->getCtasCtesProv();
    }

    public function getTextoInicio(){
        return $this->conn->getTextoInicio()[0]["valor"];
    }

    public function getArchivo($archivo_id){
        return $this->conn->getArchivo($archivo_id)[0];
    }

    public function getArchivoDestinos($archivo_id){
        return $this->conn->getArchivoDestinos($archivo_id);
    }
}

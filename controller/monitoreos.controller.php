<?php

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT);

session_start();

if (isset($_POST['funcion'])) {
    if (function_exists($_POST['funcion'])) {
        $_POST['funcion']();
    }
}

function getCountMonitoreos(){
    $controlador = MonitoreosController::singleton_monitoreos();   
    $devuelve = $controlador->getCountMonitoreos();
    if (!$_POST["json"]){
        echo $devuelve;
    } 
}

function getRegistrosFiltro(){
    $controlador = MonitoreosController::singleton_monitoreos();    
    $devuelve = $controlador->getRegistrosFiltro(
        $_POST['orderby'], 
        $_POST['sentido'], 
        $_POST['registros'], 
        $_POST['pagina'], 
        $_POST['busqueda'],
        $_POST['rol']
    );
    if (!$_POST["json"]){
        echo $devuelve;
    } 
}

function addMonitoreos() {
    $controlador = MonitoreosController::singleton_monitoreos();    
    $devuelve = $controlador->addMonitoreos  (   
        $_POST['descripcion'],
        $_POST['nivel'],
        $_POST['niveles'],
        $_POST['subniveles'],
        $_POST['visible'],
        $_POST['destino'],
        $_POST['icono'],
        $_POST['orden']            
    );
    if (!$_POST["json"]){
        echo $devuelve;
    } 
}

function updateMonitoreos() {
    $controlador = MonitoreosController::singleton_monitoreos();    
    $devuelve = $controlador->updateMonitoreos(  
        $_POST['codigo'],
        $_POST['fecha_modif'], 
        $_POST['descripcion'],
        $_POST['nivel'],
        $_POST['niveles'],
        $_POST['subniveles'],
        $_POST['visible'],
        $_POST['destino'],
        $_POST['icono'],
        $_POST['orden']     
    );
    if (!$_POST["json"]){
        echo $devuelve;
    } 
}

function deleteMonitoreos() {
    $controlador = MonitoreosController::singleton_monitoreos();    
    $devuelve = $controlador->deleteMonitoreos(
        $_POST['codigo']
    );
    if (!$_POST["json"]){
        echo $devuelve;
    } 
}

function getMonitoreos() {
    $controlador = MonitoreosController::singleton_monitoreos();    
    $devuelve = $controlador->getMonitoreos(
        $_POST['codigo']
    );
    if (!$_POST["json"]){
        echo $devuelve;
    }
}

function cambiar_estadoRolMonitoreo() {
    $controlador = MonitoreosController::singleton_monitoreos();    
    $devuelve = $controlador->cambiar_estadoRolMonitoreo(
        $_POST['codigo'],
        $_POST['estado'],
        $_POST['rol']
    );
    if (!$_POST["json"]){
        echo $devuelve;
    }
}

class MonitoreosController {

    public static $utils;
    private static $instancia;
    private $conn;

    public function __construct() {
        try {
            include $_SERVER['DOCUMENT_ROOT']."/Giuliani/model/monitoreos.model.php";
            $this->conn = MonitoreosModel::singleton_monitoreos();
        } catch (Exception $e) {
            $error = "Error!: " . $e->getMessage();
            return $error;
        }
    }

    public static function singleton_monitoreos() {
        if (!isset(self::$instancia)) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        include_once $_SERVER['DOCUMENT_ROOT']."/Giuliani/controller/utils.controller.php";
        self::$instancia->utils= UtilsController::singleton_utils();
        return self::$instancia;
    }
    
    public function getCountMonitoreos(){
        return intval($this->conn->getCountMonitoreos()[0]);        
    }
    
    public function getRegistrosFiltro($orderby, $sentido, $registros, $pagina, $busqueda, $rol){    
        $this->utils->newController($_SERVER["REQUEST_URI"], $_SESSION['monitoreo']);
        $this->utils->newFunction(__FUNCTION__, $_SERVER["REQUEST_URI"]);    
        $_SESSION["pagina"]     = $pagina;        
        $_SESSION["cant_reg"]   = $registros;        
        $_SESSION["busqueda"]   = $busqueda;                
        $_SESSION['orderby']    = $orderby;        
        $_SESSION['sentido']    = $sentido;        
        $_SESSION['rol_selected']    = $rol;        
        $devuelve               = $this->conn->getRegistrosFiltro($orderby, $sentido, $registros, $pagina, $busqueda);                                
        $roles_monitoreo             = $this->conn->getRolesMonitoreos();
        $monitoreo                   = array();
        foreach ($devuelve as $key => $me){
            $permiso = 0;
            foreach($roles_monitoreo as $rm){
                if ($rm["destino_id"] == $me["codigo"]){
                    $permiso = $rm["permiso"];
                }
            }
            $devuelve[$key]["permiso"] = $permiso;
        }
        $registros              = $devuelve;        
        $_SESSION['registros']  = $registros;
        if (!$_SESSION["JSON_API"]){
            include $_SERVER['DOCUMENT_ROOT']."/Giuliani/templates/monitoreos.busqueda.template.php";        
        }
    }
    
    public function addMonitoreos($descripcion, $nivel, $niveles, $subniveles, $visible, $destino, $icono, $orden) {
        $this->utils->newController($_SERVER["REQUEST_URI"], $_SESSION['monitoreo']);
        $this->utils->newFunction(__FUNCTION__, $_SERVER["REQUEST_URI"]);    
        $devuelve = $this->conn->addMonitoreos($descripcion, $nivel, $niveles, $subniveles, $visible, $destino, $icono, $orden);        
        return $devuelve;        
    }
    
    public function updateMonitoreos($codigo, $fecha_modif, $descripcion, $nivel, $niveles, $subniveles, $visible, $destino, $icono, $orden) {
        $this->utils->newController($_SERVER["REQUEST_URI"], $_SESSION['monitoreo']);
        $this->utils->newFunction(__FUNCTION__, $_SERVER["REQUEST_URI"]);    
        $devuelve = $this->conn->updateMonitoreos($codigo, $fecha_modif, $descripcion, $nivel, $niveles, $subniveles, $visible, $destino, $icono, $orden);        
        return $devuelve;        
    }
    
    public function cambiar_estadoRolMonitoreo($codigo, $estado, $rol) {
        $this->utils->newController($_SERVER["REQUEST_URI"], $_SESSION['monitoreo']);
        $this->utils->newFunction(__FUNCTION__, $_SERVER["REQUEST_URI"]);    
        $this->conn->deleteArchivo_destino($codigo, $rol);
        $devuelve = $this->conn->cambiar_estadoRolMonitoreo($codigo, $estado, $rol);        
        return $devuelve;        
    }
    
    public function deleteMonitoreos($codigo) {
        $this->utils->newController($_SERVER["REQUEST_URI"], $_SESSION['monitoreo']);
        $this->utils->newFunction(__FUNCTION__, $_SERVER["REQUEST_URI"]);    
        $devuelve = $this->conn->deleteMonitoreos($codigo);        
        return $devuelve;        
    }
    
    public function getMonitoreos($codigo) {
        $this->utils->newController($_SERVER["REQUEST_URI"], $_SESSION['monitoreo']);
        $this->utils->newFunction(__FUNCTION__, $_SERVER["REQUEST_URI"]);    
        $devuelve = $this->conn->getMonitoreos($codigo)[0];        
        $monitoreos_all = $this->getMonitoreosAll();
        $despues_de = 0;
        $max_orden = 0;
        foreach ($monitoreos_all as $m){
            if ($m["nivel"] == $devuelve["nivel"]){
                if ($m["subnivel"] == $devuelve["subnivel"]){
                    if ($m["orden"] < $devuelve["orden"] and $m["orden"] > $max_orden){
                        $max_orden = $m["orden"];
                        $despues_de = $m["codigo"];
                    }
                }
            }
        }
        $devuelve["despues_de"] = $despues_de;
        return json_encode($devuelve);        
    }
    
    public function getMonitoreosAll() {
        $this->utils->newController($_SERVER["REQUEST_URI"], $_SESSION['monitoreo']);
        $this->utils->newFunction(__FUNCTION__, $_SERVER["REQUEST_URI"]);    
        $devuelve = $this->conn->getMonitoreosAll();        
        return $devuelve;        
    }
    
    public function getRoles() {
        $this->utils->newController($_SERVER["REQUEST_URI"], $_SESSION['monitoreo']);
        $this->utils->newFunction(__FUNCTION__, $_SERVER["REQUEST_URI"]);    
        $devuelve = $this->conn->getRoles();        
        return $devuelve;        
    }
    
}

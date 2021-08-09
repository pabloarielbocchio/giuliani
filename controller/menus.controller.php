<?php

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT);

session_start();

if (isset($_POST['funcion'])) {
    if (function_exists($_POST['funcion'])) {
        $_POST['funcion']();
    }
}

function getCountMenus(){
    $controlador = MenusController::singleton_menus();   
    $devuelve = $controlador->getCountMenus();
    if (!$_POST["json"]){
        echo $devuelve;
    } 
}

function getRegistrosFiltro(){
    $controlador = MenusController::singleton_menus();    
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

function addMenus() {
    $controlador = MenusController::singleton_menus();    
    $devuelve = $controlador->addMenus  (   
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

function updateMenus() {
    $controlador = MenusController::singleton_menus();    
    $devuelve = $controlador->updateMenus(  
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

function deleteMenus() {
    $controlador = MenusController::singleton_menus();    
    $devuelve = $controlador->deleteMenus(
        $_POST['codigo']
    );
    if (!$_POST["json"]){
        echo $devuelve;
    } 
}

function getMenus() {
    $controlador = MenusController::singleton_menus();    
    $devuelve = $controlador->getMenus(
        $_POST['codigo']
    );
    if (!$_POST["json"]){
        echo $devuelve;
    }
}

function cambiar_estadoRolMenu() {
    $controlador = MenusController::singleton_menus();    
    $devuelve = $controlador->cambiar_estadoRolMenu(
        $_POST['codigo'],
        $_POST['estado'],
        $_POST['rol']
    );
    if (!$_POST["json"]){
        echo $devuelve;
    }
}

class MenusController {

    public static $utils;
    private static $instancia;
    private $conn;

    public function __construct() {
        try {
            include $_SERVER['DOCUMENT_ROOT']."/Giuliani/model/menus.model.php";
            $this->conn = MenusModel::singleton_menus();
        } catch (Exception $e) {
            $error = "Error!: " . $e->getMessage();
            return $error;
        }
    }

    public static function singleton_menus() {
        if (!isset(self::$instancia)) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        include_once $_SERVER['DOCUMENT_ROOT']."/Giuliani/controller/utils.controller.php";
        self::$instancia->utils= UtilsController::singleton_utils();
        return self::$instancia;
    }
    
    public function getCountMenus(){
        return intval($this->conn->getCountMenus()[0]);        
    }
    
    public function getRegistrosFiltro($orderby, $sentido, $registros, $pagina, $busqueda, $rol){    
        $this->utils->newController($_SERVER["REQUEST_URI"], $_SESSION['menu']);
        $this->utils->newFunction(__FUNCTION__, $_SERVER["REQUEST_URI"]);    
        $_SESSION["pagina"]     = $pagina;        
        $_SESSION["cant_reg"]   = $registros;        
        $_SESSION["busqueda"]   = $busqueda;                
        $_SESSION['orderby']    = $orderby;        
        $_SESSION['sentido']    = $sentido;        
        $_SESSION['rol_selected']    = $rol;        
        $devuelve               = $this->conn->getRegistrosFiltro($orderby, $sentido, $registros, $pagina, $busqueda);                                
        $roles_menu             = $this->conn->getRolesMenus();
        $menu                   = array();
        foreach ($devuelve as $me){
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
            $permiso = 0;
            foreach($roles_menu as $rm){
                if ($rm["menu_id"] == $me["codigo"]){
                    $permiso = $rm["permiso"];
                }
            }
            $aux    = [$me["nombre"], $me["destino"],$me["nivel"],$me["icono"],$me["subnivel"],$me["codigo"], $permiso];
            $menu[] = $aux;
        }
        $registros              = $devuelve;        
        $_SESSION['registros']  = $registros;
        if (!$_SESSION["JSON_API"]){
            include $_SERVER['DOCUMENT_ROOT']."/Giuliani/templates/menus.busqueda.template.php";        
        }
    }
    
    public function addMenus($descripcion, $nivel, $niveles, $subniveles, $visible, $destino, $icono, $orden) {
        $this->utils->newController($_SERVER["REQUEST_URI"], $_SESSION['menu']);
        $this->utils->newFunction(__FUNCTION__, $_SERVER["REQUEST_URI"]);    
        $devuelve = $this->conn->addMenus($descripcion, $nivel, $niveles, $subniveles, $visible, $destino, $icono, $orden);        
        return $devuelve;        
    }
    
    public function updateMenus($codigo, $fecha_modif, $descripcion, $nivel, $niveles, $subniveles, $visible, $destino, $icono, $orden) {
        $this->utils->newController($_SERVER["REQUEST_URI"], $_SESSION['menu']);
        $this->utils->newFunction(__FUNCTION__, $_SERVER["REQUEST_URI"]);    
        $devuelve = $this->conn->updateMenus($codigo, $fecha_modif, $descripcion, $nivel, $niveles, $subniveles, $visible, $destino, $icono, $orden);        
        return $devuelve;        
    }
    
    public function cambiar_estadoRolMenu($codigo, $estado, $rol) {
        $this->utils->newController($_SERVER["REQUEST_URI"], $_SESSION['menu']);
        $this->utils->newFunction(__FUNCTION__, $_SERVER["REQUEST_URI"]);    
        $this->conn->deleteArchivo_destino($codigo, $rol);
        $devuelve = $this->conn->cambiar_estadoRolMenu($codigo, $estado, $rol);        
        return $devuelve;        
    }
    
    public function deleteMenus($codigo) {
        $this->utils->newController($_SERVER["REQUEST_URI"], $_SESSION['menu']);
        $this->utils->newFunction(__FUNCTION__, $_SERVER["REQUEST_URI"]);    
        $devuelve = $this->conn->deleteMenus($codigo);        
        return $devuelve;        
    }
    
    public function getMenus($codigo) {
        $this->utils->newController($_SERVER["REQUEST_URI"], $_SESSION['menu']);
        $this->utils->newFunction(__FUNCTION__, $_SERVER["REQUEST_URI"]);    
        $devuelve = $this->conn->getMenus($codigo)[0];        
        $menus_all = $this->getMenusAll();
        $despues_de = 0;
        $max_orden = 0;
        foreach ($menus_all as $m){
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
    
    public function getMenusAll() {
        $this->utils->newController($_SERVER["REQUEST_URI"], $_SESSION['menu']);
        $this->utils->newFunction(__FUNCTION__, $_SERVER["REQUEST_URI"]);    
        $devuelve = $this->conn->getMenusAll();        
        return $devuelve;        
    }
    
    public function getRoles() {
        $this->utils->newController($_SERVER["REQUEST_URI"], $_SESSION['menu']);
        $this->utils->newFunction(__FUNCTION__, $_SERVER["REQUEST_URI"]);    
        $devuelve = $this->conn->getRoles();        
        return $devuelve;        
    }
    
}

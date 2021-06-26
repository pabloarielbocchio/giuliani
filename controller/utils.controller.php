<?php

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT);

session_start();

if (isset($_POST['function'])) {
    if (function_exists($_POST['function'])) {
        $_POST['function']();
    }
}

function logueoEncrypt() {
    $controlador = UtilsController::singleton_utils(); 
    echo $controlador->logueoEncrypt($_POST['nombre'], md5($_POST['password']), $_POST["cliente"]);
}

class UtilsController {

    private static $instancia;
    private $conn;

    public function __construct() {
        try {
            include $_SERVER['DOCUMENT_ROOT']."/Giuliani/model/utils.model.php";
            $this->conn = UtilsModel::singleton_utils();
        } catch (Exception $e) {
            $error = "Error!: " . $e->getMessage();
            return $error;
        }
    }

    public static function singleton_utils() {
        if (!isset(self::$instancia)) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }
    
    public function newMenu($menu){
        $this->conn->newMenu($menu);
    }
    
    public function newController($controller, $menu){
        $this->conn->newController($controller, $menu);
    }
    
    public function newFunction($funcion, $menu){
        $this->conn->newFunction($funcion, $menu);
    }
    
    public function getClientes() {
        return $this->conn->getClientes();
    }
    
    public function getSistemas() {
        return $this->conn->getSistemas();
    }
    
    public function getCliente($codigo) {
        if ($_SESSION["JSON_API"] == true) {
            $_SESSION["cliente_id"] = $codigo;
        }
        return $this->conn->getCliente($codigo)[0];
    }
    
    public function logueoEncrypt($user, $pass, $cliente){
        return $this->conn->logueoEncrypt($user, $pass, $cliente);        
    }
    
    public function getUsuarios(){
        return $this->conn->getUsuarios();        
    }
    
    public function getRoles($id_cliente){
        return $this->conn->getRoles($id_cliente);        
    }
    
    public function addUsuario($usuario, $password, $cliente, $cargo, $nombre, $apellido) {
        $devuelve = $this->conn->addUsuario($usuario, $password, $cliente, $cargo, $nombre, $apellido);        
        return $devuelve;        
    }
    
    public function updateUsuario($codigo, $usuario, $password, $cliente, $cargo, $nombre, $apellido) {
        $devuelve = $this->conn->updateUsuario($codigo, $usuario, $password, $cliente, $cargo, $nombre, $apellido);        
        return $devuelve;        
    }
    
    public function deleteUsuario($codigo) {
        $devuelve = $this->conn->deleteUsuario($codigo);        
        return $devuelve;        
    }
    
    public function logueo($user, $pass){
        return $this->conn->logueo($user, $pass);        
    }
    
    public function getMenu(){
        return $this->conn->getMenu();
    }
    
    public function getMenuUser($user){
        return $this->conn->getMenuUser($user);
    }
    
}

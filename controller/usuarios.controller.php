<?php

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT);

session_start();

if (isset($_POST['funcion'])) {
    if (function_exists($_POST['funcion'])) {
        $_POST['funcion']();
    }
}

function getCountUsuarios(){
    $controlador = UsuariosController::singleton_usuarios();
    $devuelve = $controlador->getCountUsuarios();
    if (!$_POST["json"]){
        echo $devuelve;
    } 
}

function getRegistrosFiltro(){
    $controlador = UsuariosController::singleton_usuarios();
    $devuelve = $controlador->getRegistrosFiltro($_POST['orderby'], $_POST['sentido'], $_POST['registros'], $_POST['pagina'], $_POST['busqueda']);
    if (!$_POST["json"]){
        echo $devuelve;
    } 
}

function getDestinosFiltro(){
    $controlador = UsuariosController::singleton_usuarios();
    $devuelve = $controlador->getDestinosFiltro($_POST['cod_usuario']);
    if (!$_POST["json"]){
        echo $devuelve;
    } 
}

function addUsuario() {
    $controlador = UsuariosController::singleton_usuarios();
    $devuelve = $controlador->addUsuario  (    
        $_POST['usuario'], 
        $_POST['password'], 
        $_POST['cliente'], 
        $_POST['cargo'], 
        $_POST['nombre'],
        $_POST['apellido'],
        $_POST['mail'],
        $_POST['sucursales']
    );
    if (!$_POST["json"]){
        echo $devuelve;
    } 
}

function updateUsuario() {
    $controlador = UsuariosController::singleton_usuarios();
    $devuelve = $controlador->updateUsuario(   
        $_POST['codigo'], 
        $_POST['usuario'],
        $_POST['password'],
        $_POST['cliente'], 
        $_POST['cargo'], 
        $_POST['nombre'],
        $_POST['apellido'],
        $_POST['mail'],
        $_POST['sucursales']
    );
    if (!$_POST["json"]){
        echo $devuelve;
    } 
}

function deleteUsuario() {
    $controlador = UsuariosController::singleton_usuarios();    
    $devuelve = $controlador->deleteUsuario(
        $_POST['codigo']
    );
    if (!$_POST["json"]){
        echo $devuelve;
    } 
}

function getUsuario() {
    $controlador = UsuariosController::singleton_usuarios();
    $devuelve = $controlador->getUsuario(
        $_POST['codigo']
    );
    if (!$_POST["json"]){
        echo $devuelve;
    } 
}

function afectar(){
    $controlador = UsuariosController::singleton_usuarios();
    echo $controlador->afectar($_POST['selected'], $_POST['cod_usuario']);
}

class UsuariosController {

    public static $utils;
    private static $instancia;
    private $conn;

    public function __construct() {
        try {
            include $_SERVER['DOCUMENT_ROOT']."/Giuliani/model/usuarios.model.php";
            $this->conn = UsuariosModel::singleton_usuarios();
        } catch (Exception $e) {
            $error = "Error!: " . $e->getMessage();
            return $error;
        }
    }

    public static function singleton_usuarios() {
        if (!isset(self::$instancia)) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        include_once $_SERVER['DOCUMENT_ROOT']."/Giuliani/controller/utils.controller.php";
        self::$instancia->utils= UtilsController::singleton_utils();
        return self::$instancia;
    }
    
    public function getCountUsuarios(){
        return intval($this->conn->getCountUsuarios()[0]);
    }
    
    public function getRegistrosFiltro($orderby, $sentido, $registros, $pagina, $busqueda){
        $this->utils->newController($_SERVER["REQUEST_URI"], $_SESSION['menu']);
        $this->utils->newFunction(__FUNCTION__, $_SERVER["REQUEST_URI"]);            
        $_SESSION["pagina"] = $pagina;        
        $_SESSION["cant_reg"] = $registros;        
        $_SESSION["busqueda"] = $busqueda;
        $_SESSION['orderby'] = $orderby;
        $_SESSION['sentido'] = $sentido;        
        $roles      = $this->getRoles();
        $devuelve = $this->conn->getRegistrosFiltro($orderby, $sentido, $registros, $pagina, $busqueda);        
        $prioridad = 0;
        $registros = $devuelve;        
        $_SESSION['registros'] = $registros;
        if (!$_SESSION["JSON_API"]){
            include $_SERVER['DOCUMENT_ROOT']."/Giuliani/templates/usuarios.busqueda.template.php";        
        }
    }
    
    public function getDestinosFiltro($cod_usuario){
        $this->utils->newController($_SERVER["REQUEST_URI"], $_SESSION['menu']);
        $this->utils->newFunction(__FUNCTION__, $_SERVER["REQUEST_URI"]);         
        $registros = $devuelve;        
        $_SESSION['registros'] = $registros;
        $destinos = $this->getDestinos();        
        $devuelve = $this->conn->getUsuariosDestinos($cod_usuario);
        foreach ($destinos as $p => $e){
            $destinos[$p]["selected"] = "";
            foreach ($devuelve as $dev){
                if ($dev["destino_id"] == $e["codigo"]){
                    $destinos[$p]["selected"] = "selected";
                    break;
                }
            }
        }
        $registros = $devuelve;
        if (!$_SESSION["JSON_API"]){
            include $_SERVER['DOCUMENT_ROOT']."/Giuliani/templates/usuarios.destinos.template.php";        
        }
    }
    
    public function addUsuario($usuario, $password, $cliente, $cargo, $nombre, $apellido, $mail, $sucursales) {
        $this->utils->newController($_SERVER["REQUEST_URI"], $_SESSION['menu']);
        $this->utils->newFunction(__FUNCTION__, $_SERVER["REQUEST_URI"]);    
        $password = md5($password); 
        //$devuelve = $this->utils->addUsuario($usuario, $password, $cliente, $cargo, $nombre, $apellido);        
        $devuelve = $this->conn->addUsuario($id_usu, $usuario, $password, $cliente, $cargo, $nombre, $apellido, $mail);     
        $ultimo = $this->conn->getLastUsuario()[0]["codigo"];
        $menues = [];
        $menues = [1,2,3,4,5,9,34,35,36,37,38,39,40,41];
        /*switch ($cargo){
            case 1: 
                $menues = [1,2,3,4,5,9,34,35,36,37,38,39,40];
                break;
            case 2: 
                $menues = [1,2,3,4,5,9,34,35,36,37,38,39,40];
                break;
            case 3: 
                $menues = [1,3,34,35,36,37,38,39,40];
                break;
            case 4: 
                $menues = [1,3,34,35,36,37,38,39,40];
                break;
            case 5: 
                $menues = [1,3,4,5,7,8,9,34,35,36,37,38,39,40];
                break;
        }*/
        foreach($menues as $m){
            $this->conn->addUsuarioMenu($ultimo, $m);
        }

        
        /*if ($devuelve == 0){
            $users = $this->utils->getUsuarios();
            foreach ($users as $u){
                if ($u["mail"] == $usuario){
                    $id_usu = $u["id"];
                    break;
                }
            }
            // public function updateSucursalesUsuarios($selected, $sucursal, $usuario){
            //$this->conn->updateSucursalesUsuarios($sucursales, 0, $id_usu);
            //return $devuelve;        
        }*/return $devuelve;
    }
    
    public function updateUsuario($codigo, $usuario, $password, $cliente, $cargo, $nombre, $apellido, $mail, $sucursales) {
        $this->utils->newController($_SERVER["REQUEST_URI"], $_SESSION['menu']);
        $this->utils->newFunction(__FUNCTION__, $_SERVER["REQUEST_URI"]);    
        $password = md5($password);
        $devuelve = $this->conn->updateUsuario($codigo, $usuario, $password, $cliente, $cargo, $nombre, $apellido, $mail);        
        $this->conn->deleteUsuarioMenu($codigo);
        $menues = [];
        switch ($cargo){
            case 1: 
                $menues = [1,2,3,4,5,7,8,9,34,35,36,37];
                break;
            case 2: 
                $menues = [1,2,3,4,5,7,8,9,34,35,36,37];
                break;
            case 3: 
                $menues = [1,3,34,35,36,37];
                break;
            case 4: 
                $menues = [1,3,34,35,36,37];
                break;
            case 5: 
                $menues = [1,3,4,5,7,8,9,34,35,36,37];
                break;
        }
        foreach($menues as $m){
            $this->conn->addUsuarioMenu($codigo, $m);
        }
        return $devuelve;
    }
    
    public function deleteUsuario($codigo) {
        $this->utils->newController($_SERVER["REQUEST_URI"], $_SESSION['menu']);
        $this->utils->newFunction(__FUNCTION__, $_SERVER["REQUEST_URI"]);    
        //$devuelve = $this->utils->deleteUsuario($codigo);          
        $devuelve = $this->conn->deleteUsuario($codigo);       
        /*if ($devuelve == 0){
            $devuelve = $this->conn->deleteUsuario($codigo);        
            return $devuelve;        
        } */
        return $devuelve;
    }
    
    public function getUsuario($codigo) {
        $this->utils->newController($_SERVER["REQUEST_URI"], $_SESSION['menu']);
        $this->utils->newFunction(__FUNCTION__, $_SERVER["REQUEST_URI"]);    
        $devuelve = $this->conn->getUsuario($codigo);        
       /* $sucursales = $this->conn->getSucursalesUsuario($codigo); //        orders_sucursal_usuario
        $devuelve[0]["sucursales"] = array();
        foreach ($sucursales as $s){
            $devuelve[0]["sucursales"][] = $s["sucursal_id"];
        }*/
        return json_encode($devuelve[0]);        
    }
    
    public function getClientes() {
        $devuelve = $this->conn->getClientes();
        return $devuelve;        
    }
    
    public function getRoles() {
        $devuelve = $this->utils->getRoles(intval($_SESSION["id_cliente"]));
        return $devuelve;        
    }
    
    public function getClientesAll() {
        return $this->utils->getClientes();
    }
    
    public function getSucursales() {
        $devuelve = $this->conn->getSucursales();        
        return $devuelve;
    }
    
    public function getDestinos() {
        $devuelve = $this->conn->getDestinos();        
        return $devuelve;
    }
    
    public function getUsuarios() {
        $devuelve = $this->conn->getUsuarios();        
        return $devuelve;
    }
    
    public function afectar($seleccionados, $cod_usuario){   
        return $this->conn->afectar($seleccionados, $cod_usuario);
    } 
    
}

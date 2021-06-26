<?php

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT);

session_start();

if (isset($_POST['funcion'])) {
    if (function_exists($_POST['funcion'])) {
        $_POST['funcion']();
    }
}

function getRegistrosFiltro(){
    $controlador = DestinosController::singleton_destinos();
    
    echo $controlador->getRegistrosFiltro($_POST['orderby'], $_POST['sentido'], $_POST['registros'], $_POST['pagina'], $_POST['busqueda']);
}

function addDestino() {
    $controlador = DestinosController::singleton_destinos();
    
    echo $controlador->addDestino  (    
                                        $_POST['descripcion']
            );
}

function updateDestino() {
    $controlador = DestinosController::singleton_destinos();
    
    echo $controlador->updateDestino(    $_POST['codigo'], 
                                        $_POST['descripcion']
            );
}

function deleteDestino() {
    $controlador = DestinosController::singleton_destinos();
    
    echo $controlador->deleteDestino($_POST['codigo']);
}

function getDestino() {
    $controlador = DestinosController::singleton_destinos();
    
    echo $controlador->getDestino($_POST['codigo']);
}

class DestinosController {

    private static $instancia;
    private $conn;

    public function __construct() {
        try {
            include $_SERVER['DOCUMENT_ROOT']."/Giuliani/model/destinos.model.php";
            $this->conn = DestinosModel::singleton_destinos();
        } catch (Exception $e) {
            $error = "Error!: " . $e->getMessage();
            return $error;
        }
    }

    public static function singleton_destinos() {
        if (!isset(self::$instancia)) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }
    
    public function getCountDestinos(){
        return intval($this->conn->getCountDestinos()[0]);
        
    }

    
    public function getRegistrosFiltro($orderby, $sentido, $registros, $pagina, $busqueda){
        
        $_SESSION["pagina"] = $pagina;
        
        $_SESSION["cant_reg"] = $registros;
        
        $_SESSION["busqueda"] = $busqueda;
                
        $_SESSION['orderby'] = $orderby;
        
        $_SESSION['sentido'] = $sentido;
        
        $devuelve = $this->conn->getRegistrosFiltro($orderby, $sentido, $registros, $pagina, $busqueda);
                                
        $registros = $devuelve;
        
        $_SESSION['registros'] = $registros;

        include $_SERVER['DOCUMENT_ROOT']."/Giuliani/templates/destinos.busqueda.template.php";
        
    }
    
    public function addDestino($descripcion) {
        $devuelve = $this->conn->addDestino($descripcion);
        
        return $devuelve;
        
    }
    
    public function updateDestino($codigo, $descripcion) {
        $devuelve = $this->conn->updateDestino($codigo, $descripcion);
        
        return $devuelve;
        
    }
    
    public function deleteDestino($codigo) {
        $devuelve = $this->conn->deleteDestino($codigo);
        
        return $devuelve;
        
    }
    
    public function getDestino($codigo) {
        $devuelve = $this->conn->getDestino($codigo);
        
        return json_encode($devuelve[0]);
        
    }
}

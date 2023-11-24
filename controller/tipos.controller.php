<?php

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT);

session_start();

if (isset($_POST['funcion'])) {
    if (function_exists($_POST['funcion'])) {
        $_POST['funcion']();
    }
}

function getRegistrosFiltro(){
    $controlador = TiposController::singleton_tipos();
    
    echo $controlador->getRegistrosFiltro($_POST['orderby'], $_POST['sentido'], $_POST['registros'], $_POST['pagina'], $_POST['busqueda']);
}

function addTipo() {
    $controlador = TiposController::singleton_tipos();
    
    echo $controlador->addTipo  (    
                                        $_POST['descripcion']
            );
}

function updateTipo() {
    $controlador = TiposController::singleton_tipos();
    
    echo $controlador->updateTipo(    $_POST['codigo'], 
                                        $_POST['descripcion']
            );
}

function deleteTipo() {
    $controlador = TiposController::singleton_tipos();
    
    echo $controlador->deleteTipo($_POST['codigo']);
}

function getTipo() {
    $controlador = TiposController::singleton_tipos();
    
    echo $controlador->getTipo($_POST['codigo']);
}

class TiposController {

    private static $instancia;
    private $conn;

    public function __construct() {
        try {
            include $_SERVER['DOCUMENT_ROOT']."/Giuliani/model/tipos.model.php";
            $this->conn = TiposModel::singleton_tipos();
        } catch (Exception $e) {
            $error = "Error!: " . $e->getMessage();
            return $error;
        }
    }

    public static function singleton_tipos() {
        if (!isset(self::$instancia)) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }
    
    public function getCountTipos(){
        return intval($this->conn->getCountTipos()[0]);
        
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

        include $_SERVER['DOCUMENT_ROOT']."/Giuliani/templates/tipos.busqueda.template.php";
        
    }
    
    public function addTipo($descripcion) {
        $devuelve = $this->conn->addTipo($descripcion);
        
        return $devuelve;
        
    }
    
    public function updateTipo($codigo, $descripcion) {
        $devuelve = $this->conn->updateTipo($codigo, $descripcion);
        
        return $devuelve;
        
    }
    
    public function deleteTipo($codigo) {
        $devuelve = $this->conn->deleteTipo($codigo);
        
        return $devuelve;
        
    }
    
    public function getTipo($codigo) {
        $devuelve = $this->conn->getTipo($codigo);
        
        return json_encode($devuelve[0]);
        
    }
}

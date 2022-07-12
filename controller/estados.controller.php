<?php

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT);

session_start();

if (isset($_POST['funcion'])) {
    if (function_exists($_POST['funcion'])) {
        $_POST['funcion']();
    }
}

function getRegistrosFiltro(){
    $controlador = EstadosController::singleton_estados();
    
    echo $controlador->getRegistrosFiltro($_POST['orderby'], $_POST['sentido'], $_POST['registros'], $_POST['pagina'], $_POST['busqueda']);
}

function addEstado() {
    $controlador = EstadosController::singleton_estados();
    
    echo $controlador->addEstado  (    
                                        $_POST['descripcion'],
                                        $_POST['abrev']
            );
}

function updateEstado() {
    $controlador = EstadosController::singleton_estados();
    
    echo $controlador->updateEstado(    $_POST['codigo'], 
                                        $_POST['descripcion'],
                                        $_POST['abrev']
            );
}

function deleteEstado() {
    $controlador = EstadosController::singleton_estados();
    
    echo $controlador->deleteEstado($_POST['codigo']);
}

function getEstado() {
    $controlador = EstadosController::singleton_estados();
    
    echo $controlador->getEstado($_POST['codigo']);
}

class EstadosController {

    private static $instancia;
    private $conn;

    public function __construct() {
        try {
            include $_SERVER['DOCUMENT_ROOT']."/Giuliani/model/estados.model.php";
            $this->conn = EstadosModel::singleton_estados();
        } catch (Exception $e) {
            $error = "Error!: " . $e->getMessage();
            return $error;
        }
    }

    public static function singleton_estados() {
        if (!isset(self::$instancia)) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }
    
    public function getCountEstados(){
        return intval($this->conn->getCountEstados()[0]);
        
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

        include $_SERVER['DOCUMENT_ROOT']."/Giuliani/templates/estados.busqueda.template.php";
        
    }
    
    public function addEstado($descripcion, $abrev) {
        $devuelve = $this->conn->addEstado($descripcion, $abrev);
        
        return $devuelve;
        
    }
    
    public function updateEstado($codigo, $descripcion, $abrev) {
        $devuelve = $this->conn->updateEstado($codigo, $descripcion, $abrev);
        
        return $devuelve;
        
    }
    
    public function deleteEstado($codigo) {
        $devuelve = $this->conn->deleteEstado($codigo);
        
        return $devuelve;
        
    }
    
    public function getEstado($codigo) {
        $devuelve = $this->conn->getEstado($codigo);
        
        return json_encode($devuelve[0]);
        
    }
}

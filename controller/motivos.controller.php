<?php

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT);

session_start();

if (isset($_POST['funcion'])) {
    if (function_exists($_POST['funcion'])) {
        $_POST['funcion']();
    }
}

function getRegistrosFiltro(){
    $controlador = MotivosController::singleton_motivos();
    
    echo $controlador->getRegistrosFiltro($_POST['orderby'], $_POST['sentido'], $_POST['registros'], $_POST['pagina'], $_POST['busqueda']);
}

function addMotivo() {
    $controlador = MotivosController::singleton_motivos();
    
    echo $controlador->addMotivo  (    
                                        $_POST['descripcion']
            );
}

function updateMotivo() {
    $controlador = MotivosController::singleton_motivos();
    
    echo $controlador->updateMotivo(    $_POST['codigo'], 
                                        $_POST['descripcion']
            );
}

function deleteMotivo() {
    $controlador = MotivosController::singleton_motivos();
    
    echo $controlador->deleteMotivo($_POST['codigo']);
}

function getMotivo() {
    $controlador = MotivosController::singleton_motivos();
    
    echo $controlador->getMotivo($_POST['codigo']);
}

class MotivosController {

    private static $instancia;
    private $conn;

    public function __construct() {
        try {
            include $_SERVER['DOCUMENT_ROOT']."/Giuliani/model/motivos.model.php";
            $this->conn = MotivosModel::singleton_motivos();
        } catch (Exception $e) {
            $error = "Error!: " . $e->getMessage();
            return $error;
        }
    }

    public static function singleton_motivos() {
        if (!isset(self::$instancia)) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }
    
    public function getCountMotivos(){
        return intval($this->conn->getCountMotivos()[0]);
        
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

        include $_SERVER['DOCUMENT_ROOT']."/Giuliani/templates/motivos.busqueda.template.php";
        
    }
    
    public function addMotivo($descripcion) {
        $devuelve = $this->conn->addMotivo($descripcion);
        
        return $devuelve;
        
    }
    
    public function updateMotivo($codigo, $descripcion) {
        $devuelve = $this->conn->updateMotivo($codigo, $descripcion);
        
        return $devuelve;
        
    }
    
    public function deleteMotivo($codigo) {
        $devuelve = $this->conn->deleteMotivo($codigo);
        
        return $devuelve;
        
    }
    
    public function getMotivo($codigo) {
        $devuelve = $this->conn->getMotivo($codigo);
        
        return json_encode($devuelve[0]);
        
    }
}

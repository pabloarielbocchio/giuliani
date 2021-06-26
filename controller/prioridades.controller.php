<?php

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT);

session_start();

if (isset($_POST['funcion'])) {
    if (function_exists($_POST['funcion'])) {
        $_POST['funcion']();
    }
}

function getRegistrosFiltro(){
    $controlador = PrioridadesController::singleton_prioridades();
    
    echo $controlador->getRegistrosFiltro($_POST['orderby'], $_POST['sentido'], $_POST['registros'], $_POST['pagina'], $_POST['busqueda']);
}

function addPrioridad() {
    $controlador = PrioridadesController::singleton_prioridades();
    
    echo $controlador->addPrioridad  (    
                                        $_POST['descripcion'],
                                        $_POST['prioridad']
            );
}

function updatePrioridad() {
    $controlador = PrioridadesController::singleton_prioridades();
    
    echo $controlador->updatePrioridad(    $_POST['codigo'], 
                                        $_POST['descripcion'],
                                        $_POST['prioridad']
            );
}

function deletePrioridad() {
    $controlador = PrioridadesController::singleton_prioridades();
    
    echo $controlador->deletePrioridad($_POST['codigo']);
}

function getPrioridad() {
    $controlador = PrioridadesController::singleton_prioridades();
    
    echo $controlador->getPrioridad($_POST['codigo']);
}

class PrioridadesController {

    private static $instancia;
    private $conn;

    public function __construct() {
        try {
            include $_SERVER['DOCUMENT_ROOT']."/Giuliani/model/prioridades.model.php";
            $this->conn = PrioridadesModel::singleton_prioridades();
        } catch (Exception $e) {
            $error = "Error!: " . $e->getMessage();
            return $error;
        }
    }

    public static function singleton_prioridades() {
        if (!isset(self::$instancia)) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }
    
    public function getCountPrioridades(){
        return intval($this->conn->getCountPrioridades()[0]);
        
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

        include $_SERVER['DOCUMENT_ROOT']."/Giuliani/templates/prioridades.busqueda.template.php";
        
    }
    
    public function addPrioridad($descripcion, $prioridad) {
        $devuelve = $this->conn->addPrioridad($descripcion, $prioridad);
        
        return $devuelve;
        
    }
    
    public function updatePrioridad($codigo, $descripcion, $prioridad) {
        $devuelve = $this->conn->updatePrioridad($codigo, $descripcion, $prioridad);
        
        return $devuelve;
        
    }
    
    public function deletePrioridad($codigo) {
        $devuelve = $this->conn->deletePrioridad($codigo);
        
        return $devuelve;
        
    }
    
    public function getPrioridad($codigo) {
        $devuelve = $this->conn->getPrioridad($codigo);
        
        return json_encode($devuelve[0]);
        
    }
}

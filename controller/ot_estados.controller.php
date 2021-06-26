<?php

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT);

session_start();

if (isset($_POST['funcion'])) {
    if (function_exists($_POST['funcion'])) {
        $_POST['funcion']();
    }
}

function getRegistrosFiltro(){
    $controlador = Ot_estadosController::singleton_ot_estados();
    
    echo $controlador->getRegistrosFiltro($_POST['orderby'], $_POST['sentido'], $_POST['registros'], $_POST['pagina'], $_POST['busqueda']);
}

function addOt_estado() {
    $controlador = Ot_estadosController::singleton_ot_estados();
    
    echo $controlador->addOt_estado  (    
                                        $_POST['produccion'],
                                        $_POST['estado'],
                                        $_POST['destino'],
                                        $_POST['observaciones']
            );
}

function updateOt_estado() {
    $controlador = Ot_estadosController::singleton_ot_estados();
    
    echo $controlador->updateOt_estado(    $_POST['codigo'], 
                                            $_POST['produccion'],
                                            $_POST['estado'],
                                            $_POST['destino'],
                                            $_POST['observaciones']
            );
}

function deleteOt_estado() {
    $controlador = Ot_estadosController::singleton_ot_estados();
    
    echo $controlador->deleteOt_estado($_POST['codigo']);
}

function getOt_estado() {
    $controlador = Ot_estadosController::singleton_ot_estados();
    
    echo $controlador->getOt_estado($_POST['codigo']);
}

class Ot_estadosController {

    private static $instancia;
    private $conn;

    public function __construct() {
        try {
            include $_SERVER['DOCUMENT_ROOT']."/Giuliani/model/ot_estados.model.php";
            $this->conn = Ot_estadosModel::singleton_ot_estados();
        } catch (Exception $e) {
            $error = "Error!: " . $e->getMessage();
            return $error;
        }
    }

    public static function singleton_ot_estados() {
        if (!isset(self::$instancia)) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }
    
    public function getCountOt_estados(){
        return intval($this->conn->getCountOt_estados()[0]);
        
    }

    
    public function getRegistrosFiltro($orderby, $sentido, $registros, $pagina, $busqueda){
        
        $_SESSION["pagina"] = $pagina;
        
        $_SESSION["cant_reg"] = $registros;
        
        $_SESSION["busqueda"] = $busqueda;
                
        $_SESSION['orderby'] = $orderby;
        
        $_SESSION['sentido'] = $sentido;
        
        $devuelve = $this->conn->getRegistrosFiltro($orderby, $sentido, $registros, $pagina, $busqueda);
                
        $ots_detalles       = $this->getOtsDetalles();
        $ots_produccions    = $this->getOtsProduccions();
        $estados            = $this->getEstados();
        $destinos           = $this->getDestinos();
                                
        $registros = $devuelve;
        
        $_SESSION['registros'] = $registros;

        include $_SERVER['DOCUMENT_ROOT']."/Giuliani/templates/ot_estados.busqueda.template.php";
        
    }
    
    public function addOt_estado($produccion, $estado, $destino, $observaciones) {
        $devuelve = $this->conn->addOt_estado($produccion, $estado, $destino, $observaciones);
        
        return $devuelve;
        
    }
    
    public function updateOt_estado($codigo, $produccion, $estado, $destino, $observaciones) {
        $devuelve = $this->conn->updateOt_estado($codigo, $produccion, $estado, $destino, $observaciones);
        
        return $devuelve;
        
    }
    
    public function deleteOt_estado($codigo) {
        $devuelve = $this->conn->deleteOt_estado($codigo);
        
        return $devuelve;
        
    }
    
    public function getOt_estado($codigo) {
        $devuelve = $this->conn->getOt_estado($codigo);
        
        return json_encode($devuelve[0]);
        
    }
    
    public function getSecciones() {
        $devuelve = $this->conn->getSecciones();
        
        return $devuelve;
        
    }
    
    public function getSectores() {
        $devuelve = $this->conn->getSectores();
        
        return $devuelve;
        
    }
    
    public function getEstados() {
        $devuelve = $this->conn->getEstados();
        
        return $devuelve;
        
    }
    
    public function getPrioridades() {
        $devuelve = $this->conn->getPrioridades();
        
        return $devuelve;
        
    }
    
    public function getOts() {
        $devuelve = $this->conn->getOts();
        
        return $devuelve;
        
    }
    
    public function getOtsDetalles() {
        $devuelve = $this->conn->getOtsDetalles();
        
        return $devuelve;
        
    }
    
    public function getOtsProduccions() {
        $devuelve = $this->conn->getOtsProduccions();
        
        return $devuelve;
        
    }
    
    public function getEventos() {
        $devuelve = $this->conn->getEventos();
        
        return $devuelve;
        
    }
    
    public function getDestinos() {
        $devuelve = $this->conn->getDestinos();
        
        return $devuelve;
        
    }
}

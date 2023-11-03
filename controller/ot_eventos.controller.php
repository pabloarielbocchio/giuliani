<?php

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT);

session_start();

if (isset($_POST['funcion'])) {
    if (function_exists($_POST['funcion'])) {
        $_POST['funcion']();
    }
}

function getRegistrosFiltro(){
    $controlador = Ot_eventosController::singleton_ot_eventos();
    
    echo $controlador->getRegistrosFiltro($_POST['orderby'], $_POST['sentido'], $_POST['registros'], $_POST['pagina'], $_POST['busqueda'], $_POST["evento"], $_POST["usuario"], $_POST["ot"]);
}

function addOt_evento() {
    $controlador = Ot_eventosController::singleton_ot_eventos();
    
    echo $controlador->addOt_evento  (    
                                        $_POST['detalle'],
                                        $_POST['produccion'],
                                        $_POST['evento'],
                                        $_POST['destino'],
                                        $_POST['observaciones']
            );
}

function updateOt_evento() {
    $controlador = Ot_eventosController::singleton_ot_eventos();
    
    echo $controlador->updateOt_evento(    $_POST['codigo'], 
                                            $_POST['detalle'],
                                            $_POST['produccion'],
                                            $_POST['evento'],
                                            $_POST['destino'],
                                            $_POST['observaciones']
            );
}

function deleteOt_evento() {
    $controlador = Ot_eventosController::singleton_ot_eventos();
    
    echo $controlador->deleteOt_evento($_POST['codigo']);
}

function getOt_evento() {
    $controlador = Ot_eventosController::singleton_ot_eventos();
    
    echo $controlador->getOt_evento($_POST['codigo']);
}

class Ot_eventosController {

    private static $instancia;
    private $conn;

    public function __construct() {
        try {
            include $_SERVER['DOCUMENT_ROOT']."/Giuliani/model/ot_eventos.model.php";
            $this->conn = Ot_eventosModel::singleton_ot_eventos();
        } catch (Exception $e) {
            $error = "Error!: " . $e->getMessage();
            return $error;
        }
    }

    public static function singleton_ot_eventos() {
        if (!isset(self::$instancia)) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }
    
    public function getCountOt_eventos(){
        return intval($this->conn->getCountOt_eventos()[0]);
        
    }

    
    public function getRegistrosFiltro($orderby, $sentido, $registros, $pagina, $busqueda, $evento, $usuario, $ot){
        
        $_SESSION["pagina"] = $pagina;
        $_SESSION["cant_reg"] = $registros;
        $_SESSION["busqueda"] = $busqueda;
        $_SESSION['orderby'] = $orderby;
        $_SESSION['sentido'] = $sentido;
        
        $_SESSION['evento_selected'] = $evento;
        $_SESSION['usuario_selected'] = $usuario;
        $_SESSION['ot_selected'] = $ot;
        
        $devuelve = $this->conn->getRegistrosFiltro($orderby, $sentido, $registros, $pagina, $busqueda);
                
        $ots_detalles       = $this->getOtsDetalles();
        $ots_produccions    = $this->getOtsProduccions();
        $eventos            = $this->getEventos();
        $destinos           = $this->getDestinos();
                                
        $registros = $devuelve;
        
        $_SESSION['registros'] = $registros; 

        include $_SERVER['DOCUMENT_ROOT']."/Giuliani/templates/ot_eventos.busqueda.template.php";
        
    }
    
    public function addOt_evento($detalle, $produccion, $evento, $destino, $observaciones) {
        $devuelve = $this->conn->addOt_evento($detalle, $produccion, $evento, $destino, $observaciones);
        
        return $devuelve;
        
    }
    
    public function updateOt_evento($codigo, $detalle, $produccion, $evento, $destino, $observaciones) {
        $devuelve = $this->conn->updateOt_evento($codigo, $detalle, $produccion, $evento, $destino, $observaciones);
        
        return $devuelve;
        
    }
    
    public function deleteOt_evento($codigo) {
        $devuelve = $this->conn->deleteOt_evento($codigo);
        
        return $devuelve;
        
    }
    
    public function getOt_evento($codigo) {
        $devuelve = $this->conn->getOt_evento($codigo);
        
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
    
    public function getUsuarios() {
        $devuelve = $this->conn->getUsuarios();
        
        return $devuelve;
        
    }
    
    public function getDestinos() {
        $devuelve = $this->conn->getDestinos();
        
        return $devuelve;
        
    }
}

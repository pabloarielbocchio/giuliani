<?php

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT);

session_start();

if (isset($_POST['funcion'])) {
    if (function_exists($_POST['funcion'])) {
        $_POST['funcion']();
    }
}

function getRegistrosFiltro(){
    $contsectorador = SectoresController::singleton_sectores();
    
    echo $contsectorador->getRegistrosFiltro($_POST['orderby'], $_POST['sentido'], $_POST['registros'], $_POST['pagina'], $_POST['busqueda']);
}

function addSector() {
    $contsectorador = SectoresController::singleton_sectores();
    
    echo $contsectorador->addSector  (    
                                        $_POST['descripcion']
            );
}

function updateSector() {
    $contsectorador = SectoresController::singleton_sectores();
    
    echo $contsectorador->updateSector(    $_POST['codigo'], 
                                        $_POST['descripcion']
            );
}

function deleteSector() {
    $contsectorador = SectoresController::singleton_sectores();
    
    echo $contsectorador->deleteSector($_POST['codigo']);
}

function getSector() {
    $contsectorador = SectoresController::singleton_sectores();
    
    echo $contsectorador->getSector($_POST['codigo']);
}

class SectoresController {

    private static $instancia;
    private $conn;

    public function __construct() {
        try {
            include $_SERVER['DOCUMENT_ROOT']."/Giuliani/model/sectores.model.php";
            $this->conn = SectoresModel::singleton_sectores();
        } catch (Exception $e) {
            $error = "Error!: " . $e->getMessage();
            return $error;
        }
    }

    public static function singleton_sectores() {
        if (!isset(self::$instancia)) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }
    
    public function getCountSectores(){
        return intval($this->conn->getCountSectores()[0]);
        
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

        include $_SERVER['DOCUMENT_ROOT']."/Giuliani/templates/sectores.busqueda.template.php";
        
    }
    
    public function addSector($descripcion) {
        $devuelve = $this->conn->addSector($descripcion);
        
        return $devuelve;
        
    }
    
    public function updateSector($codigo, $descripcion) {
        $devuelve = $this->conn->updateSector($codigo, $descripcion);
        
        return $devuelve;
        
    }
    
    public function deleteSector($codigo) {
        $devuelve = $this->conn->deleteSector($codigo);
        
        return $devuelve;
        
    }
    
    public function getSector($codigo) {
        $devuelve = $this->conn->getSector($codigo);
        
        return json_encode($devuelve[0]);
        
    }
}

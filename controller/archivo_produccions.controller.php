<?php

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT);

session_start();

if (isset($_POST['funcion'])) {
    if (function_exists($_POST['funcion'])) {
        $_POST['funcion']();
    }
}

function getRegistrosFiltro(){
    $controlador = Archivo_produccionsController::singleton_archivo_produccions();
    
    echo $controlador->getRegistrosFiltro($_POST['orderby'], $_POST['sentido'], $_POST['registros'], $_POST['pagina'], $_POST['busqueda']);
}

function addArchivo_produccion() {
    $controlador = Archivo_produccionsController::singleton_archivo_produccions();
    
    echo $controlador->addArchivo_produccion  (    
                                        $_POST['archivo'],
                                        $_POST['produccion'],
                                        $_POST['observaciones']
            );
}

function updateArchivo_produccion() {
    $controlador = Archivo_produccionsController::singleton_archivo_produccions();
    
    echo $controlador->updateArchivo_produccion(    $_POST['codigo'], 
                                        $_POST['archivo'],
                                        $_POST['produccion'],
                                        $_POST['observaciones']
            );
}

function deleteArchivo_produccion() {
    $controlador = Archivo_produccionsController::singleton_archivo_produccions();
    
    echo $controlador->deleteArchivo_produccion($_POST['codigo']);
}

function getArchivo_produccion() {
    $controlador = Archivo_produccionsController::singleton_archivo_produccions();
    
    echo $controlador->getArchivo_produccion($_POST['codigo']);
}

class Archivo_produccionsController {

    private static $instancia;
    private $conn;

    public function __construct() {
        try {
            include $_SERVER['DOCUMENT_ROOT']."/Giuliani/model/archivo_produccions.model.php";
            $this->conn = Archivo_produccionsModel::singleton_archivo_produccions();
        } catch (Exception $e) {
            $error = "Error!: " . $e->getMessage();
            return $error;
        }
    }

    public static function singleton_archivo_produccions() {
        if (!isset(self::$instancia)) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }
    
    public function getCountArchivo_produccions(){
        return intval($this->conn->getCountArchivo_produccions()[0]);
        
    }

    
    public function getRegistrosFiltro($orderby, $sentido, $registros, $pagina, $busqueda){
        
        $_SESSION["pagina"] = $pagina;
        
        $_SESSION["cant_reg"] = $registros;
        
        $_SESSION["busqueda"] = $busqueda;
                
        $_SESSION['orderby'] = $orderby;
        
        $_SESSION['sentido'] = $sentido;
        
        $devuelve = $this->conn->getRegistrosFiltro($orderby, $sentido, $registros, $pagina, $busqueda);
                
        $archivos = $this->getArchivos();
        $produccions = $this->getProduccions();                     

        $registros = $devuelve;
        
        $_SESSION['registros'] = $registros;

        include $_SERVER['DOCUMENT_ROOT']."/Giuliani/templates/archivo_produccions.busqueda.template.php";
        
    }    
    
    public function addArchivo_produccion($archivo, $produccion, $observaciones) {
        $devuelve = $this->conn->addArchivo_produccion($archivo, $produccion, $observaciones);
        
        return $devuelve;
        
    }
    
    public function updateArchivo_produccion($codigo, $archivo, $produccion, $observaciones) {
        $devuelve = $this->conn->updateArchivo_produccion($codigo, $archivo, $produccion, $observaciones);
        
        return $devuelve;
        
    }
    
    public function deleteArchivo_produccion($codigo) {
        $devuelve = $this->conn->deleteArchivo_produccion($codigo);
        
        return $devuelve;
        
    }
    
    public function getArchivo_produccion($codigo) {
        $devuelve = $this->conn->getArchivo_produccion($codigo);
        
        return json_encode($devuelve[0]);
        
    }
    
    public function getArchivos() {
        $devuelve = $this->conn->getArchivos();        
        return $devuelve;
    }
    
    public function getProduccions() {
        $devuelve = $this->conn->getProduccions();        
        return $devuelve;
    }
}

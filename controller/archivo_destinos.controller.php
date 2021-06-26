<?php

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT);

session_start();

if (isset($_POST['funcion'])) {
    if (function_exists($_POST['funcion'])) {
        $_POST['funcion']();
    }
}

function getRegistrosFiltro(){
    $controlador = Archivo_destinosController::singleton_archivo_destinos();
    
    echo $controlador->getRegistrosFiltro($_POST['orderby'], $_POST['sentido'], $_POST['registros'], $_POST['pagina'], $_POST['busqueda']);
}

function addArchivo_destino() {
    $controlador = Archivo_destinosController::singleton_archivo_destinos();
    
    echo $controlador->addArchivo_destino  (    
                                        $_POST['archivo'],
                                        $_POST['destino'],
                                        $_POST['observaciones']
            );
}

function cambiar_estadoArchivo() {
    $controlador = Archivo_destinosController::singleton_archivo_destinos();
    
    echo $controlador->cambiar_estadoArchivo(    $_POST['codigo'], 
                                        $_POST['estado']
            );
}

function updateArchivo_destino() {
    $controlador = Archivo_destinosController::singleton_archivo_destinos();
    
    echo $controlador->updateArchivo_destino(    $_POST['codigo'], 
                                        $_POST['archivo'],
                                        $_POST['destino'],
                                        $_POST['observaciones']
            );
}

function deleteArchivo_destino() {
    $controlador = Archivo_destinosController::singleton_archivo_destinos();
    
    echo $controlador->deleteArchivo_destino($_POST['codigo']);
}

function getArchivo_destino() {
    $controlador = Archivo_destinosController::singleton_archivo_destinos();
    
    echo $controlador->getArchivo_destino($_POST['codigo']);
}

class Archivo_destinosController {

    private static $instancia;
    private $conn;

    public function __construct() {
        try {
            include $_SERVER['DOCUMENT_ROOT']."/Giuliani/model/archivo_destinos.model.php";
            $this->conn = Archivo_destinosModel::singleton_archivo_destinos();
        } catch (Exception $e) {
            $error = "Error!: " . $e->getMessage();
            return $error;
        }
    }

    public static function singleton_archivo_destinos() {
        if (!isset(self::$instancia)) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }
    
    public function getCountArchivo_destinos(){
        return intval($this->conn->getCountArchivo_destinos()[0]);
        
    }

    
    public function getRegistrosFiltro($orderby, $sentido, $registros, $pagina, $busqueda){
        
        $_SESSION["pagina"] = $pagina;
        
        $_SESSION["cant_reg"] = $registros;
        
        $_SESSION["busqueda"] = $busqueda;
                
        $_SESSION['orderby'] = $orderby;
        
        $_SESSION['sentido'] = $sentido;
        
        $devuelve = $this->conn->getRegistrosFiltro($orderby, $sentido, $registros, $pagina, $busqueda);
                
        $archivos = $this->getArchivos();
        $destinos = $this->getDestinos();                     

        $registros = $devuelve;
        
        $_SESSION['registros'] = $registros;

        include $_SERVER['DOCUMENT_ROOT']."/Giuliani/templates/archivo_destinos.busqueda.template.php";
        
    }    
    
    public function addArchivo_destino($archivo, $destino, $observaciones) {
        $devuelve = $this->conn->addArchivo_destino($archivo, $destino, $observaciones);
        
        return $devuelve;
        
    }
    
    public function updateArchivo_destino($codigo, $archivo, $destino, $observaciones) {
        $devuelve = $this->conn->updateArchivo_destino($codigo, $archivo, $destino, $observaciones);
        
        return $devuelve;
        
    }
    
    public function cambiar_estadoArchivo($codigo, $estado) {
        $devuelve = $this->conn->cambiar_estadoArchivo($codigo, $estado);
        
        return $devuelve;
        
    }
    
    public function deleteArchivo_destino($codigo) {
        $devuelve = $this->conn->deleteArchivo_destino($codigo);
        
        return $devuelve;
        
    }
    
    public function getArchivo_destino($codigo) {
        $devuelve = $this->conn->getArchivo_destino($codigo);
        
        return json_encode($devuelve[0]);
        
    }
    
    public function getArchivos() {
        $devuelve = $this->conn->getArchivos();        
        return $devuelve;
    }
    
    public function getDestinos() {
        $devuelve = $this->conn->getDestinos();        
        return $devuelve;
    }
}

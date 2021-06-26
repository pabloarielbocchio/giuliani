<?php

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT);

session_start();

if (isset($_POST['funcion'])) {
    if (function_exists($_POST['funcion'])) {
        $_POST['funcion']();
    }
}

function getRegistrosFiltro(){
    $controlador = SeccionesController::singleton_secciones();
    
    echo $controlador->getRegistrosFiltro($_POST['orderby'], $_POST['sentido'], $_POST['registros'], $_POST['pagina'], $_POST['busqueda']);
}

function addSeccion() {
    $controlador = SeccionesController::singleton_secciones();
    
    echo $controlador->addSeccion  (    
                                        $_POST['descripcion']
            );
}

function updateSeccion() {
    $controlador = SeccionesController::singleton_secciones();
    
    echo $controlador->updateSeccion(    $_POST['codigo'], 
                                        $_POST['descripcion']
            );
}

function deleteSeccion() {
    $controlador = SeccionesController::singleton_secciones();
    
    echo $controlador->deleteSeccion($_POST['codigo']);
}

function getSeccion() {
    $controlador = SeccionesController::singleton_secciones();
    
    echo $controlador->getSeccion($_POST['codigo']);
}

class SeccionesController {

    private static $instancia;
    private $conn;

    public function __construct() {
        try {
            include $_SERVER['DOCUMENT_ROOT']."/Giuliani/model/secciones.model.php";
            $this->conn = SeccionesModel::singleton_secciones();
        } catch (Exception $e) {
            $error = "Error!: " . $e->getMessage();
            return $error;
        }
    }

    public static function singleton_secciones() {
        if (!isset(self::$instancia)) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }
    
    public function getCountSecciones(){
        return intval($this->conn->getCountSecciones()[0]);
        
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

        include $_SERVER['DOCUMENT_ROOT']."/Giuliani/templates/secciones.busqueda.template.php";
        
    }
    
    public function addSeccion($descripcion) {
        $devuelve = $this->conn->addSeccion($descripcion);
        
        return $devuelve;
        
    }
    
    public function updateSeccion($codigo, $descripcion) {
        $devuelve = $this->conn->updateSeccion($codigo, $descripcion);
        
        return $devuelve;
        
    }
    
    public function deleteSeccion($codigo) {
        $devuelve = $this->conn->deleteSeccion($codigo);
        
        return $devuelve;
        
    }
    
    public function getSeccion($codigo) {
        $devuelve = $this->conn->getSeccion($codigo);
        
        return json_encode($devuelve[0]);
        
    }
}

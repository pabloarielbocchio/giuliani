<?php

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT);

session_start();

if (isset($_POST['funcion'])) {
    if (function_exists($_POST['funcion'])) {
        $_POST['funcion']();
    }
}

function getDashboardEjecutivo(){
    $controlador = DashboardController::singleton_dashboard();
    $fecha_desde = isset($_POST['fecha_desde']) ? $_POST['fecha_desde'] : date('Y-m-d', strtotime('-30 days'));
    $fecha_hasta = isset($_POST['fecha_hasta']) ? $_POST['fecha_hasta'] : date('Y-m-d');
    $proyecto = isset($_POST['proyecto']) ? intval($_POST['proyecto']) : 0;
    $usuario = isset($_POST['usuario']) ? $_POST['usuario'] : '0';
    echo $controlador->getDashboardEjecutivo($fecha_desde, $fecha_hasta, $proyecto, $usuario);
}

function getActividadSubidas(){
    $controlador = DashboardController::singleton_dashboard();
    $fecha_desde = isset($_POST['fecha_desde']) ? $_POST['fecha_desde'] : date('Y-m-d', strtotime('-30 days'));
    $fecha_hasta = isset($_POST['fecha_hasta']) ? $_POST['fecha_hasta'] : date('Y-m-d');
    $usuario = isset($_POST['usuario']) ? $_POST['usuario'] : '0';
    $proyecto = isset($_POST['proyecto']) ? intval($_POST['proyecto']) : 0;
    $tipo_archivo = isset($_POST['tipo_archivo']) ? $_POST['tipo_archivo'] : '';
    $granularidad = isset($_POST['granularidad']) ? $_POST['granularidad'] : 'dia';
    echo $controlador->getActividadSubidas($fecha_desde, $fecha_hasta, $usuario, $proyecto, $tipo_archivo, $granularidad);
}

function getActividadDescargas(){
    $controlador = DashboardController::singleton_dashboard();
    $fecha_desde = isset($_POST['fecha_desde']) ? $_POST['fecha_desde'] : date('Y-m-d', strtotime('-30 days'));
    $fecha_hasta = isset($_POST['fecha_hasta']) ? $_POST['fecha_hasta'] : date('Y-m-d');
    $granularidad = isset($_POST['granularidad']) ? $_POST['granularidad'] : 'dia';
    echo $controlador->getActividadDescargas($fecha_desde, $fecha_hasta, $granularidad);
}

function getDashboardUsuarios(){
    $controlador = DashboardController::singleton_dashboard();
    $fecha_desde = isset($_POST['fecha_desde']) ? $_POST['fecha_desde'] : date('Y-m-d', strtotime('-30 days'));
    $fecha_hasta = isset($_POST['fecha_hasta']) ? $_POST['fecha_hasta'] : date('Y-m-d');
    $granularidad = isset($_POST['granularidad']) ? $_POST['granularidad'] : 'dia';
    echo $controlador->getDashboardUsuarios($fecha_desde, $fecha_hasta, $granularidad);
}

function getOrdenesTrabajo(){
    $controlador = DashboardController::singleton_dashboard();
    $fecha_desde = isset($_POST['fecha_desde']) ? $_POST['fecha_desde'] : date('Y-m-d', strtotime('-30 days'));
    $fecha_hasta = isset($_POST['fecha_hasta']) ? $_POST['fecha_hasta'] : date('Y-m-d');
    $granularidad = isset($_POST['granularidad']) ? $_POST['granularidad'] : 'dia';
    echo $controlador->getOrdenesTrabajo($fecha_desde, $fecha_hasta, $granularidad);
}

function getProyectos(){
    $controlador = DashboardController::singleton_dashboard();
    $fecha_desde = isset($_POST['fecha_desde']) ? $_POST['fecha_desde'] : date('Y-m-d', strtotime('-30 days'));
    $fecha_hasta = isset($_POST['fecha_hasta']) ? $_POST['fecha_hasta'] : date('Y-m-d');
    $proyecto_seleccionado = isset($_POST['proyecto_seleccionado']) ? intval($_POST['proyecto_seleccionado']) : 0;
    $granularidad = isset($_POST['granularidad']) ? $_POST['granularidad'] : 'dia';
    echo $controlador->getProyectos($fecha_desde, $fecha_hasta, $proyecto_seleccionado, $granularidad);
}


class DashboardController {

    private static $instancia;
    private $conn;

    public function __construct() {
        try {
            include $_SERVER['DOCUMENT_ROOT']."/Giuliani/model/dashboard.model.php";
            $this->conn = DashboardModel::singleton_dashboard();
        } catch (Exception $e) {
            $error = "Error!: " . $e->getMessage();
            return $error;
        }
    }

    public static function singleton_dashboard() {
        if (!isset(self::$instancia)) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }
    
    public function getDashboardEjecutivo($fecha_desde, $fecha_hasta, $proyecto, $usuario){
        $datos = $this->conn->getDashboardEjecutivo($fecha_desde, $fecha_hasta, $proyecto, $usuario);
        $registros = $datos;
        include $_SERVER['DOCUMENT_ROOT']."/Giuliani/templates/dashboard.ejecutivo.template.php";
    }
    
    public function getActividadSubidas($fecha_desde, $fecha_hasta, $usuario, $proyecto, $tipo_archivo, $granularidad){
        $datos = $this->conn->getActividadSubidas($fecha_desde, $fecha_hasta, $usuario, $proyecto, $tipo_archivo, $granularidad);
        $registros = $datos;
        include $_SERVER['DOCUMENT_ROOT']."/Giuliani/templates/dashboard.subidas.template.php";
    }
    
    public function getActividadDescargas($fecha_desde, $fecha_hasta, $granularidad){
        $datos = $this->conn->getActividadDescargas($fecha_desde, $fecha_hasta, $granularidad);
        $registros = $datos;
        include $_SERVER['DOCUMENT_ROOT']."/Giuliani/templates/dashboard.descargas.template.php";
    }
    
    public function getDashboardUsuarios($fecha_desde, $fecha_hasta, $granularidad){
        $datos = $this->conn->getDashboardUsuarios($fecha_desde, $fecha_hasta, $granularidad);
        $registros = $datos;
        include $_SERVER['DOCUMENT_ROOT']."/Giuliani/templates/dashboard.usuarios.template.php";
    }
    
    public function getOrdenesTrabajo($fecha_desde, $fecha_hasta, $granularidad){
        $datos = $this->conn->getOrdenesTrabajo($fecha_desde, $fecha_hasta, $granularidad);
        $registros = $datos;
        include $_SERVER['DOCUMENT_ROOT']."/Giuliani/templates/dashboard.ot.template.php";
    }
    
    public function getProyectos($fecha_desde, $fecha_hasta, $proyecto_seleccionado, $granularidad){
        $datos = $this->conn->getProyectos($fecha_desde, $fecha_hasta, $proyecto_seleccionado, $granularidad);
        $registros = $datos;
        include $_SERVER['DOCUMENT_ROOT']."/Giuliani/templates/dashboard.proyectos.template.php";
    }
    
    public function getClientes(){
        return $this->conn->getClientes();
    }
    
    public function getUsuarios(){
        return $this->conn->getUsuarios();
    }
}

?>


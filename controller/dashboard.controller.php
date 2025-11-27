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
    
    public function getClientes(){
        return $this->conn->getClientes();
    }
    
    public function getUsuarios(){
        return $this->conn->getUsuarios();
    }
}

?>


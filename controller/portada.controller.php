<?php

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT);

session_start();

if (isset($_POST['funcion'])) {
    if (function_exists($_POST['funcion'])) {
        $_POST['funcion']();
    }
}
class PortadaController{
    private static $instancia;
    private $conn1;
    public function __construct() {
        try {
            include $_SERVER['DOCUMENT_ROOT']."/Giuliani/model/portada.model.php";
            $this->conn1 = Portada_Model::singleton_portada();
        } catch (Exception $e) {
            $error = "Error!: " . $e->getMessage();
            return $error;
        }
    }

    public static function singleton_portada() {
        if (!isset(self::$instancia)) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }
    public function funcionAbarcadora()
    {
        $devuelvePortada=$this->getOtsportada();
        foreach ($devuelvePortada as $key) {
            $cliente=$key["cliente"];
        }

        include $_SERVER['DOCUMENT_ROOT']."/Giuliani/templates/files.archivos.tabla.php";
    }
    public function obtenerCamposprimerosdetalles($cod)
    {
        $devuelve=$this->conn1->obtenerCamposprimerosdetalles($cod);
        return $devuelve;
    }
    public function obtenerCampossectoressecciones($codPP,$valorSeleccion){
        $devuelve=$this->conn1->obtenerCampossectoressecciones($codPP,$valorSeleccion);
        return $devuelve;
    }
    // public function obtenerCampossectoressecciones($ot,$otd){
    //     $devuelve=$this->conn1->obtenerCampossectoressecciones($ot,$otd);
    //     return $devuelve;
    // }
    public function getArchivostablapersonalizado($idProductopersonalizado){
        $archivosPersonalizados=$this->conn1->getArchivostablapersonalizado($idProductopersonalizado);
        return $archivosPersonalizados;
    }
    public function getOtsportada()
    {
        $devuelve=$this->conn1->getOtsportada();
        return $devuelve;
    }
    public function productoDetalle($otprodDetalle,$valorSeleccion)
    {
        $devuelve=$this->conn1->productoDetalle($otprodDetalle,$valorSeleccion);
        return $devuelve;
    }
 
    public function getArchivosAll() {
        $devuelve = $this->conn1->getArchivosAll();        
        return $devuelve;
    }
    public function getOtparchivos($opc)
    {
        $archivos = $this->conn1->getarchivosOtp($opc);
        return $archivos;
    }
}   

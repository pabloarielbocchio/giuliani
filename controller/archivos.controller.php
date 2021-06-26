<?php

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT);

session_start();

if (isset($_POST['funcion'])) {
    if (function_exists($_POST['funcion'])) {
        $_POST['funcion']();
    }
}

function getRegistrosFiltro(){
    $controlador = ArchivosController::singleton_archivos();
    
    echo $controlador->getRegistrosFiltro($_POST['orderby'], $_POST['sentido'], $_POST['registros'], $_POST['pagina'], $_POST['busqueda']);
}

function addArchivo() {
    $controlador = ArchivosController::singleton_archivos();
    
    echo $controlador->addArchivo  (    
                                        $_POST['descripcion'],
                                        $_POST['ruta'],
                                        $_POST['fecha_hora'],
                                        $_POST['activo'],
                                        $_POST['cod_prod_na'],
                                        $_POST['cod_prod_nb'],
                                        $_POST['cod_prod_nc'],
                                        $_POST['cod_prod_nd'],
                                        $_POST['cod_prod_ne'],
                                        $_POST['cod_prod_nf'],
                                        $_POST['cod_prod_personalizado_id'],
                                        $_POST['cod_prod_estandar_id']
            );
}

function updateArchivo() {
    $controlador = ArchivosController::singleton_archivos();
    
    echo $controlador->updateArchivo(    $_POST['codigo'], 
                                        $_POST['descripcion'],
                                        $_POST['ruta'],
                                        $_POST['fecha_hora'],
                                        $_POST['activo'],
                                        $_POST['cod_prod_na'],
                                        $_POST['cod_prod_nb'],
                                        $_POST['cod_prod_nc'],
                                        $_POST['cod_prod_nd'],
                                        $_POST['cod_prod_ne'],
                                        $_POST['cod_prod_nf'],
                                        $_POST['cod_prod_personalizado_id'],
                                        $_POST['cod_prod_estandar_id']
            );
}

function deleteArchivo() {
    $controlador = ArchivosController::singleton_archivos();
    
    echo $controlador->deleteArchivo($_POST['codigo']);
}

function getArchivo() {
    $controlador = ArchivosController::singleton_archivos();
    
    echo $controlador->getArchivo($_POST['codigo']);
}

class ArchivosController {

    private static $instancia;
    private $conn;

    public function __construct() {
        try {
            include $_SERVER['DOCUMENT_ROOT']."/Giuliani/model/archivos.model.php";
            $this->conn = ArchivosModel::singleton_archivos();
        } catch (Exception $e) {
            $error = "Error!: " . $e->getMessage();
            return $error;
        }
    }

    public static function singleton_archivos() {
        if (!isset(self::$instancia)) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }
    
    public function getCountArchivos(){
        return intval($this->conn->getCountArchivos()[0]);
        
    }

    
    public function getRegistrosFiltro($orderby, $sentido, $registros, $pagina, $busqueda){
        
        $_SESSION["pagina"] = $pagina;
        
        $_SESSION["cant_reg"] = $registros;
        
        $_SESSION["busqueda"] = $busqueda;
                
        $_SESSION['orderby'] = $orderby;
        
        $_SESSION['sentido'] = $sentido;
        
        $devuelve = $this->conn->getRegistrosFiltro($orderby, $sentido, $registros, $pagina, $busqueda);
                
        $prod_a = $this->getProductosA();
        $prod_b = $this->getProductosB();
        $prod_c = $this->getProductosC();
        $prod_d = $this->getProductosD();
        $prod_e = $this->getProductosE();
        $prod_f = $this->getProductosF();
        $prod_s = $this->getProductosS();
        $prod_p = $this->getProductosP();                        

        $registros = $devuelve;
        
        $_SESSION['registros'] = $registros;

        include $_SERVER['DOCUMENT_ROOT']."/Giuliani/templates/archivos.busqueda.template.php";
        
    }
    
    public function addArchivo($descripcion, $ruta, $fecha_hora, $activo, $cod_prod_na, $cod_prod_nb, $cod_prod_nc, $cod_prod_nd, $cod_prod_ne, $cod_prod_nf, $cod_prod_personalizado_id, $cod_prod_estandar_id) {
        $devuelve = $this->conn->addArchivo($descripcion, $ruta, $fecha_hora, $activo, $cod_prod_na, $cod_prod_nb, $cod_prod_nc, $cod_prod_nd, $cod_prod_ne, $cod_prod_nf, $cod_prod_personalizado_id, $cod_prod_estandar_id);
        
        return $devuelve;
        
    }
    
    public function updateArchivo($codigo, $descripcion, $ruta, $fecha_hora, $activo, $cod_prod_na, $cod_prod_nb, $cod_prod_nc, $cod_prod_nd, $cod_prod_ne, $cod_prod_nf, $cod_prod_personalizado_id, $cod_prod_estandar_id) {
        $devuelve = $this->conn->updateArchivo($codigo, $descripcion, $ruta, $fecha_hora, $activo, $cod_prod_na, $cod_prod_nb, $cod_prod_nc, $cod_prod_nd, $cod_prod_ne, $cod_prod_nf, $cod_prod_personalizado_id, $cod_prod_estandar_id);
        
        return $devuelve;
        
    }
    
    public function deleteArchivo($codigo) {
        $devuelve = $this->conn->deleteArchivo($codigo);
        $devuelve = $this->conn->deleteArchivo_destino($codigo);
        $devuelve = $this->conn->deleteArchivo_otp($codigo);
        
        return $devuelve;
        
    }
    
    public function getArchivo($codigo) {
        $devuelve = $this->conn->getArchivo($codigo);
        
        return json_encode($devuelve[0]);
        
    }
    
    public function getProductosA() {
        $devuelve = $this->conn->getProductosA();        
        return $devuelve;
    }
    
    public function getProductosB() {
        $devuelve = $this->conn->getProductosB();        
        return $devuelve;
    }
    
    public function getProductosC() {
        $devuelve = $this->conn->getProductosC();        
        return $devuelve;
    }
    
    public function getProductosD() {
        $devuelve = $this->conn->getProductosD();        
        return $devuelve;
    }
    
    public function getProductosE() {
        $devuelve = $this->conn->getProductosE();        
        return $devuelve;
    }
    
    public function getProductosF() {
        $devuelve = $this->conn->getProductosF();        
        return $devuelve;
    }
    
    public function getProductosP() {
        $devuelve = $this->conn->getProductosP();        
        return $devuelve;
    }
    
    public function getProductosS() {
        $devuelve = $this->conn->getProductosS();        
        return $devuelve;
    }
    
    public function getLastArchivo() {
        $devuelve = $this->conn->getLastArchivo();        
        return $devuelve;
    }
    
    public function addArchivo_produccion($archivo, $produccion, $observaciones) {
        $devuelve = $this->conn->addArchivo_produccion($archivo, $produccion, $observaciones);        
        return $devuelve;
    }
    
    public function getDestinos() {
        $devuelve = $this->conn->getDestinos();        
        return $devuelve;
    }
    
    public function addArchivo_destino($archivo, $destino, $observaciones) {
        $devuelve = $this->conn->addArchivo_destino($archivo, $destino, $observaciones);
        
        return $devuelve;
        
    }
}

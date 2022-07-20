<?php

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT);

session_start();

if (isset($_POST['funcion'])) {
    if (function_exists($_POST['funcion'])) {
        $_POST['funcion']();
    }
}

function getRegistrosFiltro(){
    $controlador = PlanosController::singleton_planos();
    
    echo $controlador->getRegistrosFiltro($_POST['orderby'], $_POST['sentido'], $_POST['registros'], $_POST['pagina'], $_POST['busqueda'], $_POST['sector']);
}

class PlanosController {

    private static $instancia;
    private $conn;

    public function __construct() {
        try {
            include $_SERVER['DOCUMENT_ROOT']."/Giuliani/model/planos.model.php";
            $this->conn = PlanosModel::singleton_planos();
        } catch (Exception $e) {
            $error = "Error!: " . $e->getMessage();
            return $error;
        }
    }

    public static function singleton_planos() {
        if (!isset(self::$instancia)) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }
    
    public function getCountPlanos(){
        return intval($this->conn->getCountPlanos()[0]);
        
    }

    
    public function getRegistrosFiltro($orderby, $sentido, $registros, $pagina, $busqueda, $sector){
        
        $_SESSION["pagina"] = $pagina;
        
        $_SESSION["cant_reg"] = $registros;
        
        $_SESSION["busqueda"] = $busqueda;
                
        $_SESSION['orderby'] = $orderby;
        
        $_SESSION['sentido'] = $sentido;
        
        $_SESSION['sector'] = $sector;
        
        $devuelve = $this->conn->getRegistrosFiltro($orderby, $sentido, $registros, $pagina, $busqueda);
                
        $prod_a = $this->getProductosA();
        $prod_b = $this->getProductosB();
        $prod_c = $this->getProductosC();
        $prod_d = $this->getProductosD();
        $prod_e = $this->getProductosE();
        $prod_f = $this->getProductosF();
        $prod_s = $this->getProductosS();
        $prod_x = $this->getProductosX();
        $prod_p = $this->getProductosP();
        
        $archivos = $this->getArchivosAll();
        
        $registros = [];              

        foreach($devuelve as $dev){
            $ot_detalle_id = $dev["ot_detalle_id"];
            $prod_estandar_id = $dev["prod_estandar_id"];
            $prod_personalizado_id = $dev["prod_personalizado_id"];

            $cod_prod_nf = -1;
            $cod_prod_ne = -1;
            $cod_prod_nd = -1;
            $cod_prod_nc = -1;
            $cod_prod_nb = -1;
            $cod_prod_na = -1;

            foreach($prod_x as $aux){
                if ($aux["prod_standar_id"] == $prod_estandar_id){
                    $cod_prod_nf[] = $aux["prod_f_id"];
                    break;
                }
            }

            foreach($prod_s as $aux){
                if ($aux["codigo"] == $prod_estandar_id){
                    $cod_prod_nd = $aux["cod_prod_nd"];
                    break;
                }
            }

            foreach($prod_d as $aux){
                if ($aux["codigo"] == $cod_prod_nd){
                    $cod_prod_nc = $aux["cod_prod_nc"];
                    break;
                }
            }

            foreach($prod_c as $aux){
                if ($aux["codigo"] == $cod_prod_nc){
                    $cod_prod_nb = $aux["cod_prod_nb"];
                    break;
                }
            }

            foreach($prod_b as $aux){
                if ($aux["codigo"] == $cod_prod_nb){
                    $cod_prod_na = $aux["cod_prod_na"];
                    break;
                }
            }
        }

        foreach($archivos as $aux){
            if ($aux["cod_prod_na"] == $cod_prod_na){
                $registros[$aux["codigo"]] = $aux;
            }
            if ($aux["cod_prod_nb"] == $cod_prod_nb){
                $registros[$aux["codigo"]] = $aux;
            }
            if ($aux["cod_prod_nc"] == $cod_prod_nc){
                $registros[$aux["codigo"]] = $aux;
            }
            if ($aux["cod_prod_nd"] == $cod_prod_nd){
                $registros[$aux["codigo"]] = $aux;
            }
            if ($aux["cod_prod_ne"] == $cod_prod_ne){
                $registros[$aux["codigo"]] = $aux;
            }
            if ($aux["cod_prod_personalizado_id"] == $prod_personalizado_id){
                $registros[$aux["codigo"]] = $aux;
            }
            if ($aux["cod_prod_estandar_id"] == $prod_estandar_id){
                $registros[$aux["codigo"]] = $aux;
            }
            if ($aux["cod_ot_detalle_id"] == $ot_detalle_id){
                $registros[$aux["codigo"]] = $aux;
            }
            foreach($cod_prod_nf as $f){
                if ($aux["cod_prod_nf"] == $f){
                    $registros[$aux["codigo"]] = $aux;
                }
            }
        }

        
        $_SESSION['registros'] = $registros;

        include $_SERVER['DOCUMENT_ROOT']."/Giuliani/templates/planos.busqueda.template.php";
        
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
    
    public function getProductosX() {
        $devuelve = $this->conn->getProductosX();        
        return $devuelve;
    }
    
    public function getSectores() {
        $devuelve = $this->conn->getSectores();        
        return $devuelve;
    }
    
    public function getArchivosAll() {
        $devuelve = $this->conn->getArchivosAll();        
        return $devuelve;
    }
    
    public function getOtp($codigo) {
        $devuelve = $this->conn->getOtp($codigo);        
        return $devuelve;
    }
}

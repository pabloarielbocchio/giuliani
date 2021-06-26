<?php

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT);

session_start();

if (isset($_POST['funcion'])) {
    if (function_exists($_POST['funcion'])) {
        $_POST['funcion']();
    }
}

function getRegistrosFiltro(){
    $controlador = Ot_detallesController::singleton_ot_detalles();
    
    echo $controlador->getRegistrosFiltro($_POST['orderby'], $_POST['sentido'], $_POST['registros'], $_POST['pagina'], $_POST['busqueda'], $_POST['ot']);
}

function getRegistrosFiltroSeguimiento(){
    $controlador = Ot_detallesController::singleton_ot_detalles();
    
    echo $controlador->getRegistrosFiltroSeguimiento($_POST['orderby'], $_POST['sentido'], $_POST['registros'], $_POST['pagina'], $_POST['busqueda'], $_POST['ot'], $_POST['estado']);
}

function addOt_detalle() {
    $controlador = Ot_detallesController::singleton_ot_detalles();
    
    echo $controlador->addOt_detalle  (    
                                        $_POST['item'],
                                        $_POST['cantidad'],
                                        $_POST['seccion'],
                                        $_POST['sector'],
                                        $_POST['estado'],
                                        $_POST['prioridad'],
                                        $_POST['ot'],
                                        $_POST['observaciones']
            );
}

function updateOt_detalle() {
    $controlador = Ot_detallesController::singleton_ot_detalles();
    
    echo $controlador->updateOt_detalle(    $_POST['codigo'], 
                                            $_POST['item'],
                                            $_POST['cantidad'],
                                            $_POST['seccion'],
                                            $_POST['sector'],
                                            $_POST['estado'],
                                            $_POST['prioridad'],
                                            $_POST['ot'],
                                            $_POST['observaciones']
            );
}

function deleteOt_detalle() {
    $controlador = Ot_detallesController::singleton_ot_detalles();
    
    echo $controlador->deleteOt_detalle($_POST['codigo']);
}

function getOt_detalle() {
    $controlador = Ot_detallesController::singleton_ot_detalles();
    
    echo $controlador->getOt_detalle($_POST['codigo']);
}

class Ot_detallesController {

    private static $instancia;
    private $conn;

    public function __construct() {
        try {
            include $_SERVER['DOCUMENT_ROOT']."/Giuliani/model/ot_detalles.model.php";
            $this->conn = Ot_detallesModel::singleton_ot_detalles();
        } catch (Exception $e) {
            $error = "Error!: " . $e->getMessage();
            return $error;
        }
    }

    public static function singleton_ot_detalles() {
        if (!isset(self::$instancia)) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }
    
    public function getCountOt_detalles(){
        return intval($this->conn->getCountOt_detalles()[0]);
        
    }
    
    public function getRegistrosFiltroSeguimiento($orderby, $sentido, $registros, $pagina, $busqueda, $ot, $estado){
        
        $_SESSION["pagina"] = $pagina;        
        $_SESSION["cant_reg"] = $registros;        
        $_SESSION["busqueda"] = $busqueda;                
        $_SESSION['orderby'] = $orderby;        
        $_SESSION['sentido'] = $sentido;
        $_SESSION['ot'] = $ot;
        $_SESSION['estado'] = $estado;
        
        $roles = $this->getRoles();
        $destinos = $this->getDestinos();
        $_sectores = $this->getSectores();
        $_secciones = $this->getSecciones();
        $secciones = []; 
        $sectores = []; 

        $devuelve = $this->conn->getRegistrosFiltro($orderby, $sentido, $registros, $pagina, $busqueda, $ot, $estado);

        foreach($devuelve as $dev){
            foreach($_secciones as $secc){
                if ($dev["seccion_id"] == $secc["codigo"]){
                    $secciones[$secc["codigo"]]["codigo"] = $secc["codigo"];
                    $secciones[$secc["codigo"]]["descripcion"] = $secc["descripcion"];                            
                    foreach($_sectores as $sect){
                        if ($dev["sector_id"] == $sect["codigo"]){
                            $secciones[$secc["codigo"]]["sectores"][$sect["codigo"]]["codigo"] = $sect["codigo"];       
                            $secciones[$secc["codigo"]]["sectores"][$sect["codigo"]]["descripcion"] = $sect["descripcion"];  
                            
                            $secciones[$secc["codigo"]]["sectores"][$sect["codigo"]]["registros"][] = $dev;          
                        }
                    }                              
                }
            }
        }

        $prods = $this->conn->getPartesProduccions($ot);
        foreach($prods as $key => $prod){
            $destinos_otps = $this->conn->getOtpsArchivosDestinos($prod["codigo"]);
            foreach($destinos_otps as $dtops){
                if ($dtops["cuenta"] > 0) {
                    $prods[$key]["destinos"][$dtops["codigo"]] = $dtops["cuenta"];
                }
            }
        }

        $estados = $this->conn->getPartesProduccionsEstados($ot);
        
        $registros = $devuelve;
        
        $_SESSION['registros'] = $registros;

        include $_SERVER['DOCUMENT_ROOT']."/Giuliani/templates/ot_detalles.seguimiento.template.php";
        
    }
    
    public function getRegistrosFiltro($orderby, $sentido, $registros, $pagina, $busqueda, $ot){
        
        $_SESSION["pagina"] = $pagina;        
        $_SESSION["cant_reg"] = $registros;        
        $_SESSION["busqueda"] = $busqueda;                
        $_SESSION['orderby'] = $orderby;        
        $_SESSION['sentido'] = $sentido;
        $_SESSION['ot'] = $ot;
        
        $roles = $this->getRoles();
        $destinos = $this->getDestinos();
        $_sectores = $this->getSectores();
        $_secciones = $this->getSecciones();
        $secciones = []; 
        $sectores = []; 

        $devuelve = $this->conn->getRegistrosFiltro($orderby, $sentido, $registros, $pagina, $busqueda, $ot);

        foreach($devuelve as $dev){
            foreach($_secciones as $secc){
                if ($dev["seccion_id"] == $secc["codigo"]){
                    $secciones[$secc["codigo"]]["codigo"] = $secc["codigo"];
                    $secciones[$secc["codigo"]]["descripcion"] = $secc["descripcion"];                            
                    foreach($_sectores as $sect){
                        if ($dev["sector_id"] == $sect["codigo"]){
                            $secciones[$secc["codigo"]]["sectores"][$sect["codigo"]]["codigo"] = $sect["codigo"];       
                            $secciones[$secc["codigo"]]["sectores"][$sect["codigo"]]["descripcion"] = $sect["descripcion"];  
                            
                            $secciones[$secc["codigo"]]["sectores"][$sect["codigo"]]["registros"][] = $dev;          
                        }
                    }                              
                }
            }
        } 

        $prods = $this->conn->getPartesProduccions($ot);
        $ot_header = $this->conn->getOt_header($ot)[0];
        
        $registros = $devuelve;
        
        $_SESSION['registros'] = $registros;

        include $_SERVER['DOCUMENT_ROOT']."/Giuliani/templates/ot_detalles.busqueda.template.php";
        
    }
    
    public function addOt_detalle($item,$cantidad,$seccion,$sector,$estado,$prioridad,$ot,$observaciones) {
        $devuelve = $this->conn->addOt_detalle($item,$cantidad,$seccion,$sector,$estado,$prioridad,$ot,$observaciones);
        if ($devuelve === 0){
            $ult_detalle = $this->conn->getLastOtDetalle()[0]["codigo"];
            if ($ult_detalle > 0){

                $evento = 7;
                $observaciones = "Generación Item Vendido " . $item;
                $this->conn->addOt_evento($ult_detalle, 0, $evento, 0, $observaciones);
            }
        }
        return $devuelve;        
    }
    
    public function updateOt_detalle($codigo, $item,$cantidad,$seccion,$sector,$estado,$prioridad,$ot,$observaciones) {
        $devuelve = $this->conn->updateOt_detalle($codigo, $item,$cantidad,$seccion,$sector,$estado,$prioridad,$ot,$observaciones);
        if ($devuelve === 0){
            $ult_detalle = $codigo;
            if ($ult_detalle > 0){
                $evento = 8;
                $observaciones = "Modificacion Item Vendido " . $item;
                $this->conn->addOt_evento($ult_detalle, 0, $evento, 0, $observaciones);
            }
        }
        return $devuelve;
        
    }
    
    public function deleteOt_detalle($codigo) {
        $devuelve = $this->conn->deleteOt_detalle($codigo);
        
        return $devuelve;
        
    }
    
    public function getOt_detalle($codigo) {
        $devuelve = $this->conn->getOt_detalle($codigo);
        
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
    
    public function getUnidades() {
        $devuelve = $this->conn->getUnidades();
        
        return $devuelve;
        
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
    
    public function getRoles() {
        $devuelve = $this->conn->getRoles();
        
        return $devuelve;
        
    }
    
    public function getDestinos() {
        $devuelve = $this->conn->getDestinos();
        
        return $devuelve;
        
    }
    public function formatNumber($prod){
        $prod["numero"] = $prod["codigo"];
        while (strlen($prod["numero"]) < 5 ){
            $prod["numero"] = "0" . $prod["numero"];
        }
        return $prod;
    }
}

<?php

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT);

session_start();

if (isset($_POST['funcion'])) {
    if (function_exists($_POST['funcion'])) {
        $_POST['funcion']();
    }
}

function getRegistrosFiltro(){
    $controlador = Ot_produccionsController::singleton_ot_produccions();
    
    echo $controlador->getRegistrosFiltro($_POST['orderby'], $_POST['sentido'], $_POST['registros'], $_POST['pagina'], $_POST['busqueda']);
}

function addOt_produccion() {
    $controlador = Ot_produccionsController::singleton_ot_produccions();
    
    echo $controlador->addOt_produccion  (    
                                        $_POST['avance'],
                                        $_POST['cantidad'],
                                        $_POST['standard'],
                                        $_POST['prod_id'],
                                        $_POST['personalizado_id'],
                                        $_POST['unidad'],
                                        $_POST['estado'],
                                        $_POST['prioridad'],
                                        $_POST['detalle'],
                                        $_POST['observaciones']
            );
}

function updateOt_produccion() {
    $controlador = Ot_produccionsController::singleton_ot_produccions();
    
    echo $controlador->updateOt_produccion(    $_POST['codigo'], 
                                                $_POST['avance'],
                                                $_POST['cantidad'],
                                                $_POST['standard'],
                                                $_POST['prod_id'],
                                                $_POST['personalizado_id'],
                                                $_POST['unidad'],
                                                $_POST['estado'],
                                                $_POST['prioridad'],
                                                $_POST['detalle'],
                                                $_POST['observaciones']
            );
}

function updateOt_produccionEstado() {
    $controlador = Ot_produccionsController::singleton_ot_produccions();
    
    echo $controlador->updateOt_produccionEstado(    $_POST['codigo'], 
                                                $_POST['atributo'],
                                                $_POST['avance'],
                                                $_POST['estado'],
                                                $_POST['code'],
                                                $_POST['ing_alcance'],
                                                $_POST['ing_planos']
            );
}

function deleteOt_produccion() {
    $controlador = Ot_produccionsController::singleton_ot_produccions();
    
    echo $controlador->deleteOt_produccion($_POST['codigo']);
}

function restablecerOt_produccion() {
    $controlador = Ot_produccionsController::singleton_ot_produccions();
    
    echo $controlador->restablecerOt_produccion($_POST['codigo']);
}

function getOt_produccion() {
    $controlador = Ot_produccionsController::singleton_ot_produccions();
    
    echo $controlador->getOt_produccion($_POST['codigo']);
}

function getOt_produccionEstado() {
    $controlador = Ot_produccionsController::singleton_ot_produccions();
    
    echo $controlador->getOt_produccionEstado($_POST['codigo'], $_POST['atributo'], $_POST["destino"]);
}

class Ot_produccionsController {

    private static $instancia;
    private $conn;

    public function __construct() {
        try {
            include $_SERVER['DOCUMENT_ROOT']."/Giuliani/model/ot_produccions.model.php";
            $this->conn = Ot_produccionsModel::singleton_ot_produccions();
        } catch (Exception $e) {
            $error = "Error!: " . $e->getMessage();
            return $error;
        }
    }

    public static function singleton_ot_produccions() {
        if (!isset(self::$instancia)) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }
    
    public function getCountOt_produccions(){
        return intval($this->conn->getCountOt_produccions()[0]);
        
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

        include $_SERVER['DOCUMENT_ROOT']."/Giuliani/templates/ot_produccions.busqueda.template.php";
        
    }
    
    public function addOt_produccion($avance, $cantidad, $standard, $prod_id, $personalizado_id, $unidad, $estado, $prioridad, $detalle, $observaciones) {
        $devuelve = $this->conn->addOt_produccion($avance, $cantidad, $standard, $prod_id, $personalizado_id, $unidad, $estado, $prioridad, $detalle, $observaciones);
        if ($devuelve === 0){
            $codigo = $this->conn->getLastOtProduccion()[0]["codigo"];
            $otp = $this->conn->getOt_produccion($codigo)[0];
            $ult_detalle = $otp["ot_detalle_id"];
            if ($ult_detalle > 0){

                $observaciones = "Actualizacion Estado OT Produccion, ingreso a Ingenieria. " . $otp["prod_standar"] . $otp["prod_personalizado"];
                $this->conn->addOt_estado($codigo, 1, null, $observaciones, 'ingenieria');
                
                $evento = 9;
                $observaciones = "Generacion OT Produccion " . $otp["prod_standar"] . $otp["prod_personalizado"];
                $this->conn->addOt_evento($ult_detalle, $codigo, $evento, 0, $observaciones);
            }
        }
        return $devuelve;
        
    }
    
    public function updateOt_produccion($codigo, $avance, $cantidad, $standard, $prod_id, $personalizado_id, $unidad, $estado, $prioridad, $detalle, $observaciones) {
        $devuelve = $this->conn->updateOt_produccion($codigo, $avance, $cantidad, $standard, $prod_id, $personalizado_id, $unidad, $estado, $prioridad, $detalle, $observaciones);
        if ($devuelve === 0){
            $otp = $this->conn->getOt_produccion($codigo)[0];
            $ult_detalle = $otp["ot_detalle_id"];
            if ($ult_detalle > 0){
                $evento = 11;
                $observaciones = "Modificaion OT Produccion " . $otp["prod_standar"] . $otp["prod_personalizado"];
                $this->conn->addOt_evento($ult_detalle, $codigo, $evento, 0, $observaciones);
            }
        }
        return $devuelve;        
    }
    
    public function updateOt_produccionEstado($codigo, $atributo, $avance, $estado, $code, $ing_alcance, $ing_planos) { //aca quede
        $ot_prod_estado_antes = $this->conn->getOt_produccionEstadoCode($code)[0];
        $devuelve = $this->conn->updateOt_produccionEstado($codigo, $atributo, $avance, $estado, $code, intval($ing_alcance), intval($ing_planos));
        $destinos = $this->conn->getDestinos();
        $_estado = $this->conn->getEstado($estado)[0];
        if ($devuelve === 0){
            $otp = $this->conn->getOt_produccion($codigo)[0];
            $ult_detalle = $otp["ot_detalle_id"];
            if ($ult_detalle > 0){
                $evento = 11;
                $observaciones = "Actualizacion Estado OT Produccion (" . $_estado["descripcion"] . ") " . $otp["prod_standar"] . $otp["prod_personalizado"];
                if ($estado == 2){ // en proceso
                    $observaciones = "Actualizacion Estado OT Produccion (" . $_estado["descripcion"] . " - " . $avance . "%) " . $otp["prod_standar"] . $otp["prod_personalizado"];
                 
                }
                $this->conn->addOt_evento($ult_detalle, $codigo, $evento, 0, $observaciones);
            }
            if ($ot_prod_estado_antes["estado_id"] != $estado){
                if ($estado == 3){ //aprobado
                    if ($atributo == 'ingenieria'){
                        $evento = 10;
                        $observaciones = "Cambio Estado OT Produccion, paso de ingenieria a produccion " . $otp["prod_standar"] . $otp["prod_personalizado"];
                        $this->conn->addOt_evento($ult_detalle, $codigo, $evento, 0, $observaciones);

                        foreach($destinos as $destino){
                            $observaciones = "Actualizacion Estado OT Produccion " . $otp["prod_standar"] . $otp["prod_personalizado"];
                            $this->conn->addOt_estado($codigo, 1, $destino["codigo"], $observaciones, 'produccion');
                        }
                                
                    }
                    if ($atributo == 'produccion'){
                        $evento = 10;
                        $observaciones = "Cambio Estado OT Produccion, paso de produccion a calidad " . $otp["prod_standar"] . $otp["prod_personalizado"];
                        $this->conn->addOt_evento($ult_detalle, $codigo, $evento, 0, $observaciones);

                        // validar que los destinos habilitados esten todos en aprobados

                        $aprobada = true;
                        $destinos_otps = $this->conn->getOtpsArchivosDestinos($codigo);
                        $aux = "";
                        foreach($destinos_otps as $dtops){
                            $aux = $this->conn->getOt_produccionDestino($codigo, $dtops["codigo"])[0];
                            if ($aux["estado_id"] != 3){
                                $aprobada = false;
                                break;
                            }
                        }
                        if ($aprobada) {
                            $observaciones = "Actualizacion Estado OT Produccion " . $otp["prod_standar"] . $otp["prod_personalizado"];
                            $this->conn->addOt_estado($codigo, 1, null, $observaciones, 'calidad');
                        }
                    
                    }
                    if ($atributo == 'calidad'){

                        $evento = 10;
                        $observaciones = "Cambio Estado OT Produccion, paso de calidad a gerencia " . $otp["prod_standar"] . $otp["prod_personalizado"];
                        $this->conn->addOt_evento($ult_detalle, $codigo, $evento, 0, $observaciones);

                        $observaciones = "Actualizacion Estado OT Produccion " . $otp["prod_standar"] . $otp["prod_personalizado"];
                        $this->conn->addOt_estado($codigo, 1, null, $observaciones, 'gerencia');
                    }
                }
            }
        }
        return $devuelve;
        
    }
    
    public function restablecerOt_produccion($codigo) {
        $devuelve = $this->conn->deleteOt_produccionEstados($codigo);
        $devuelve = $this->conn->updateOt_produccionEstadoAll($codigo, 'ingenieria', 0, 1);        
        $_estado = $this->conn->getEstado(1)[0];
        if ($devuelve === 0){
            $otp = $this->conn->getOt_produccion($codigo)[0];
            $ult_detalle = $otp["ot_detalle_id"];
            if ($ult_detalle > 0){
                $evento = 12;
                $observaciones = "Restablecer Progreso OT Produccion " . $otp["prod_standar"] . $otp["prod_personalizado"];
                $this->conn->addOt_evento($ult_detalle, $codigo, $evento, 0, $observaciones);
            }
        }
        return $devuelve;
        
    }
    
    public function deleteOt_produccion($codigo) {
        $devuelve = $this->conn->deleteOt_produccion($codigo);
        
        return $devuelve;
        
    }
    
    public function getOt_produccion($codigo) {
        $devuelve = $this->conn->getOt_produccion($codigo);
        
        return json_encode($devuelve[0]);
        
    }
    
    public function getOt_produccionEstado($codigo, $atributo, $destino) {
        $devuelve = $this->conn->getOt_produccionEstado($codigo, $atributo, $destino);
        
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
    
    public function getStandards() {
        $devuelve = $this->conn->getStandards();
        
        return $devuelve;
        
    }
    
    public function getPersonalizados() {
        $devuelve = $this->conn->getPersonalizados();
        
        return $devuelve;
        
    }
    
    public function getUnidades() {
        $devuelve = $this->conn->getUnidades();
        
        return $devuelve;
        
    }
    
    public function getOts() {
        $devuelve = $this->conn->getOts();
        
        return $devuelve;
        
    }
    
    public function getOtsDetalles() {
        $devuelve = $this->conn->getOtsDetalles();
        
        return $devuelve;
        
    }
}
